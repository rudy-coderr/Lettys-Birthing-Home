<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Emergency;

class EmergencyController extends Controller
{
    public function index()
    {
        // Admin → lahat ng emergencies makikita
        $emergencies = Emergency::with('branch')
            ->latest()
            ->get();

        // Kung walang emergency records → huwag mag-render ng modal
        if ($emergencies->isEmpty()) {
            return ''; // ⚡ return empty string (safe for frontend)
        }

        return view('partials.emergencyModal', compact('emergencies'));
    }

    public function destroy($id)
    {
        $emergency = Emergency::find($id);

        if (! $emergency) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Emergency not found',
            ], 404);
        }

        $emergency->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Emergency acknowledged and removed',
        ]);
    }
}
