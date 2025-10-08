<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\Branch;
use App\Models\Client;
use App\Models\InventoryItem;
use App\Models\MaternalVitals;
use App\Models\Patient;
use App\Models\PatientPdfRecord;
use App\Models\PrenatalVisit;
use App\Models\Remarks;
use App\Models\User;
use App\Models\VisitInfo;
use App\Models\PatientImmunization;
use App\Models\PatientImmunizationItem;
use App\Notifications\AppointmentScheduled;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class AllAppointmentController extends Controller
{
    /**
     * Send appointment data to N8N webhook
     */
    private function sendToN8NWebhook($appointmentData)
    {
        try {
            // Get webhook URL from config or env
            $webhookUrl = config('services.n8n.webhook_url') ?? env('N8N_WEBHOOK_URL');

            if (empty($webhookUrl)) {
                Log::warning('N8N webhook URL not configured');
                return;
            }

            // Send POST request to N8N webhook
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ])
                ->post($webhookUrl, $appointmentData);

            if ($response->successful()) {
                Log::info('Successfully sent appointment data to N8N webhook', [
                    'status_code' => $response->status(),
                ]);
            } else {
                Log::error('Failed to send appointment data to N8N webhook', [
                    'status_code'   => $response->status(),
                    'response_body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception occurred while sending to N8N webhook', [
                'error'        => $e->getMessage(),
                'webhook_data' => $appointmentData,
            ]);
        }
    }

    /**
     * Prepare appointment data for webhook
     */
    private function prepareAppointmentWebhookData($appointment)
    {
        // Load relationships if not already loaded
        if (! $appointment->relationLoaded('client')) {
            $appointment->load('client');
        }
        if (! $appointment->relationLoaded('branch')) {
            $appointment->load('branch');
        }

        return [
            'first_name'         => $appointment->client->first_name,
            'last_name'          => $appointment->client->last_name,
            'appointment_date'   => $appointment->appointment_date,
            'appointment_time'   => $appointment->appointment_time,
            'branch'             => $appointment->branch->branch_name,
            'appointment_reason' => $appointment->appointment_reason,
        ];
    }

    public function todaysAppointments()
    {
        $user  = Auth::user();
        $staff = $user->staff;

        if (! $staff) {
            abort(403, 'No staff profile found for this user.');
        }

        $appointments = Appointment::with(['client', 'branch', 'appointment_status'])
            ->where('branch_id', $staff->branch_id)
            ->whereDate('appointment_date', Carbon::today())
            ->where('status_id', 1)
            ->get();

        return view('staff.appointment.today-appointment', compact('appointments'));
    }

    public function checkups($id = null)
    {
        $appointment = $id ? Appointment::with('client')->findOrFail($id) : null;

        // ✅ Fetch vaccines from inventory (category_id = 1)
        $vaccines = InventoryItem::where('category_id', 1)->get();

        return view('staff.appointment.checkup', compact('appointment', 'vaccines'));
    }

    public function storePrenatal(Request $request)
    {
        // 1. Validate request
        $validated = $request->validate([
            'first_name'          => 'required|string|max:255',
            'last_name'           => 'required|string|max:255',
            'village'             => 'required|string',
            'city_municipality'   => 'required|string',
            'province'            => 'required|string',
            'phone'               => 'required|string|max:20',
            'age'                 => 'required|integer|min:1|max:100',
            'marital_status'      => 'required|in:1,2,3',
            'visitDate'           => 'required|date',
            'gravida'             => 'required|integer|min:0',
            'para'                => 'required|integer|min:0',
            'lmp'                 => 'nullable|date',
            'edc'                 => 'nullable|date',
            'aog'                 => 'nullable|string',
            'fht'                 => 'nullable|numeric',
            'fh'                  => 'nullable|numeric',
            'weight'              => 'nullable|numeric',
            'bloodPressure'       => 'nullable|string',
            'temperature'         => 'nullable|numeric',
            'respiratoryRate'     => 'nullable|numeric',
            'pulseRate'           => 'nullable|numeric',
            'nextVisit'           => 'nullable|date',
            'nextVisitTime'       => 'nullable|string',
            'appointment_id'      => 'nullable|exists:appointment,id',
            'spouse_fname'        => 'nullable|string',
            'spouse_lname'        => 'nullable|string',
            'remarks'             => 'nullable|string|max:1000',

            // ✅ validation for vaccines
            'vaccines'            => 'nullable|array',
            'vaccines.*.item_id'  => 'nullable|exists:inventory_items,id',
            'vaccines.*.date'     => 'nullable|date',
            'vaccines.*.quantity' => 'nullable|integer|min:1',
        ]);

        // 2. Get staff branch
        $user  = Auth::user();
        $staff = $user->staff;

        // 3. Create or update Client
        $client = Client::updateOrCreate(
            ['client_phone' => $validated['phone']],
            [
                'first_name'   => $validated['first_name'],
                'last_name'    => $validated['last_name'],
                'client_phone' => $validated['phone'],
            ]
        );

        // 4. Create or update Address
        $address = Address::updateOrCreate(
            [
                'village'           => $validated['village'],
                'city_municipality' => $validated['city_municipality'],
                'province'          => $validated['province'],
            ]
        );

        // 5. Attach address to client
        $client->address()->associate($address);
        $client->save();

        // 6. Create or update Patient
        $patient = Patient::updateOrCreate(
            ['client_id' => $client->id],
            [
                'age'               => $validated['age'],
                'spouse_fname'      => $validated['spouse_fname'] ?? null,
                'spouse_lname'      => $validated['spouse_lname'] ?? null,
                'marital_status_id' => $validated['marital_status'],
                'branch_id'         => $staff->branch_id,
            ]
        );

        // 7. Compute visit number
        $latestVisit = $client->prenatalVisits()->latest()->first();
        if ($latestVisit && $latestVisit->prenatal_status_id == 2) {
            $visitNumber = 1;
        } else {
            $visitNumber = $client->prenatalVisits()->count() + 1;
        }

        // 8. If remarks provided
        $remarks = null;
        if (! empty($validated['remarks'])) {
            $remarks = Remarks::create(['notes' => $validated['remarks']]);
        }

        // 9. Create Prenatal Visit
        $prenatalVisit = PrenatalVisit::create([
            'client_id'          => $client->id,
            'staff_id'           => $staff->id,
            'prenatal_status_id' => 1,
            'remarks_id'         => $remarks ? $remarks->id : null,
            'lmp'                => $validated['lmp'] ?? null,
            'edc'                => $validated['edc'] ?? null,
            'aog'                => $validated['aog'] ?? null,
            'gravida'            => $validated['gravida'],
            'para'               => $validated['para'],
        ]);

        // 10. Create Visit Info
        $visitInfo = VisitInfo::create([
            'prenatal_visit_id' => $prenatalVisit->id,
            'visit_number'      => $visitNumber,
            'visit_date'        => $validated['visitDate'],
            'next_visit_date'   => $validated['nextVisit'] ?? null,
            'next_visit_time'   => $validated['nextVisitTime'] ? $validated['nextVisitTime'] . ':00' : null,
        ]);

        // 11. Save Maternal Vitals
        MaternalVitals::create([
            'prenatal_visit_id' => $prenatalVisit->id,
            'fht'               => $validated['fht'] ?? null,
            'fh'                => $validated['fh'] ?? null,
            'weight'            => $validated['weight'] ?? null,
            'blood_pressure'    => $validated['bloodPressure'] ?? null,
            'temperature'       => $validated['temperature'] ?? null,
            'respiratory_rate'  => $validated['respiratoryRate'] ?? null,
            'pulse_rate'        => $validated['pulseRate'] ?? null,
        ]);

        // ✅ 11.1 Save Immunization if vaccines provided
        if ($request->has('vaccines')) {
            $immunization = PatientImmunization::create([
                'patient_id'        => $patient->id,
                'prenatal_visit_id' => $prenatalVisit->id,
                'notes'             => $request->immunization_notes ?? null,
                'immunized_at'      => now(),
            ]);

            foreach ($request->vaccines as $vaccine) {
                if (empty($vaccine['item_id']) || empty($vaccine['date'])) {
                    continue;
                }

                PatientImmunizationItem::create([
                    'patient_immunization_id' => $immunization->id,
                    'item_id'                 => $vaccine['item_id'],
                    'quantity'                => $vaccine['quantity'] ?? 1,
                ]);

                // Decrement inventory stock
                $item = InventoryItem::find($vaccine['item_id']);
                if ($item) {
                    $item->decrement('quantity', $vaccine['quantity'] ?? 1);
                }
            }
        }

        // 12. Update Appointment Status → Completed (4)
        if (! empty($validated['appointment_id'])) {
            Appointment::where('id', $validated['appointment_id'])
                ->update(['status_id' => 4]);
        }

        // 13. Generate PDF
        $fullName  = str_replace(' ', '_', $client->first_name . '_' . $client->last_name);
        $visitDate = \Carbon\Carbon::parse($visitInfo->visit_date)->format('Y-m-d');
        $fileName  = "{$fullName}-visit{$visitNumber}-{$visitDate}.pdf";

        // include immunizations in PDF
        $immunizations = PatientImmunization::with('items.item')
            ->where('prenatal_visit_id', $prenatalVisit->id)
            ->get();

        $pdf = Pdf::loadView('staff.patient.pdf-record', [
            'patient'         => $client,
            'latestPrenatal'  => $prenatalVisit,
            'latestVisitInfo' => $visitInfo,
            'immunizations'   => $immunizations,
        ]);

        $pdfData = $pdf->output();

        // 14. Save PDF record
        PatientPdfRecord::create([
            'patient_id'        => $patient->id,
            'prenatal_visit_id' => $prenatalVisit->id,
            'file_name'         => $fileName,
            'file_data'         => $pdfData,
        ]);

        // 15. Audit Log
        AuditLog::create([
            'staff_id'   => $staff->id,
            'action'     => 'Added Prenatal Record',
            'details'    => 'Prenatal record created for '
            . $client->first_name . ' ' . $client->last_name
            . ' (Visit #' . $visitNumber . ') on ' . $visitInfo->visit_date,
            'created_at' => now(),
        ]);

        // 16. Flash success
        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Prenatal Record Saved!',
            'text'  => 'Prenatal record, immunizations, and PDF saved successfully.',
        ]);

        return redirect()->route('currentPatients');
    }

    public function pendingAppointment()
    {
        $user  = Auth::user();
        $staff = $user->staff;

        if (! $staff) {
            abort(403, 'No staff profile found for this user.');
        }

        // Rule 2: Confirmed (1) but past due → No-Show (5)
        Appointment::where('branch_id', $staff->branch_id)
            ->where('status_id', 1) // Confirmed
            ->whereDate('appointment_date', '<', Carbon::today())
            ->update(['status_id' => 5]); // No-show

        // Fetch appointments for table (Confirmed, Cancelled, No-show) excluding today's confirmed
        $appointments = Appointment::with(['client', 'branch', 'appointment_status'])
            ->where('branch_id', $staff->branch_id)
            ->whereIn('status_id', [1, 3, 5])
            ->where(function ($q) {
                // Exclude confirmed appointments scheduled today
                $q->where('status_id', '!=', 1)
                    ->orWhereDate('appointment_date', '!=', Carbon::today());
            })
            ->orderByRaw("FIELD(status_id, 1, 3, 5)")
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        return view('staff.appointment.pending-appointment', compact('appointments'));
    }

    public function addAppointment($client_id = null)
    {
        $client = $client_id ? Client::findOrFail($client_id) : null;
        return view('staff.appointment.add-appointment', compact('client'));
    }

    // Add this method to your AppointmentController

    public function getAvailableSlots(Request $request)
    {
        $date = $request->input('date');
        $branchId = $request->input('branch_id');

        if (!$date || !$branchId) {
            return response()->json(['slots' => []]);
        }

        // Define all possible time slots
        $allSlots = [
            '08:00:00', '08:30:00', '09:00:00', '09:30:00',
            '10:00:00', '10:30:00', '11:00:00', '11:30:00',
            '12:00:00', '12:30:00', '13:00:00', '13:30:00',
            '14:00:00', '14:30:00', '15:00:00', '15:30:00',
            '16:00:00'
        ];

        // Get booked appointments for the selected date and branch
        $bookedSlots = Appointment::where('appointment_date', $date)
            ->where('branch_id', $branchId)
            ->whereIn('status_id', [1, 3]) // Pending and Confirmed appointments
            ->pluck('appointment_time')
            ->toArray();

        // Filter available slots
        $availableSlots = [];
        foreach ($allSlots as $slot) {
            $slotTime = \Carbon\Carbon::parse($slot);
            $slotEndTime = $slotTime->copy()->addMinutes(30);
            
            $isBooked = false;
            foreach ($bookedSlots as $bookedTime) {
                $bookedStart = \Carbon\Carbon::parse($bookedTime);
                $bookedEnd = $bookedStart->copy()->addMinutes(30);
                
                // Check if slots overlap
                if ($slotTime < $bookedEnd && $slotEndTime > $bookedStart) {
                    $isBooked = true;
                    break;
                }
            }
            
            if (!$isBooked) {
                $availableSlots[] = [
                    'value' => $slot,
                    'label' => \Carbon\Carbon::parse($slot)->format('g:i A')
                ];
            }
        }

        return response()->json(['slots' => $availableSlots]);
    }

    public function storeAppointment(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name'       => 'required|string|max:255',
                'last_name'        => 'required|string|max:255',
                'client_phone'     => 'required|string|max:20',
                'appointment_date' => 'required|date',
                'appointment_time' => 'required',
                'branch'           => 'required|string',
                'reason'           => 'required|string',
            ]);

            // Find branch first
            $branch = Branch::where('branch_name', $validated['branch'])->first();
            
            if (!$branch) {
                return back()->with('swal', [
                    'icon'  => 'error',
                    'title' => 'Branch Not Found',
                    'text'  => 'The selected branch does not exist.',
                ])->withInput();
            }

            // Check if the time slot is still available
            $isSlotAvailable = !Appointment::where('appointment_date', $validated['appointment_date'])
                ->where('branch_id', $branch->id)
                ->whereIn('status_id', [1, 3])
                ->where(function ($query) use ($validated) {
                    $slotTime = \Carbon\Carbon::parse($validated['appointment_time']);
                    $slotEndTime = $slotTime->copy()->addMinutes(30);
                    
                    $query->where(function ($q) use ($slotTime, $slotEndTime) {
                        $q->whereRaw("TIME(appointment_time) < ?", [$slotEndTime->format('H:i:s')])
                        ->whereRaw("ADDTIME(TIME(appointment_time), '00:30:00') > ?", [$slotTime->format('H:i:s')]);
                    });
                })
                ->exists();

            if (!$isSlotAvailable) {
                return back()->with('swal', [
                    'icon'  => 'error',
                    'title' => 'Time Slot Unavailable',
                    'text'  => 'This time slot has already been booked. Please select another time.',
                ])->withInput();
            }

            // Create or find client
            $client = Client::firstOrCreate(
                ['client_phone' => $validated['client_phone']],
                [
                    'first_name'   => $validated['first_name'],
                    'last_name'    => $validated['last_name'],
                    'messenger_id' => null,
                ]
            );

            // Create Appointment
            $appointment = Appointment::create([
                'client_id'          => $client->id,
                'branch_id'          => $branch->id,
                'status_id'          => 1, // Pending
                'appointment_date'   => $validated['appointment_date'],
                'appointment_time'   => $validated['appointment_time'],
                'appointment_reason' => $validated['reason'],
            ]);

            // Audit Logs
            AuditLog::create([
                'staff_id'   => Auth::user()->staff->id ?? null,
                'action'     => 'Added Appointment',
                'details'    => 'Scheduled appointment for '
                    . $client->first_name . ' ' . $client->last_name
                    . ' at ' . $branch->branch_name
                    . ' on ' . $appointment->appointment_date . ' ' . $appointment->appointment_time,
                'created_at' => now(),
            ]);

            // 🔔 Notify all Admins and Staff
            $admins     = User::whereHas('admin')->get();
            $staffs     = User::whereHas('staff')->get();
            $recipients = $admins->merge($staffs);

            Notification::send($recipients, new AppointmentScheduled($appointment));

            // Flash Swal message
            session()->flash('swal', [
                'icon'  => 'success',
                'title' => 'Appointment Added!',
                'text'  => 'The appointment has been scheduled successfully.',
            ]);

            return redirect()->route('pendingAppointment');

        } catch (\Exception $e) {
            \Log::error('Error storing appointment: ' . $e->getMessage());
            
            return back()->with('swal', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => 'Failed to create appointment. Please try again.',
            ])->withInput();
        }
    }

    public function rescheduleAppointment(Request $request)
    {
        $request->validate([
            'appointment_id'   => 'required|exists:appointment,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);
        $newDate     = Carbon::parse($request->appointment_date);
        $today       = Carbon::today();

        // Determine status based on the new date
        if ($newDate->lt($today)) {
            $statusId = 5; // No-Show
        } else {
            $statusId = 1; // Confirmed
        }

        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'status_id'        => $statusId,
        ]);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Appointment Rescheduled!',
            'text'  => 'The appointment has been updated successfully.',
        ]);

        return redirect()->back();
    }

    public function cancelAppointment(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointment,id',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);
        $appointment->update(['status_id' => 3]); // Cancelled

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Appointment Cancelled!',
            'text'  => 'The appointment was cancelled successfully.',
        ]);

        return redirect()->back();
    }

    // Show Edit Form
    public function editAppointment($id)
    {
        $appointment = Appointment::with(['client', 'branch'])->findOrFail($id);

        return view('staff.appointment.edit-appointment', compact('appointment'));
    }

    // Update Appointment
    public function updateAppointment(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'client_phone'     => 'required|string|max:20',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'branch'           => 'required|string',
            'reason'           => 'required|string',
        ]);

        $appointment = Appointment::findOrFail($id);

        // Update client info
        $client = $appointment->client;
        $client->update([
            'first_name'   => $validated['first_name'],
            'last_name'    => $validated['last_name'],
            'client_phone' => $validated['client_phone'],
        ]);

        // Update branch
        $branch = Branch::where('branch_name', $validated['branch'])->firstOrFail();

        // Update appointment info
        $appointment->update([
            'branch_id'          => $branch->id,
            'appointment_date'   => $validated['appointment_date'],
            'appointment_time'   => $validated['appointment_time'],
            'appointment_reason' => $validated['reason'],
        ]);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Appointment Updated!',
            'text'  => 'The appointment has been updated successfully.',
        ]);

        return redirect()->route('pendingAppointment');
    }
}
