<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Emergency;
use Illuminate\Support\Facades\Auth;

class BaseStaffController extends Controller
{
    protected $emergencies;

    public function __construct()
    {
        $user = Auth::user();

        $this->emergencies = Emergency::with('branch')
            ->where('branch_id', $user->staff->branch_id ?? null)
            ->latest()
            ->get();

        view()->share('emergencies', $this->emergencies);
    }
}