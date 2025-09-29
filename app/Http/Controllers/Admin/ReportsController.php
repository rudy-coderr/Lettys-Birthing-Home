<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientDelivery;
use App\Models\PatientPdfRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function reports(Request $request)
    {
        $selectedBranch = $request->get('branch', 'Combined');

        // Map branch names to IDs
        $branchMap = [
            'Santa Justina' => 1,
            'San Pedro'     => 2,
        ];
        $branchId = $selectedBranch !== 'Combined' ? ($branchMap[$selectedBranch] ?? null) : null;

        // ✅ Pass branch to methods
        $locationData = $this->getLocationDistribution($branchId);
        $mapLocations = $this->getMapLocationData($branchId);
        $chartData    = $this->getChartData($branchId);

        $prenatalRecords = PatientPdfRecord::with(['patient.client', 'visit.staff'])
            ->whereNotNull('prenatal_visit_id')
            ->when($branchId, fn($q) =>
                $q->whereHas('patient', fn($q2) => $q2->where('branch_id', $branchId))
            )
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $babyPdfRecords = PatientPdfRecord::with(['babyRegistration.delivery.staff', 'patient.client'])
            ->whereNotNull('baby_registration_id')
            ->when($branchId, fn($q) =>
                $q->whereHas('patient', fn($q2) => $q2->where('branch_id', $branchId))
            )
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.report.report', compact(
            'selectedBranch',
            'locationData',
            'mapLocations',
            'chartData',
            'prenatalRecords',
            'babyPdfRecords'
        ));
    }

    // 📊 Location distribution
    private function getLocationDistribution($branchId = null)
    {
        $query = DB::table('client')
            ->join('patient', 'client.id', '=', 'patient.client_id')
            ->join('address', 'client.address_id', '=', 'address.id');

        if ($branchId) {
            $query->where('patient.branch_id', $branchId);
        }

        $totalPatients = $query->count();

        return DB::table('client')
            ->join('patient', 'client.id', '=', 'patient.client_id')
            ->join('address', 'client.address_id', '=', 'address.id')
            ->when($branchId, fn($q) => $q->where('patient.branch_id', $branchId))
            ->groupBy('address.village', 'address.city_municipality', 'address.province')
            ->selectRaw('address.village, address.city_municipality, address.province, COUNT(*) as patient_count')
            ->orderByDesc('patient_count')
            ->get()
            ->map(function ($location) use ($totalPatients) {
                $percentage = $totalPatients > 0
                    ? round(($location->patient_count / $totalPatients) * 100, 1)
                    : 0;

                return [
                    'name'          => $location->village ?: $location->city_municipality,
                    'address'       => $location->city_municipality . ', ' . $location->province,
                    'patient_count' => $location->patient_count,
                    'percentage'    => $percentage,
                ];
            });
    }

    // 🗺️ Location data for map
    private function getMapLocationData($branchId = null)
    {
        return DB::table('client')
            ->join('patient', 'client.id', '=', 'patient.client_id')
            ->join('address', 'client.address_id', '=', 'address.id')
            ->when($branchId, fn($q) => $q->where('patient.branch_id', $branchId))
            ->groupBy('address.village', 'address.city_municipality', 'address.province')
            ->selectRaw('address.village, address.city_municipality, address.province, COUNT(*) as patient_count')
            ->orderByDesc('patient_count')
            ->get()
            ->map(function ($location) {
                $addressParts = array_filter([
                    $location->village,
                    $location->city_municipality,
                    $location->province,
                    'Philippines',
                ]);

                return [
                    'name'              => $location->village ?: $location->city_municipality,
                    'full_address'      => implode(', ', $addressParts),
                    'village'           => $location->village,
                    'city_municipality' => $location->city_municipality,
                    'province'          => $location->province,
                    'patient_count'     => $location->patient_count,
                ];
            });
    }

    // 📈 Monthly graph + delivery breakdown
    private function getChartData($branchId = null)
    {
        $monthlyData = [];
        for ($year = 2023; $year <= 2027; $year++) {
            $monthlyData[$year] = [];
            for ($month = 1; $month <= 12; $month++) {
                $count = DB::table('client')
                    ->join('patient', 'client.id', '=', 'patient.client_id')
                    ->when($branchId, fn($q) => $q->where('patient.branch_id', $branchId))
                    ->whereYear('client.created_at', $year)
                    ->whereMonth('client.created_at', $month)
                    ->count();
                $monthlyData[$year][] = $count;
            }
        }

        $completedDelivery = PatientDelivery::when($branchId, fn($q) =>
            $q->whereHas('patient', fn($q2) => $q2->where('branch_id', $branchId))
        )
            ->where('delivery_status_id', 2)
            ->count();

        $cancelledDelivery = PatientDelivery::when($branchId, fn($q) =>
            $q->whereHas('patient', fn($q2) => $q2->where('branch_id', $branchId))
        )
            ->where('delivery_status_id', 1)
            ->count();

        $deliveryBreakdown = [
            'completed_delivery' => $completedDelivery,
            'cancelled_delivery' => $cancelledDelivery,
        ];

        $totalPatients = DB::table('client')
            ->join('patient', 'client.id', '=', 'patient.client_id')
            ->when($branchId, fn($q) => $q->where('patient.branch_id', $branchId))
            ->count();

        return [
            'monthly_counts'     => $monthlyData,
            'total_patients'     => $totalPatients,
            'delivery_breakdown' => $deliveryBreakdown,
        ];
    }

    // 📊 AJAX JSON endpoint for charts
    public function getChartDataJson(Request $request)
    {
        $year      = $request->get('year', date('Y'));
        $fromMonth = (int) $request->get('from_month', 1);
        $toMonth   = (int) $request->get('to_month', 12);
        $branch    = $request->get('branch', 'Combined');

        $branchMap = [
            'Santa Justina' => 1,
            'San Pedro'     => 2,
        ];
        $branchId = $branch !== 'Combined' ? ($branchMap[$branch] ?? null) : null;

        // ✅ Collect data for full 12 months (para may basis pa rin)
        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $count = DB::table('client')
                ->join('patient', 'client.id', '=', 'patient.client_id')
                ->when($branchId, fn($q) => $q->where('patient.branch_id', $branchId))
                ->whereYear('client.created_at', $year)
                ->whereMonth('client.created_at', $month)
                ->count();
            $monthlyData[$month] = $count;
        }

        // ✅ Filtered (for graph display)
        $filteredData = [];
        for ($m = $fromMonth; $m <= $toMonth; $m++) {
            $filteredData[] = $monthlyData[$m];
        }

        // ✅ Range total
        $rangeTotal = array_sum($filteredData);

        // Deliveries
        $completedDelivery = PatientDelivery::when($branchId, fn($q) =>
            $q->whereHas('patient', fn($q2) => $q2->where('branch_id', $branchId))
        )
            ->where('delivery_status_id', 2)
            ->whereYear('created_at', $year)
            ->whereBetween(DB::raw('MONTH(created_at)'), [$fromMonth, $toMonth])
            ->count();

        $cancelledDelivery = PatientDelivery::when($branchId, fn($q) =>
            $q->whereHas('patient', fn($q2) => $q2->where('branch_id', $branchId))
        )
            ->where('delivery_status_id', 1)
            ->whereYear('created_at', $year)
            ->whereBetween(DB::raw('MONTH(created_at)'), [$fromMonth, $toMonth])
            ->count();

        $deliveryBreakdown = [
            'completed_delivery' => $completedDelivery,
            'cancelled_delivery' => $cancelledDelivery,
        ];

        return response()->json([
            'monthly_counts'     => $filteredData, // ✅ ito lang ipapasa sa graph
            'range_total'        => $rangeTotal,
            'delivery_breakdown' => $deliveryBreakdown,
            'period'             => [
                'from_month' => $fromMonth,
                'to_month'   => $toMonth,
                'year'       => $year,
            ],
        ]);
    }

}
