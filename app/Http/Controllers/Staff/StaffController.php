<?php
namespace App\Http\Controllers\Staff;

use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class StaffController extends BaseStaffController
{
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role === 'staff' && $user->staff) {
            $branchId = $user->staff->branch_id;

            // ✅ Total Patients
            $totalPatients = Client::whereHas('appointments', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })->count();

            // ✅ Today's Appointments
            $todaysAppointments = Appointment::where('branch_id', $branchId)
                ->whereDate('appointment_date', now()->toDateString())
                ->where('status_id', '!=', 4)
                ->count();

            // ✅ Pending Appointments
            $pendingAppointments = Appointment::where('branch_id', $branchId)
                ->where('status_id', 2)
                ->count();

            // ✅ Completed Visits
            $completedVisits = Client::with(['patient', 'prenatalVisits', 'patient.deliveries'])
                ->whereHas('patient', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                })
                ->get()
                ->filter(function ($client) {
                    $completed = $client->prenatalVisits
                        ->where('prenatal_status_id', 2)
                        ->sortByDesc('id');

                    if ($completed->isEmpty()) {
                        return false;
                    }

                    $latest         = $completed->first();
                    $linkedDelivery = $client->patient->deliveries
                        ->firstWhere('prenatal_visit_id', $latest->id);

                    if ($linkedDelivery && $linkedDelivery->delivery_status_id == 2) {
                        return false; // already delivered
                    }

                    return true;
                })
                ->count();

            // ✅ Appointments (exclude completed)
            $appointmentsData = Appointment::with('client')
                ->where('branch_id', $branchId)
                ->where('status_id', '!=', 4)
                ->select('appointment_date', 'appointment_time', 'appointment_reason', 'client_id')
                ->get()
                ->map(function ($appt) {
                    return [
                        'date'   => $appt->appointment_date,
                        'time'   => $appt->appointment_time,
                        'reason' => $appt->appointment_reason,
                        'client' => $appt->client ? $appt->client->first_name . ' ' . $appt->client->last_name : 'Unknown',
                    ];
                });

            // ✅ Add Next Prenatal Visits (branch only)
            $nextVisits = Client::with(['patient', 'prenatalVisits.visitInfo'])
                ->whereHas('patient', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                })
                ->get()
                ->map(function ($client) {
                    $prenatalVisits = $client->prenatalVisits ?? collect();
                    if ($prenatalVisits->isEmpty()) {
                        return null;
                    }

                    $latestPrenatal  = $prenatalVisits->sortByDesc('id')->first();
                    $latestVisitInfo = $latestPrenatal->visitInfo->sortByDesc('visit_number')->first();

                    // skip completed prenatal (status_id = 2)
                    if ($latestPrenatal->prenatal_status_id == 2) {
                        return null;
                    }

                    if (! $latestVisitInfo) {
                        return null;
                    }

                    return [
                        'date'   => $latestVisitInfo->next_visit_date,
                        'time'   => $latestVisitInfo->next_visit_time,
                        'reason' => 'Next Prenatal Visit',
                        'client' => $client->first_name . ' ' . $client->last_name,
                    ];
                })
                ->filter();

            $appointmentsData = $appointmentsData->merge($nextVisits);

        } else {
            // ✅ Admin: all branches
            $totalPatients      = Client::count();
            $todaysAppointments = Appointment::whereDate('appointment_date', now()->toDateString())
                ->where('status_id', '!=', 4)
                ->count();
            $pendingAppointments = Appointment::whereDate('appointment_date', '<', now()->subDay()->toDateString())
                ->where('status_id', '!=', 4)
                ->count();

            // ✅ Completed Visits (all branches)
            $completedVisits = Client::with(['prenatalVisits', 'patient.deliveries'])
                ->get()
                ->filter(function ($client) {
                    $completed = $client->prenatalVisits
                        ->where('prenatal_status_id', 2)
                        ->sortByDesc('id');

                    if ($completed->isEmpty()) {
                        return false;
                    }

                    $latest         = $completed->first();
                    $linkedDelivery = $client->patient->deliveries
                        ->firstWhere('prenatal_visit_id', $latest->id);

                    if ($linkedDelivery && $linkedDelivery->delivery_status_id == 2) {
                        return false; // already delivered
                    }

                    return true;
                })
                ->count();

            $appointmentsData = Appointment::with('client')
                ->where('status_id', '!=', 4)
                ->select('appointment_date', 'appointment_time', 'appointment_reason', 'client_id')
                ->get()
                ->map(function ($appt) {
                    return [
                        'date'   => $appt->appointment_date,
                        'time'   => $appt->appointment_time,
                        'reason' => $appt->appointment_reason,
                        'client' => $appt->client ? $appt->client->first_name . ' ' . $appt->client->last_name : 'Unknown',
                    ];
                });

            // ✅ Add Next Prenatal Visits (all branches)
            $nextVisits = Client::with(['prenatalVisits.visitInfo'])
                ->get()
                ->map(function ($client) {
                    $prenatalVisits = $client->prenatalVisits ?? collect();
                    if ($prenatalVisits->isEmpty()) {
                        return null;
                    }

                    $latestPrenatal  = $prenatalVisits->sortByDesc('id')->first();
                    $latestVisitInfo = $latestPrenatal->visitInfo->sortByDesc('visit_number')->first();

                    if ($latestPrenatal->prenatal_status_id == 2) {
                        return null;
                    }

                    if (! $latestVisitInfo) {
                        return null;
                    }

                    return [
                        'date'   => $latestVisitInfo->next_visit_date,
                        'time'   => $latestVisitInfo->next_visit_time,
                        'reason' => 'Next Prenatal Visit',
                        'client' => $client->first_name . ' ' . $client->last_name,
                    ];
                })
                ->filter();

            $appointmentsData = $appointmentsData->merge($nextVisits);
        }

        return view('staff.dashboard.dashboard', compact(
            'totalPatients',
            'todaysAppointments',
            'pendingAppointments',
            'appointmentsData',
            'completedVisits'
        ));
    }

}
