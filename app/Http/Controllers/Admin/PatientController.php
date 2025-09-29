<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\PatientPdfRecord;

class PatientController extends Controller
{
    public function currentPatients()
    {
        $patients = Client::with([
            'patient.branch',
            'prenatalVisits.visitInfo',
            'prenatalVisits.staff',
            'appointments',
        ])
        // 🔹 Only include clients with at least 1 prenatal visit
            ->whereHas('prenatalVisits')
            ->get()
            ->map(function ($client) {
                $prenatalVisits = $client->prenatalVisits ?? collect();

                // 🔹 Pinaka-latest prenatal visit (by id)
                $latestPrenatal = $prenatalVisits->sortByDesc('id')->first();

                // 🔹 Pinaka-latest visitInfo (by visit_number)
                $latestVisitInfo = $latestPrenatal->visitInfo
                    ->sortByDesc('visit_number')
                    ->first();

                $client->latest_visit_number = $latestVisitInfo->visit_number ?? null;
                $client->latest_visit_status = $latestPrenatal->prenatal_status_id;
                $client->latest_visit_next   = $latestVisitInfo->next_visit_date ?? null;
                $client->consulted_by        = $latestPrenatal->staff
                ? trim(($latestPrenatal->staff->first_name ?? '') . ' ' . ($latestPrenatal->staff->last_name ?? ''))
                : 'N/A';

                return $client;
            })
            ->filter(function ($client) {
                // ❌ Huwag isama kung latest status = 2 (Completed)
                return $client->latest_visit_status != 2;
            })
            ->values(); // reset keys

        $totalPatients = $patients->count();

        return view('admin.patient.current-patient', [
            'patients'      => $patients,
            'totalPatients' => $totalPatients,
        ]);
    }

    public function patientRecords()
    {
        $patients = Client::with(['patient', 'address'])
            ->whereHas('prenatalVisits')
            ->get();

        return view('admin.patient.all-patient-records', compact('patients'));
    }
    public function patientPdfRecords($id)
    {
        $patient = Client::with([
            'patient.deliveries.babyRegistration',
            'patient.deliveries.intrapartum',
            'patient.deliveries.postpartum',
            'prenatalVisits.visitInfo',
            'prenatalVisits.staff',
        ])->findOrFail($id);

        // Prenatal visits
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

        // ✅ Fetch all patient PDFs (covers prenatal, intrapartum, postpartum, registration)
        $allPatientPdfRecords = $patient->patient->pdfRecords()
            ->with(['visit.staff', 'intrapartumRecord', 'postpartumRecord', 'babyRegistration'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.patient.patient-pdf-records', [
            'patient'              => $patient,
            'visits'               => $visits,
            'babyRegistrations'    => $babyRegistrations,
            'intrapartumRecords'   => $intrapartumRecords,
            'postpartumRecords'    => $postpartumRecords,
            'allPatientPdfRecords' => $allPatientPdfRecords,
        ]);
    }

    public function pdfRecord($record)
    {
        $pdfRecord = PatientPdfRecord::findOrFail($record);

        return response($pdfRecord->file_data)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $pdfRecord->file_name . '"');
    }

    public function downloadRecord($patient, $record)
    {
        $pdfRecord = PatientPdfRecord::findOrFail($record);

        $fileName = $pdfRecord->file_name ?? 'patient-record.pdf';

        return response($pdfRecord->file_data, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

}
