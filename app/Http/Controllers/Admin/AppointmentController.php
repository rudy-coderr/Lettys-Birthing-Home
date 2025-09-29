<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function todaysAppointments()
    {
        $appointments = Appointment::with(['client', 'branch', 'appointment_status'])
            ->whereDate('appointment_date', \Carbon\Carbon::today())
            ->where('status_id', 1)
            ->whereDoesntHave('client.prenatalVisits')
            ->get();

        return view('admin.appointment.todays-appointment', compact('appointments'));
    }

    public function addAppointment($client_id = null)
    {
        $client = $client_id ? Client::findOrFail($client_id) : null;
        return view('admin.appointment.add-appointment', compact('client'));
    }

    public function storeAppointment(Request $request)
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

        // Find or create client
        $client = Client::firstOrCreate(
            ['client_phone' => $validated['client_phone']],
            [
                'first_name' => $validated['first_name'],
                'last_name'  => $validated['last_name'],

            ]
        );

        // Find branch
        $branch = Branch::where('branch_name', $validated['branch'])->firstOrFail();

        // Create Appointment (default status → Pending (2))
        $appointment = Appointment::create([
            'client_id'          => $client->id,
            'branch_id'          => $branch->id,
            'status_id'          => 1, // Pending
            'appointment_date'   => $validated['appointment_date'],
            'appointment_time'   => $validated['appointment_time'],
            'appointment_reason' => $validated['reason'],
        ]);

        // Flash Swal message
        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Appointment Added!',
            'text'  => 'The appointment has been scheduled successfully.',
        ]);

        return redirect()->route('admin.allAppointments');
    }

    public function allAppointments()
    {
                                           // Rule 2: Confirmed (1) but past due → No-Show (5)
        Appointment::where('status_id', 1) // Confirmed
            ->whereDate('appointment_date', '<', Carbon::today())
            ->update(['status_id' => 5]); // No-show

        // Fetch appointments for table (Confirmed, Cancelled, No-show) excluding today's confirmed
        $appointments = Appointment::with(['client', 'branch', 'appointment_status'])
            ->whereIn('status_id', [1, 3, 5])
            ->where(function ($q) {
                // Exclude confirmed appointments scheduled today
                $q->where('status_id', '!=', 1)
                    ->orWhereDate('appointment_date', '!=', Carbon::today());
            })
            ->orderByRaw("FIELD(status_id, 1, 3, 5)") // Prioritize Confirmed > Cancelled > No-show
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        return view('admin.appointment.pending-appointment', compact('appointments'));
    }

    public function firstCheckup($id = null)
    {
        $appointment = $id ? Appointment::with('client')->findOrFail($id) : null;

        return view('admin.appointment.first-checkup', compact('appointment'));
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
            $statusId = 1; // Confirmed (today or future)
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

    public function destroyAppointment(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointment,id',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);
        $appointment->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Appointment Deleted!',
            'text'  => 'The appointment was deleted successfully.',
        ]);

        return redirect()->back();
    }
    public function editAppointment($id)
    {
        $appointment = Appointment::with(['client', 'branch'])->findOrFail($id);

        return view('admin.appointment.edit-appointment', compact('appointment'));
    }
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

        return redirect()->route('allAppointments');
    }

}
