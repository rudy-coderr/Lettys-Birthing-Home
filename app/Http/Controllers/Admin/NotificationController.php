<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->data['url'] ?? route('admin.dashboard'));
    }

    public function fetchAppointments()
    {
        $notifications = Auth::user()
            ->notifications()
            ->where('created_at', '>=', now()->subDays(7)) // 🔹 only last 7 days
            ->latest()
            ->paginate(20);

        return view('admin.notification.all-notification', compact('notifications'));
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
