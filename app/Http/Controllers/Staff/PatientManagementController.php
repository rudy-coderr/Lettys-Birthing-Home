<?php
namespace App\Http\Controllers\Staff;

use App\Models\Address;
use App\Models\BabyAdditionalInfo;
use App\Models\BabyFather;
use App\Models\BabyMother;
use App\Models\BabyRegistration;
use App\Models\Client;
use App\Models\Intrapartum;
use App\Models\InventoryItem;
use App\Models\MaritalStatus;
use App\Models\Patient;
use App\Models\PatientDelivery;
use App\Models\PatientImmunization;
use App\Models\PatientImmunizationItem;
use App\Models\PatientPdfRecord;
use App\Models\Postpartum;
use App\Models\PrenatalVisit;
use App\Models\Remarks;
use App\Models\VisitInfo;
use App\Models\Branch;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PatientManagementController extends BaseStaffController
{
    public function currentPatients()
    {
        $user  = Auth::user();
        $staff = $user->staff;

        if (! $staff) {
            abort(403, 'No staff profile found for this user.');
        }

        $clients = Client::with(['patient', 'prenatalVisits.visitInfo'])
            ->whereHas('patient', function ($q) use ($staff) {
                $q->where('branch_id', $staff->branch_id);
            })
            ->get()
            ->map(function ($client) {
                $prenatalVisits = $client->prenatalVisits ?? collect();

                if ($prenatalVisits->isEmpty()) {
                    $client->latest_visit_number = null;
                    $client->latest_visit_status = null;
                    $client->latest_visit_next   = null;
                    return $client;
                }

                // 🔹 Get latest prenatal visit by ID
                $latestPrenatal = $prenatalVisits->sortByDesc('id')->first();

                // 🔹 Get its latest visitInfo (highest visit_number)
                $latestVisitInfo = $latestPrenatal->visitInfo->sortByDesc('visit_number')->first();

                $client->latest_visit_number = $latestVisitInfo->visit_number ?? null;
                $client->latest_visit_status = $latestPrenatal->prenatal_status_id;
                $client->latest_visit_next   = $latestVisitInfo->next_visit_date ?? null;

                return $client;
            })
            ->filter(function ($client) {
                // Include patients with no visits OR whose latest prenatal visit is not completed
                return is_null($client->latest_visit_status) || $client->latest_visit_status != 2;
            });

        $totalPatients = $clients->count();

        return view('staff.patient.current-patient', [
            'patients'      => $clients,
            'totalPatients' => $totalPatients,
        ]);
    }
    public function completeVisits()
    {
        $user  = Auth::user();
        $staff = $user->staff;

        if (! $staff) {
            abort(403, 'No staff profile found for this user.');
        }

        $clients = Client::with([
            'patient',
            'prenatalVisits.visitInfo',
            'patient.deliveries',
        ])
            ->whereHas('patient', function ($q) use ($staff) {
                $q->where('branch_id', $staff->branch_id);
            })
            ->get()
            ->filter(function ($client) {

                // Only completed prenatal visits
                $completedVisits = $client->prenatalVisits
                    ->where('prenatal_status_id', 2)
                    ->sortByDesc('id');

                if ($completedVisits->isEmpty()) {
                    return false;
                }

                // Latest completed prenatal visit
                $latestVisit = $completedVisits->first();

                // Exclude if there is a delivery linked to this visit
                $linkedDelivery = $client->patient->deliveries
                    ->firstWhere('prenatal_visit_id', $latestVisit->id);

                if ($linkedDelivery && $linkedDelivery->delivery_status_id == 2) {
                    return false; // Already delivered
                }

                return true;
            });

        $totalPatients = $clients->count();

        return view('staff.patient.complete-visit', [
            'patients'      => $clients,
            'totalPatients' => $totalPatients,
        ]);
    }

    public function edit(Client $client)
    {
        $client->load('patient', 'address');
        return view('staff.patient.update-patient-info', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        // 🔹 Validate request
        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'client_phone'      => 'required|string|max:20',
            'village'           => 'nullable|string|max:255',
            'city_municipality' => 'nullable|string|max:255',
            'province'          => 'nullable|string|max:255',
            'age'               => 'nullable|integer|min:0',
            'spouse_fname'      => 'nullable|string|max:255',
            'spouse_lname'      => 'nullable|string|max:255',
            'marital_status_id' => 'nullable|integer|exists:marital_status,id',
        ]);

        // 🔹 Update Client
        $client->update($validated);

        // 🔹 Update or create Address
        if ($client->address) {
            $client->address->update($request->only(['village', 'city_municipality', 'province']));
        } else if ($request->filled(['village', 'city_municipality', 'province'])) {
            $address            = Address::create($request->only(['village', 'city_municipality', 'province']));
            $client->address_id = $address->id;
            $client->save();
        }

        // 🔹 Update Patient info if exists
        if ($client->patient) {
            $client->patient->update($request->only(['age', 'spouse_fname', 'spouse_lname', 'marital_status_id']));
        }

        return redirect()->back()->with('success', 'Patient information updated successfully.');
    }
    public function getAvailableVisitSlots(Request $request)
    {
        $date = $request->input('date');
        $branchId = $request->input('branch_id');

        if (!$date || !$branchId) {
            return response()->json(['slots' => []]);
        }

        $allSlots = [
            '08:00:00', '08:30:00', '09:00:00', '09:30:00',
            '10:00:00', '10:30:00', '11:00:00', '11:30:00',
            '12:00:00', '12:30:00', '13:00:00', '13:30:00',
            '14:00:00', '14:30:00', '15:00:00', '15:30:00',
            '16:00:00'
        ];

        $bookedSlots = VisitInfo::where('next_visit_date', $date)
            ->where('branch_id', $branchId)
            ->whereNotNull('next_visit_time')
            ->pluck('next_visit_time')
            ->toArray();

        $availableSlots = [];
        foreach ($allSlots as $slot) {
            $slotTime = \Carbon\Carbon::parse($slot);
            $slotEndTime = $slotTime->copy()->addMinutes(30);
            
            $isBooked = false;
            foreach ($bookedSlots as $bookedTime) {
                $bookedStart = \Carbon\Carbon::parse($bookedTime);
                $bookedEnd = $bookedStart->copy()->addMinutes(30);
                
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

    public function addRecords($id)
    {
        $patient = Client::with('patient.maritalStatus', 'address', 'prenatalVisits.visitInfo')->findOrFail($id);

        $latestPrenatal = $patient->prenatalVisits->sortByDesc('id')->first();

        if ($latestPrenatal && $latestPrenatal->visitInfo->isNotEmpty()) {
            $latestVisitNumber = $latestPrenatal->visitInfo->max('visit_number');
            $nextVisitNumber   = $latestVisitNumber + 1;
        } else {
            $nextVisitNumber = 1;
        }

        $maritalStatuses = MaritalStatus::all();
        $vaccines = InventoryItem::where('category_id', 1)->get();
        $branches = Branch::all();

        return view('staff.patient.add-record', compact('patient', 'nextVisitNumber', 'maritalStatuses', 'vaccines', 'branches'));
    }
    public function storePrenatal(Request $request, $id)
    {
        $client = Client::with('patient', 'address')->findOrFail($id);

        // ✅ Validate main prenatal fields
        $validated = $request->validate([
            'visit_number'     => 'required|integer',
            'visit_date'       => 'required|date',
            'next_visit_date'  => 'nullable|date',
            'next_visit_time'  => 'nullable',

            'lmp'              => 'nullable|date',
            'edc'              => 'nullable|date',
            'aog'              => 'nullable|string',
            'gravida'          => 'required|integer',
            'para'             => 'required|integer',

            'fht'              => 'nullable|numeric',
            'fh'               => 'nullable|numeric',
            'weight'           => 'nullable|numeric',
            'blood_pressure'   => 'nullable|string',
            'temperature'      => 'nullable|numeric',
            'respiratory_rate' => 'nullable|numeric',
            'pulse_rate'       => 'nullable|numeric',

            'remarks'          => 'nullable|string',
        ]);

        // ✅ Create Remarks
        $remarksId = null;
        if (! empty($validated['remarks'])) {
            $remarks = \App\Models\Remarks::create([
                'notes' => $validated['remarks'],
            ]);
            $remarksId = $remarks->id;
        }

        // ✅ Create Prenatal Visit
        $prenatalVisit = $client->prenatalVisits()->create([
            'prenatal_status_id' => $request->prenatal_status_id ?? 1,
            'staff_id'           => auth()->user()->staff->id,
            'lmp'                => $validated['lmp'] ?? null,
            'edc'                => $validated['edc'] ?? null,
            'aog'                => $validated['aog'] ?? null,
            'gravida'            => $validated['gravida'],
            'para'               => $validated['para'],
            'remarks_id'         => $remarksId,
        ]);

        // ✅ Store Visit Info
        $prenatalVisit->visitInfo()->create([
            'visit_number'    => $validated['visit_number'],
            'visit_date'      => $validated['visit_date'],
            'next_visit_date' => $validated['next_visit_date'] ?? null,
            'next_visit_time' => $validated['next_visit_time'] ? $validated['next_visit_time'] . ':00' : null,
            'branch_id'       => $request->branch_id ?? null, // ADD THIS
        ]);

        // ✅ Store Maternal Vitals
        $prenatalVisit->maternalVitals()->create([
            'fht'              => $validated['fht'] ?? null,
            'fh'               => $validated['fh'] ?? null,
            'weight'           => $validated['weight'] ?? null,
            'blood_pressure'   => $validated['blood_pressure'] ?? null,
            'temperature'      => $validated['temperature'] ?? null,
            'respiratory_rate' => $validated['respiratory_rate'] ?? null,
            'pulse_rate'       => $validated['pulse_rate'] ?? null,
        ]);

        // ✅ Store Immunization if provided
        if ($request->has('vaccines')) {
            // Create parent immunization record
            $immunization = PatientImmunization::create([
                'patient_id'        => $client->patient->id,
                'prenatal_visit_id' => $prenatalVisit->id,
                'notes'             => $request->immunization_notes ?? null,
                'immunized_at'      => now(),
            ]);

            foreach ($request->vaccines as $vaccine) {
                if (empty($vaccine['item_id']) || empty($vaccine['date'])) {
                    continue; // skip invalid rows
                }

                PatientImmunizationItem::create([
                    'patient_immunization_id' => $immunization->id,
                    'item_id'                 => $vaccine['item_id'],
                    'quantity'                => $vaccine['quantity'] ?? 1,
                ]);

                // 🔹 Optional: bawasan ang stock ng vaccine sa inventory
                $item = InventoryItem::find($vaccine['item_id']);
                if ($item) {
                    $item->decrement('quantity', $vaccine['quantity'] ?? 1);
                }
            }
        }

        // ✅ Fetch latest visit info
        $latestVisitInfo = $prenatalVisit->visitInfo()->latest('visit_date')->first();

        // ✅ Fetch immunizations for this visit (FIXED relationship: items.item)
        $immunizations = PatientImmunization::with('items.item')
            ->where('prenatal_visit_id', $prenatalVisit->id)
            ->get();

        // ✅ Prepare file name
        $visitDate = \Carbon\Carbon::parse($validated['visit_date'])->format('Y-m-d');
        $fullName  = str_replace(' ', '_', $client->first_name . '_' . $client->last_name);
        $fileName  = "{$fullName}-visit{$validated['visit_number']}-{$visitDate}.pdf";

        // ✅ Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('staff.patient.pdf-record', [
            'patient'         => $client,
            'latestPrenatal'  => $prenatalVisit,
            'latestVisitInfo' => $latestVisitInfo,
            'immunizations'   => $immunizations, // 🔹 now available in PDF
        ]);

        $pdfData = $pdf->output();

        PatientPdfRecord::create([
            'patient_id'        => $client->patient->id,
            'prenatal_visit_id' => $prenatalVisit->id,
            'file_name'         => $fileName,
            'file_data'         => $pdfData,
        ]);

        // ✅ Success message
        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Prenatal Visit Recorded!',
            'text'  => 'Prenatal visit record has been added and PDF saved successfully.',
        ]);

        return redirect()->route('currentPatients');
    }

    public function patientRecords()
    {
        $user  = Auth::user();
        $staff = $user->staff;

        if (! $staff) {
            abort(403, 'No staff profile found for this user.');
        }

        $patients = Client::with(['patient', 'address'])
            ->whereHas('patient', function ($q) use ($staff) {
                $q->where('branch_id', $staff->branch_id);
            })
            ->whereHas('prenatalVisits')
            ->get();

        return view('staff.patient.all-record', compact('patients'));
    }

    public function patientPdfRecords($id)
    {
        $patient = Client::with([
            'patient.deliveries.babyRegistration',
            'patient.deliveries.intrapartum',
            'patient.deliveries.postpartum',
            'prenatalVisits.visitInfo',
        ])->findOrFail($id);

        // Fetch prenatal visits for display
        $visits = $patient->prenatalVisits()->orderBy('created_at', 'desc')->get();

        // Baby, intrapartum, postpartum records from deliveries
        $babyRegistrations = $patient->patient->deliveries
            ->map(fn($delivery) => $delivery->babyRegistration)
            ->filter();

        $intrapartumRecords = $patient->patient->deliveries
            ->map(fn($delivery) => $delivery->intrapartum)
            ->filter();

        $postpartumRecords = $patient->patient->deliveries
            ->map(fn($delivery) => $delivery->postpartum)
            ->filter();

        // ✅ Fetch all patient PDFs, even if prenatal_visit_id is null
        $allPatientPdfRecords = $patient->patient->pdfRecords()
            ->with(['visit', 'intrapartumRecord', 'postpartumRecord', 'babyRegistration'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff.patient.patient-record', [
            'patient'              => $patient,
            'visits'               => $visits,
            'babyRegistrations'    => $babyRegistrations,
            'intrapartumRecords'   => $intrapartumRecords,
            'postpartumRecords'    => $postpartumRecords,
            'allPatientPdfRecords' => $allPatientPdfRecords, // contains PDFs with null prenatal_visit_id
        ]);
    }

    public function downloadRecord($patient, $record)
    {
        $pdfRecord = PatientPdfRecord::findOrFail($record);

        $fileName = $pdfRecord->file_name ?? 'patient-record.pdf';

        return response($pdfRecord->file_data, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function pdfRecord($record)
    {
        $pdfRecord = PatientPdfRecord::findOrFail($record);

        return response($pdfRecord->file_data)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $pdfRecord->file_name . '"');
    }

    public function editLatestVisit($id)
    {
        $patient = Client::with([
            'patient',
            'address',
            'prenatalVisits.visitInfo',
            'prenatalVisits.maternalVitals',
            'prenatalVisits.remarks',
        ])->findOrFail($id);

        // 🔹 Kunin latest prenatal visit
        $latestPrenatal = $patient->prenatalVisits->sortByDesc('id')->first();

        if (! $latestPrenatal || $latestPrenatal->visitInfo->isEmpty()) {
            return redirect()->route('currentPatients')->with('swal', [
                'icon'  => 'error',
                'title' => 'No Records Found',
                'text'  => 'This patient has no prenatal visits yet.',
            ]);
        }

        // 🔹 Kunin latest visit info by highest visit_number
        $latestVisitInfo = $latestPrenatal->visitInfo
            ->sortByDesc('visit_number')
            ->first();

        // 🔹 Fetch all marital statuses
        $maritalStatuses = MaritalStatus::all();

        return view('staff.patient.edit-prenatal', compact(
            'patient',
            'latestPrenatal',
            'latestVisitInfo',
            'maritalStatuses'
        ));
    }

    public function updateLatestVisit(Request $request, $id)
    {
        // 🔹 Find client with related prenatal visits & patient
        $client  = Client::with(['prenatalVisits.remarks', 'patient'])->findOrFail($id);
        $patient = $client->patient;

        // 🔹 Get latest prenatal visit
        $latestPrenatal = $client->prenatalVisits()->latest('id')->first();
        if (! $latestPrenatal) {
            session()->flash('swal', [
                'icon'  => 'error',
                'title' => 'Oops!',
                'text'  => 'No prenatal visit found.',
            ]);
            return redirect()->route('currentPatients');
        }

        // 🔹 Validate input
        $validated = $request->validate([
            'lmp'              => 'nullable|date',
            'edc'              => 'nullable|date',
            'aog'              => 'nullable|integer',
            'gravida'          => 'nullable|integer',
            'para'             => 'nullable|integer',
            'remarks'          => 'nullable|string|max:1000',
            'fht'              => 'nullable|string|max:50',
            'fh'               => 'nullable|string|max:50',
            'weight'           => 'nullable|numeric',
            'blood_pressure'   => 'nullable|string|max:20',
            'temperature'      => 'nullable|numeric',
            'respiratory_rate' => 'nullable|integer',
            'pulse_rate'       => 'nullable|integer',
            'visit_number'     => 'nullable|integer',
            'visit_date'       => 'nullable|date',
            'next_visit_date'  => 'nullable|date',
            'next_visit_time'  => 'nullable|string',
        ]);

        // 🔹 Update remarks
        if (! empty($validated['remarks'])) {
            if ($latestPrenatal->remarks) {
                $latestPrenatal->remarks->update(['notes' => $validated['remarks']]);
            } else {
                $remark                     = Remarks::create(['notes' => $validated['remarks']]);
                $latestPrenatal->remarks_id = $remark->id;
            }
        }

        // 🔹 Update prenatal visit
        $latestPrenatal->update([
            'lmp'                => $validated['lmp'] ?? $latestPrenatal->lmp,
            'edc'                => $validated['edc'] ?? $latestPrenatal->edc,
            'aog'                => $validated['aog'] ?? $latestPrenatal->aog,
            'gravida'            => $validated['gravida'] ?? $latestPrenatal->gravida,
            'para'               => $validated['para'] ?? $latestPrenatal->para,
            'staff_id'           => Auth::user()->staff->id ?? $latestPrenatal->staff_id,
            'prenatal_status_id' => $latestPrenatal->prenatal_status_id,
            'remarks_id'         => $latestPrenatal->remarks_id,
        ]);

        // 🔹 Update maternal vitals
        $latestMaternal = $latestPrenatal->maternalVitals()->latest('id')->first();
        $maternalData   = [
            'fht'              => $validated['fht'] ?? null,
            'fh'               => $validated['fh'] ?? null,
            'weight'           => $validated['weight'] ?? null,
            'blood_pressure'   => $validated['blood_pressure'] ?? null,
            'temperature'      => $validated['temperature'] ?? null,
            'respiratory_rate' => $validated['respiratory_rate'] ?? null,
            'pulse_rate'       => $validated['pulse_rate'] ?? null,
        ];

        if ($latestMaternal) {
            $latestMaternal->update($maternalData);
        } else {
            $latestPrenatal->maternalVitals()->create($maternalData);
        }

        // 🔹 Update VisitInfo
        $latestVisitInfo = $latestPrenatal->visitInfo()->latest('visit_date')->first();
        if ($latestVisitInfo) {
            $latestVisitInfo->update([
                'visit_number'    => $validated['visit_number'] ?? $latestVisitInfo->visit_number,
                'visit_date'      => $validated['visit_date'] ?? $latestVisitInfo->visit_date,
                'next_visit_date' => $validated['next_visit_date'] ?? $latestVisitInfo->next_visit_date,
                'next_visit_time' => $validated['next_visit_time'] ?? $latestVisitInfo->next_visit_time,
            ]);
        }

        // 🔹 Regenerate PDF
        if ($latestVisitInfo && $patient) {
            $visitDate = Carbon::parse($latestVisitInfo->visit_date)->format('Y-m-d');
            $fullName  = str_replace(' ', '_', $client->first_name . '_' . $client->last_name);
            $fileName  = "{$fullName}-visit{$latestVisitInfo->visit_number}-{$visitDate}.pdf";

            $pdf = Pdf::loadView('staff.patient.pdf-record', [
                'patient'         => $client,
                'latestPrenatal'  => $latestPrenatal,
                'latestVisitInfo' => $latestVisitInfo,
                'immunizations'   => collect(),
            ]);

            PatientPdfRecord::updateOrCreate(
                [
                    'patient_id'        => $patient->id,
                    'prenatal_visit_id' => $latestPrenatal->id,
                ],
                [
                    'file_name' => $fileName,
                    'file_data' => $pdf->output(),
                ]
            );
        }

        // 🔹 SweetAlert flash message
        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Updated!',
            'text'  => 'Prenatal visit, maternal vitals & visit info updated successfully. PDF regenerated.',
        ]);

        return redirect()->route('currentPatients');
    }

    public function addPatient()
    {
        // ✅ Fetch marital statuses for dropdown
        $maritalStatuses = MaritalStatus::all();

        // ✅ Fetch vaccines from inventory (category_id = 1 for vaccines)
        $vaccines = InventoryItem::where('category_id', 1)->get();

        return view('staff.patient.add-patient', compact('maritalStatuses', 'vaccines'));
    }

    public function storePatientRecord(Request $request)
    {
        $validated = $request->validate([
            // Patient info
            'first_name'        => 'required|string|max:255',
            'middle_name'       => 'nullable|string|max:255',
            'last_name'         => 'required|string|max:255',
            'barangay'          => 'required|string|max:255',
            'municipality'      => 'required|string|max:255',
            'province'          => 'required|string|max:255',
            'phone'             => 'required|regex:/^09[0-9]{9}$/',
            'age'               => 'required|integer',
            'marital_status_id' => 'required|integer|in:1,2,3',
            'spouse_fname'      => 'nullable|string|max:255',
            'spouse_lname'      => 'nullable|string|max:255',

            // Visit info
            'visit_number'      => 'required|integer',
            'visit_date'        => 'required|date',
            'next_visit_date'   => 'nullable|date',
            'next_visit_time'   => 'nullable',

            // Pregnancy details
            'lmp'               => 'nullable|date',
            'edc'               => 'nullable|date',
            'aog'               => 'nullable|string',
            'gravida'           => 'required|integer',
            'para'              => 'required|integer',

            // Vitals
            'fht'               => 'nullable|numeric',
            'fh'                => 'nullable|numeric',
            'weight'            => 'nullable|numeric',
            'bp'                => 'nullable|string',
            'temp'              => 'nullable|numeric',
            'rr'                => 'nullable|numeric',
            'pr'                => 'nullable|numeric',

            // Remarks
            'remarks'           => 'nullable|string|max:1000',
        ]);

        $user  = Auth::user();
        $staff = $user->staff;
        if (! $staff) {
            abort(403, 'No staff profile found for this user.');
        }

        // --- Address ---
        $address = Address::create([
            'village'           => $validated['barangay'],
            'city_municipality' => $validated['municipality'],
            'province'          => $validated['province'],
        ]);

        // --- Client ---
        $client = Client::create([
            'first_name'   => $validated['first_name'],
            'middle_name'  => $validated['middle_name'] ?? null,
            'last_name'    => $validated['last_name'],
            'client_phone' => $validated['phone'],
            'address_id'   => $address->id,
        ]);

        // --- Patient ---
        $patient = $client->patient()->create([
            'age'               => $validated['age'],
            'marital_status_id' => $validated['marital_status_id'],
            'spouse_fname'      => $validated['spouse_fname'] ?? null,
            'spouse_lname'      => $validated['spouse_lname'] ?? null,
            'branch_id'         => $staff->branch_id,
        ]);

        // --- Remarks ---
        $remarks = null;
        if (! empty($validated['remarks'])) {
            $remarks = Remarks::create([
                'notes' => $validated['remarks'],
            ]);
        }

        // --- Prenatal Visit ---
        $prenatalVisit = $client->prenatalVisits()->create([
            'lmp'                => $validated['lmp'] ?? null,
            'edc'                => $validated['edc'] ?? null,
            'aog'                => $validated['aog'] ?? null,
            'gravida'            => $validated['gravida'],
            'para'               => $validated['para'],
            'staff_id'           => $staff->id,
            'prenatal_status_id' => 1,
            'remarks_id'         => $remarks?->id,
        ]);

        // --- Visit Info ---
        $prenatalVisit->visitInfo()->create([
            'visit_number'    => $validated['visit_number'],
            'visit_date'      => $validated['visit_date'],
            'next_visit_date' => $validated['next_visit_date'] ?? null,
            'next_visit_time' => $validated['next_visit_time'] ?? null,
        ]);

        // --- Maternal Vitals ---
        $prenatalVisit->maternalVitals()->create([
            'fht'              => $validated['fht'] ?? null,
            'fh'               => $validated['fh'] ?? null,
            'weight'           => $validated['weight'] ?? null,
            'blood_pressure'   => $validated['bp'] ?? null,
            'temperature'      => $validated['temp'] ?? null,
            'respiratory_rate' => $validated['rr'] ?? null,
            'pulse_rate'       => $validated['pr'] ?? null,
        ]);

        // --- Immunization (same logic as storePrenatal) ---
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

                $item = InventoryItem::find($vaccine['item_id']);
                if ($item) {
                    $item->decrement('quantity', $vaccine['quantity'] ?? 1);
                }
            }
        }

        // --- Generate PDF ---
        $latestVisitInfo = $prenatalVisit->visitInfo()->latest('visit_date')->first();
        $latestPrenatal  = $prenatalVisit;

        // also load immunizations
        $immunizations = PatientImmunization::with('items.item')
            ->where('prenatal_visit_id', $prenatalVisit->id)
            ->get();

        $visitDate = Carbon::parse($validated['visit_date'])->format('Y-m-d');
        $fullName  = str_replace(' ', '_', $client->first_name . '_' . $client->last_name);
        $fileName  = "{$fullName}-visit{$validated['visit_number']}-{$visitDate}.pdf";

        $pdf = Pdf::loadView('staff.patient.pdf-record', [
            'patient'         => $client,
            'latestPrenatal'  => $latestPrenatal,
            'latestVisitInfo' => $latestVisitInfo,
            'immunizations'   => $immunizations, // ✅ include in PDF
        ]);

        $pdfData = $pdf->output();

        PatientPdfRecord::create([
            'patient_id'        => $patient->id,
            'prenatal_visit_id' => $prenatalVisit->id,
            'file_name'         => $fileName,
            'file_data'         => $pdfData,
        ]);

        // --- SweetAlert flash message ---
        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Patient Added!',
            'text'  => 'Patient record, first prenatal visit, and immunizations saved successfully.',
        ]);

        return redirect()->route('currentPatients');
    }

    public function startIntrapartum(Patient $patient)
    {
        // Kunin latest delivery ng patient
        $delivery = PatientDelivery::where('patient_id', $patient->id)
            ->latest()
            ->first();

        if ($delivery) {
            // Check kung may intrapartum record na
            $intrapartum = Intrapartum::where('delivery_id', $delivery->id)->first();

            if ($intrapartum) {
                // ✅ Kung may intrapartum na → diretso postpartum stage
                return redirect()->route('postpartumStage', $delivery->id);
            }
        }

        // ❌ Kung wala pa (kahit walang delivery) → load intrapartum form
        return view('staff.patient.intrapartum-checkup', compact('patient'));
    }

    public function storeIntrapartum(Request $request, Patient $patient)
    {
        //  Validate inputs
        $validated = $request->validate([
            'bp'                 => 'required|string',
            'temp'               => 'required|string',
            'rr'                 => 'required|string',
            'pr'                 => 'required|string',
            'fundic_height'      => 'required|string',
            'fetal_heart_tone'   => 'required|string',
            'internal_exam'      => 'required|string',
            'bag_of_water'       => 'required|string',
            'baby_delivered'     => 'required|string',
            'placenta_delivered' => 'required|string',
            'baby_sex'           => 'required|string',
            'remarks'            => 'nullable|string',
        ]);

        //  Get the latest completed prenatal visit
        $latestVisit = $patient->client->prenatalVisits()
            ->where('prenatal_status_id', 2) // Completed
            ->orderByDesc('id')
            ->first();

        //  Get staff info from logged-in user
        $staff = Auth::user()->staff ?? null;

        //  Create delivery record
        $delivery = PatientDelivery::create([
            'patient_id'        => $patient->id,
            'staff_id'          => $staff?->id,
            'prenatal_visit_id' => $latestVisit?->id,

        ]);

        //  Create remark if provided
        $remarks_id = null;
        if (! empty($validated['remarks'])) {
            $remark = Remarks::create([
                'notes' => $validated['remarks'],
            ]);
            $remarks_id = $remark->id;
        }

        //  Save intrapartum record
        $intrapartumData                = $validated;
        $intrapartumData['delivery_id'] = $delivery->id;
        $intrapartumData['remarks_id']  = $remarks_id;
        unset($intrapartumData['remarks']);

        $intrapartum = Intrapartum::create($intrapartumData);

        //  Generate PDF for intrapartum record
        $pdf = PDF::loadView('staff.patient.pdf-intrapartum', [
            'patient'     => $patient,
            'delivery'    => $delivery,
            'intrapartum' => $intrapartum,
            'remarks'     => $intrapartum->remarks,
            'staff'       => $staff, // 🔑 para magamit sa Blade
        ]);

        $fileName = 'intrapartum_' . $intrapartum->id . '-' . now()->format('Y-m-d') . '.pdf';

        // ✅ Save PDF record in DB
        PatientPdfRecord::create([
            'patient_id'            => $patient->id,
            'intrapartum_record_id' => $intrapartum->id,
            'prenatal_visit_id'     => $latestVisit?->id,
            'file_name'             => $fileName,
            'file_data'             => $pdf->output(),
        ]);

        return redirect()->route('postpartumStage', $delivery->id)
            ->with('success', 'Intrapartum record saved successfully and PDF generated.');
    }

    public function cancelDelivery($deliveryId)
    {
        $delivery                     = PatientDelivery::findOrFail($deliveryId);
        $delivery->delivery_status_id = 1; // Cancelled
        $delivery->save();

        return redirect()->route('currentPatients')
            ->with('warning', 'Delivery has been cancelled.');
    }

    public function editIntrapartum($recordId)
    {
        $record = PatientPdfRecord::with([
            'intrapartumRecord.remarks',
        ])->findOrFail($recordId);

        return view('staff.patient.edit-intrapartum', compact('record'));
    }

    public function updateIntrapartum(Request $request, $recordId)
    {
        $validated = $request->validate([
            'bp'                 => 'required|string',
            'temp'               => 'required|string',
            'rr'                 => 'required|string',
            'pr'                 => 'required|string',
            'fundic_height'      => 'required|string',
            'fetal_heart_tone'   => 'required|string',
            'internal_exam'      => 'required|string',
            'bag_of_water'       => 'required|string',
            'baby_delivered'     => 'required|string',
            'placenta_delivered' => 'required|string',
            'baby_sex'           => 'required|string',
            'remarks'            => 'nullable|string',
        ]);

        $pdfRecord = PatientPdfRecord::with(['intrapartumRecord.delivery', 'intrapartumRecord.remarks'])
            ->findOrFail($recordId);

        $intrapartum = $pdfRecord->intrapartumRecord;
        $delivery    = $intrapartum->delivery;
        $patient     = $delivery->patient;
        $staff       = Auth::user()->staff ?? null;

        // Update remarks kung meron
        if (! empty($validated['remarks'])) {
            if ($intrapartum->remarks_id) {
                $intrapartum->remarks->update(['notes' => $validated['remarks']]);
            } else {
                $remark                  = Remarks::create(['notes' => $validated['remarks']]);
                $intrapartum->remarks_id = $remark->id;
            }
        }

        // Update intrapartum record
        $intrapartum->update($validated);

        // Regenerate PDF
        $pdf = PDF::loadView('staff.patient.pdf-intrapartum', [
            'patient'     => $patient,
            'delivery'    => $delivery,
            'intrapartum' => $intrapartum,
            'remarks'     => $intrapartum->remarks,
            'staff'       => $staff,
        ]);

        $fileName = 'intrapartum_' . $intrapartum->id . '-' . now()->format('Y-m-d') . '.pdf';

        $pdfRecord->update([
            'file_name' => $fileName,
            'file_data' => $pdf->output(),
        ]);

        $clientId = $patient->client->id;
        return redirect()->route('patient.pdfRecords', $clientId)
            ->with('success', 'Intrapartum record updated successfully and PDF regenerated.');
    }

    public function startPostpartum($deliveryId)
    {
        $delivery = PatientDelivery::with('patient')->findOrFail($deliveryId);

        // Check kung may postpartum record na
        $postpartum = Postpartum::where('delivery_id', $delivery->id)->first();

        if ($postpartum) {
            // Meron na → diretso sa baby registration
            return redirect()->route('babyRegistration', $delivery->id);
        }

        //  Wala pa → show postpartum form
        return view('staff.patient.postpartum-checkup', compact('delivery'));
    }

    public function storePostpartum(Request $request, $deliveryId)
    {
        //  Validate inputs
        $validated = $request->validate([
            'postpartum_bp'   => 'required|string',
            'postpartum_temp' => 'required|string',
            'postpartum_rr'   => 'required|string',
            'postpartum_pr'   => 'required|string',
            'newborn_weight'  => 'required|string',
            'newborn_hc'      => 'required|string',
            'newborn_cc'      => 'required|string',
            'newborn_ac'      => 'required|string',
            'newborn_length'  => 'required|string',
            'remarks'         => 'nullable|string',
            'redirect_to'     => 'required|string', // 🔑 bago
        ]);

        // ✅ Create remark if provided
        $remarks_id = null;
        if (! empty($validated['remarks'])) {
            $remark = Remarks::create([
                'notes' => $validated['remarks'],
            ]);
            $remarks_id = $remark->id;
        }

        // ✅ Prepare postpartum data
        $postpartumData                = $validated;
        $postpartumData['delivery_id'] = $deliveryId;
        $postpartumData['remarks_id']  = $remarks_id;
        unset($postpartumData['remarks'], $postpartumData['redirect_to']);

        // ✅ Save postpartum record
        $postpartum = Postpartum::create($postpartumData);

        // ✅ Load delivery + patient + staff info
        $delivery = PatientDelivery::with('patient.client')->findOrFail($deliveryId);
        $patient  = $delivery->patient;
        $staff    = Auth::user()->staff ?? null;

        // ✅ Generate PDF for postpartum record
        $pdf = PDF::loadView('staff.patient.pdf-postpartum', [
            'patient'    => $patient,
            'delivery'   => $delivery,
            'postpartum' => $postpartum,
            'remarks'    => $postpartum->remarks,
            'staff'      => $staff,
        ]);

        $fileName = 'postpartum_' . $postpartum->id . '-' . now()->format('Y-m-d') . '.pdf';

        // ✅ Save PDF record in DB
        PatientPdfRecord::create([
            'patient_id'           => $patient->id,
            'postpartum_record_id' => $postpartum->id,
            'prenatal_visit_id'    => $delivery->prenatal_visit_id,
            'file_name'            => $fileName,
            'file_data'            => $pdf->output(),
        ]);

        if ($request->redirect_to === 'all-record') {
            return redirect('staff/patientRecords')
                ->with('success', 'Postpartum record saved successfully and PDF generated.');
        }

        return redirect()->route('babyRegistration', $deliveryId)
            ->with('success', 'Postpartum record saved successfully and PDF generated.');
    }

    public function editPostpartum($recordId)
    {
        $record = PatientPdfRecord::with([
            'postpartumRecord.remarks',
        ])->findOrFail($recordId);

        return view('staff.patient.edit-postpartum', compact('record'));
    }
    public function updatePostpartum(Request $request, $recordId)
    {
        $validated = $request->validate([
            'postpartum_bp'   => 'required|string',
            'postpartum_temp' => 'required|string',
            'postpartum_rr'   => 'required|string',
            'postpartum_pr'   => 'required|string',
            'newborn_weight'  => 'required|string',
            'newborn_hc'      => 'required|string',
            'newborn_cc'      => 'required|string',
            'newborn_ac'      => 'required|string',
            'newborn_length'  => 'required|string',
            'remarks'         => 'nullable|string',
        ]);

        $pdfRecord = PatientPdfRecord::with(['postpartumRecord.delivery', 'postpartumRecord.remarks'])
            ->findOrFail($recordId);

        $postpartum = $pdfRecord->postpartumRecord;
        $delivery   = $postpartum->delivery;
        $patient    = $delivery->patient;
        $staff      = Auth::user()->staff ?? null;

        // Update remarks kung meron
        if (! empty($validated['remarks'])) {
            if ($postpartum->remarks_id) {
                $postpartum->remarks->update(['notes' => $validated['remarks']]);
            } else {
                $remark                 = Remarks::create(['notes' => $validated['remarks']]);
                $postpartum->remarks_id = $remark->id;
            }
        }

        // Update postpartum record
        $postpartum->update($validated);

        // Regenerate PDF
        $pdf = PDF::loadView('staff.patient.pdf-postpartum', [
            'patient'    => $patient,
            'delivery'   => $delivery,
            'postpartum' => $postpartum,
            'remarks'    => $postpartum->remarks,
            'staff'      => $staff,
        ]);

        $fileName = 'postpartum_' . $postpartum->id . '-' . now()->format('Y-m-d') . '.pdf';

        $pdfRecord->update([
            'file_name' => $fileName,
            'file_data' => $pdf->output(),
        ]);

        // ✅ Redirect sa tamang Client ID
        $clientId = $patient->client->id;

        return redirect()->route('patient.pdfRecords', $clientId)
            ->with('success', 'Postpartum record updated successfully and PDF regenerated.');
    }

    public function startBabyRegistration($deliveryId)
    {
        // Load delivery with nested relationships
        $delivery = PatientDelivery::with([
            'patient.client.address',
        ])->findOrFail($deliveryId);

        $patient = $delivery->patient; // patient record
        $client  = $patient->client;   // client record (name, address_id)
        $address = $client->address;   // actual address

        $motherInfo = [
            'first_name'   => $client->first_name ?? '',
            'last_name'    => $client->last_name ?? '',
            'age'          => $patient->age ?? '',
            'full_address' => $client->full_address ?? '',
        ];

        return view('staff.patient.baby-registration', compact('delivery', 'motherInfo'));
    }

    public function storeBabyRegistration(Request $request, $deliveryId)
    {
        $validated = $request->validate([
            // Baby Info
            'baby_first_name'              => 'required|string',
            'baby_last_name'               => 'required|string',
            'sex'                          => 'required|string',
            'date_of_birth'                => 'required|date',
            'time_of_birth'                => 'required',
            'place_of_birth'               => 'required|string',
            'type_of_birth'                => 'required|string',
            'weight_at_birth'              => 'required|string',
            'birth_order'                  => 'nullable|string',

            // Mother Info
            'mother_maiden_first_name'     => 'required|string',
            'mother_maiden_last_name'      => 'required|string',
            'mother_maiden_middle_name'    => 'nullable|string',
            'mother_age'                   => 'required|integer|min:0',
            'mother_address'               => 'required|string',
            'mother_citizenship'           => 'nullable|string',
            'mother_religion'              => 'nullable|string',
            'mother_total_children_alive'  => 'nullable|integer',
            'mother_children_still_living' => 'nullable|integer',
            'mother_children_deceased'     => 'nullable|integer',
            'mother_occupation'            => 'nullable|string',

            // Father Info
            'father_age'                   => 'nullable|integer',
            'father_middle_name'           => 'nullable|string',
            'father_address'               => 'nullable|string',
            'father_citizenship'           => 'nullable|string',
            'father_religion'              => 'nullable|string',
            'father_occupation'            => 'nullable|string',

            // Additional Info
            'marriage_date'                => 'nullable|date',
            'marriage_place'               => 'nullable|string',
            'birth_attendant'              => 'nullable|string',
        ]);

        // Load delivery, patient, client
        $delivery = PatientDelivery::with('patient.client')->findOrFail($deliveryId);
        $patient  = $delivery->patient;
        $client   = $patient->client;

        // Update mother client record
        $client->first_name = $request->mother_maiden_first_name;
        $client->last_name  = $request->mother_maiden_last_name;
        $client->save();

        // Update patient (mother’s age)
        $patient->age = $request->mother_age;
        $patient->save();

        // Create Baby Registration
        $babyRegistration = BabyRegistration::create([
            'delivery_id'      => $delivery->id,
            'baby_first_name'  => $validated['baby_first_name'],
            'baby_middle_name' => $request->baby_middle_name,
            'baby_last_name'   => $validated['baby_last_name'],
            'sex'              => $validated['sex'],
            'date_of_birth'    => $validated['date_of_birth'],
            'time_of_birth'    => $validated['time_of_birth'],
            'place_of_birth'   => $validated['place_of_birth'],
            'type_of_birth'    => $validated['type_of_birth'],
            'birth_order'      => $request->birth_order,
            'weight_at_birth'  => $validated['weight_at_birth'],
        ]);

        // Save father info
        $father = BabyFather::create([
            'registration_id' => $babyRegistration->id,
            'patient_id'      => $delivery->patient_id,
            'age'             => $request->father_age,
            'middle_name'     => $request->father_middle_name,
            'address'         => $request->father_address,
            'citizenship'     => $request->father_citizenship,
            'religion'        => $request->father_religion,
            'occupation'      => $request->father_occupation,
        ]);

        // Save mother info
        $mother = BabyMother::create([
            'registration_id'       => $babyRegistration->id,
            'patient_id'            => $delivery->patient_id,
            'age'                   => $request->mother_age,
            'maiden_middle_name'    => $request->mother_maiden_middle_name,
            'citizenship'           => $request->mother_citizenship,
            'religion'              => $request->mother_religion,
            'total_children_alive'  => $request->mother_total_children_alive,
            'children_still_living' => $request->mother_children_still_living,
            'children_deceased'     => $request->mother_children_deceased,
            'address'               => $request->mother_address,
            'occupation'            => $request->mother_occupation,
        ]);

        // Save additional info
        $marriage = BabyAdditionalInfo::create([
            'registration_id' => $babyRegistration->id,
            'marriage_date'   => $request->marriage_date,
            'marriage_place'  => $request->marriage_place,
            'birth_attendant' => $request->birth_attendant,
        ]);

        // Generate PDF
        $pdf = PDF::loadView('staff.patient.registration-pdf', [
            'delivery' => $delivery,
            'baby'     => $babyRegistration,
            'mother'   => $mother,   //  pass correct mother data
            'father'   => $father,   //  pass correct father data
            'marriage' => $marriage, //  pass correct additional info
        ]);

        $fileName = 'baby_registration_' . $babyRegistration->id . '_' . now()->format('Ymd') . '.pdf';

        // Save PDF in database with baby_registration_id
        PatientPdfRecord::create([
            'patient_id'           => $delivery->patient_id,
            'baby_registration_id' => $babyRegistration->id,
            'prenatal_visit_id'    => $delivery->prenatal_visit_id,
            'file_name'            => $fileName,
            'file_data'            => $pdf->output(),
        ]);

        $delivery->update([
            'delivery_status_id' => 2,
        ]);

        return redirect()->route('postpartumStage', $delivery->id)
            ->with('success', 'Baby registration saved successfully and PDF generated.');
    }

    public function editRegistration($recordId)
    {
        $record = PatientPdfRecord::with([
            'babyRegistration.mother.patient.client.address', // mother + address
            'babyRegistration.father.patient',                // father
            'babyRegistration.additionalInfo',                // marriage & birth attendant
        ])->findOrFail($recordId);

        $babyRegistration = $record->babyRegistration;
        $delivery         = $babyRegistration->delivery;
        $patient          = $delivery->patient; // Patient model (for age)
        $client           = $patient->client;   // Client model (for full_address)

        $mother     = $babyRegistration->mother;         // BabyMother model
        $father     = $babyRegistration->father;         // BabyFather model
        $additional = $babyRegistration->additionalInfo; // BabyAdditionalInfo model

        return view('staff.patient.edit-registration', compact(
            'record', 'babyRegistration', 'delivery', 'mother', 'father', 'additional', 'patient', 'client'
        ));
    }
    public function updateBabyRegistration(Request $request, $recordId)
    {
        $validated = $request->validate([
            // Baby Info
            'baby_first_name'              => 'required|string',
            'baby_middle_name'             => 'nullable|string',
            'baby_last_name'               => 'required|string',
            'sex'                          => 'required|string',
            'date_of_birth'                => 'required|date',
            'time_of_birth'                => 'required',
            'place_of_birth'               => 'required|string',
            'type_of_birth'                => 'required|string',
            'weight_at_birth'              => 'required|string',
            'birth_order'                  => 'nullable|string',

            // Mother Info
            'mother_maiden_first_name'     => 'required|string',
            'mother_maiden_last_name'      => 'required|string',
            'mother_maiden_middle_name'    => 'nullable|string',
            'mother_age'                   => 'required|integer|min:0',
            'mother_address'               => 'required|string',
            'mother_citizenship'           => 'nullable|string',
            'mother_religion'              => 'nullable|string',
            'mother_total_children_alive'  => 'nullable|integer',
            'mother_children_still_living' => 'nullable|integer',
            'mother_children_deceased'     => 'nullable|integer',
            'mother_occupation'            => 'nullable|string',

            // Father Info
            'father_age'                   => 'nullable|integer',
            'father_middle_name'           => 'nullable|string',
            'father_address'               => 'nullable|string',
            'father_citizenship'           => 'nullable|string',
            'father_religion'              => 'nullable|string',
            'father_occupation'            => 'nullable|string',

            // Additional Info
            'marriage_date'                => 'nullable|date',
            'marriage_place'               => 'nullable|string',
            'birth_attendant'              => 'nullable|string',
        ]);

        // 🔎 Get PDF record with all relationships
        $pdfRecord = PatientPdfRecord::with([
            'babyRegistration.mother',
            'babyRegistration.father',
            'babyRegistration.additionalInfo',
            'babyRegistration.delivery.patient.client',
        ])->findOrFail($recordId);

        $babyRegistration = $pdfRecord->babyRegistration;
        $delivery         = $babyRegistration->delivery;
        $patient          = $delivery->patient;
        $client           = $patient->client;

        $mother     = $babyRegistration->mother;
        $father     = $babyRegistration->father;
        $additional = $babyRegistration->additionalInfo;

        // ✅ Update baby registration
        $babyRegistration->update([
            'baby_first_name'  => $validated['baby_first_name'],
            'baby_middle_name' => $validated['baby_middle_name'] ?? null,
            'baby_last_name'   => $validated['baby_last_name'],
            'sex'              => $validated['sex'],
            'date_of_birth'    => $validated['date_of_birth'],
            'time_of_birth'    => $validated['time_of_birth'],
            'place_of_birth'   => $validated['place_of_birth'],
            'type_of_birth'    => $validated['type_of_birth'],
            'birth_order'      => $validated['birth_order'] ?? null,
            'weight_at_birth'  => $validated['weight_at_birth'],
        ]);

        // ✅ Update Client (mother's basic info)
        $client->update([
            'first_name' => $validated['mother_maiden_first_name'],
            'last_name'  => $validated['mother_maiden_last_name'],
        ]);

        // ✅ Update Patient (mother’s age)
        $patient->update([
            'age' => $validated['mother_age'],
        ]);

        // ✅ Update BabyMother (including extra fields)
        if ($mother) {
            $mother->update([
                'maiden_middle_name'    => $validated['mother_maiden_middle_name'] ?? null,
                'age'                   => $validated['mother_age'],
                'address'               => $validated['mother_address'],
                'citizenship'           => $validated['mother_citizenship'] ?? null,
                'religion'              => $validated['mother_religion'] ?? null,
                'total_children_alive'  => $validated['mother_total_children_alive'] ?? null,
                'children_still_living' => $validated['mother_children_still_living'] ?? null,
                'children_deceased'     => $validated['mother_children_deceased'] ?? null,
                'occupation'            => $validated['mother_occupation'] ?? null,
            ]);
        }

        // ✅ Update BabyFather (full fields)
        if ($father) {
            $father->update([
                'age'         => $validated['father_age'] ?? null,
                'middle_name' => $validated['father_middle_name'] ?? null,
                'address'     => $validated['father_address'] ?? null,
                'citizenship' => $validated['father_citizenship'] ?? null,
                'religion'    => $validated['father_religion'] ?? null,
                'occupation'  => $validated['father_occupation'] ?? null,
            ]);
        }

        // ✅ Update Additional Info
        if ($additional) {
            $additional->update([
                'marriage_date'   => $validated['marriage_date'] ?? null,
                'marriage_place'  => $validated['marriage_place'] ?? null,
                'birth_attendant' => $validated['birth_attendant'] ?? null,
            ]);
        }

        // ✅ Generate updated PDF
        $staff = Auth::user()->staff ?? null;
        $pdf   = PDF::loadView('staff.patient.registration-pdf', [
            'delivery' => $delivery,
            'baby'     => $babyRegistration,
            'mother'   => $mother,
            'father'   => $father,
            'marriage' => $additional,
            'staff'    => $staff,
        ]);

        $fileName = 'baby_registration_' . $babyRegistration->id . '_' . now()->format('Ymd') . '.pdf';

        // ✅ Update PDF record
        $pdfRecord->update([
            'file_name' => $fileName,
            'file_data' => $pdf->output(),
        ]);

        // ✅ Redirect to Client's PDF records
        return redirect()->route('patient.pdfRecords', $client->id)
            ->with('success', 'Baby registration updated successfully and PDF regenerated.');
    }

    public function editPrenatal($recordId)
    {
        // Kunin yung PDF record kasama relationships
        $record = PatientPdfRecord::with([
            'prenatalVisit.remarks',
            'prenatalVisit.visitInfo',
            'prenatalVisit.maternalVitals',
            'patient.client.address',
            'patient',
        ])->findOrFail($recordId);

        // Kunin client at address (via relationships)
        $client  = $record->patient->client;
        $address = $client->address ?? null;

        // Kunin yung prenatal visit directly from the record
        $prenatalVisit = $record->prenatalVisit;

        // Kunin vitals kung meron
        $maternalVitals = $prenatalVisit ? $prenatalVisit->maternalVitals()->first() : null;

        return view('staff.patient.edit-prenatal-record', compact(
            'record', 'client', 'address', 'prenatalVisit', 'maternalVitals'
        ));
    }

    public function updatePrenatal(Request $request, $recordId)
    {
        // ✅ Validate request data
        $validated = $request->validate([
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'village'           => 'required|string',
            'city_municipality' => 'required|string',
            'province'          => 'required|string',
            'phone'             => 'required|string|regex:/^09[0-9]{9}$/',
            'age'               => 'required|integer|min:1|max:100',
            'marital_status'    => 'required|integer',
            'spouse_fname'      => 'nullable|string',
            'spouse_lname'      => 'nullable|string',

            'lmp'               => 'nullable|date',
            'edc'               => 'nullable|date',
            'aog'               => 'nullable|string',
            'gravida'           => 'required|integer',
            'para'              => 'required|integer',

            'fht'               => 'nullable|numeric',
            'fh'                => 'nullable|numeric',
            'weight'            => 'nullable|numeric',
            'blood_pressure'    => 'nullable|string',
            'temperature'       => 'nullable|numeric',
            'respiratoryRate'   => 'nullable|numeric',
            'pulseRate'         => 'nullable|numeric',

            'remarks'           => 'nullable|string',
        ]);

        // ✅ Kunin yung PatientPdfRecord + relationships
        $pdfRecord = PatientPdfRecord::with([
            'prenatalVisit.remarks',
            'prenatalVisit.visitInfo',
            'prenatalVisit.maternalVitals',
            'patient.client.address',
        ])->findOrFail($recordId);

        $prenatalVisit  = $pdfRecord->prenatalVisit;
        $client         = $pdfRecord->patient->client;
        $address        = $client->address;
        $maternalVitals = $prenatalVisit->maternalVitals()->first();

        // ✅ Update client info
        $client->update([
            'first_name'   => $validated['first_name'],
            'last_name'    => $validated['last_name'],
            'client_phone' => $validated['phone'],
        ]);

        // ✅ Update address
        if ($address) {
            $address->update([
                'village'           => $validated['village'],
                'city_municipality' => $validated['city_municipality'],
                'province'          => $validated['province'],
            ]);
        }

        // ✅ Update patient info
        $client->patient->update([
            'age'               => $validated['age'],
            'marital_status_id' => $validated['marital_status'],
            'spouse_fname'      => $validated['spouse_fname'],
            'spouse_lname'      => $validated['spouse_lname'],
        ]);

        // ✅ Update prenatal visit
        $prenatalVisit->update([
            'lmp'     => $validated['lmp'] ?? null,
            'edc'     => $validated['edc'] ?? null,
            'aog'     => $validated['aog'] ?? null,
            'gravida' => $validated['gravida'],
            'para'    => $validated['para'],
        ]);

        // ✅ Update remarks (create if wala pa)
        if (! empty($validated['remarks'])) {
            if ($prenatalVisit->remarks) {
                $prenatalVisit->remarks->update(['notes' => $validated['remarks']]);
            } else {
                $remarks = Remarks::create(['notes' => $validated['remarks']]);
                $prenatalVisit->update(['remarks_id' => $remarks->id]);
            }
        }

        // ✅ Update maternal vitals
        if ($maternalVitals) {
            $maternalVitals->update([
                'fht'              => $validated['fht'] ?? null,
                'fh'               => $validated['fh'] ?? null,
                'weight'           => $validated['weight'] ?? null,
                'blood_pressure'   => $validated['blood_pressure'] ?? null,
                'temperature'      => $validated['temperature'] ?? null,
                'respiratory_rate' => $validated['respiratoryRate'] ?? null,
                'pulse_rate'       => $validated['pulseRate'] ?? null,
            ]);
        }

        // ✅ Regenerate PDF
        $latestVisitInfo = $prenatalVisit->visitInfo()->latest('visit_date')->first();
        $immunizations   = PatientImmunization::with('items.item')
            ->where('prenatal_visit_id', $prenatalVisit->id)
            ->get();

        $visitDate = optional($latestVisitInfo)->visit_date
            ? \Carbon\Carbon::parse($latestVisitInfo->visit_date)->format('Y-m-d')
            : now()->format('Y-m-d');

        $fullName = str_replace(' ', '_', $client->first_name . '_' . $client->last_name);
        $fileName = "{$fullName}-visit-update-{$visitDate}.pdf";

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('staff.patient.pdf-record', [
            'patient'         => $client,
            'latestPrenatal'  => $prenatalVisit,
            'latestVisitInfo' => $latestVisitInfo,
            'immunizations'   => $immunizations,
        ]);

        $pdfRecord->update([
            'file_name' => $fileName,
            'file_data' => $pdf->output(),
        ]);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Record Updated!',
            'text'  => 'Prenatal record updated successfully and PDF regenerated.',
        ]);

        return redirect()->route('patient.pdfRecords', $client->id);
    }

    public function postnatalCare($id)
    {
        // Kunin yung client
        $client = Client::with('patient')->findOrFail($id);

        // Kunin latest delivery ng pasyente
        $delivery = PatientDelivery::where('patient_id', $client->patient->id)
            ->latest()
            ->first();

        if (! $delivery) {
            return redirect()->back()->with('error', 'No delivery record found for this patient.');
        }

        // Ibalik sa postnatal-care blade
        return view('staff.patient.postnatal-care', [
            'patient'  => $client->patient,
            'delivery' => $delivery,
        ]);
    }

}
