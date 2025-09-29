<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function schedules()
    {
        $user = Auth::user();

        // Check if the authenticated user has a staff record
        if (! $user || ! $user->staff) {
            abort(403, 'Unauthorized or no staff profile');
        }

        // Load staff along with workDays
        $staff = $user->staff->load('workDays');

        return view('staff.profile.schedule', compact('staff'));
    }

}
