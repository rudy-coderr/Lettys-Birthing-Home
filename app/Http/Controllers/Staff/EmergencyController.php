<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Emergency;
use Illuminate\Support\Facades\Auth;

class EmergencyController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // default walang laman
        $emergencies = collect();

        if ($user->role === 'admin') {
            $emergencies = Emergency::with('branch')
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($user->role === 'staff') {
            $branchId = $user->staff->branch_id ?? null;

            if ($branchId) {
                $emergencies = Emergency::with('branch')
                    ->where('branch_id', $branchId) // ✅ filter by staff branch
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        // Walang laman? Wag magrender ng modal
        if ($emergencies->isEmpty()) {
            return '';
        }

        return view('partials.emergencyModal', compact('emergencies'));
    }

    public function destroy($id)
    {
        $emergency = Emergency::find($id);

        if (! $emergency) {
            return response()->json(['status' => 'error', 'message' => 'Emergency not found'], 404);
        }

        $emergency->delete();

        return response()->json(['status' => 'success', 'message' => 'Emergency acknowledged and removed']);
    }
}
