<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EmergencyAlertNotification extends Notification
{
    use Queueable;

    protected $emergency;

    public function __construct($emergency)
    {
        $this->emergency = $emergency;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // Default values
        $icon = 'fas fa-baby text-danger';
        $url  = '#';

        // Depende sa role ng notifiable
        if ($notifiable->role === 'admin') {
            $url = route('admin.emergencies.index');
        } elseif ($notifiable->role === 'staff') {
            $url = route('staff.emergencies.index');
        }

        return [
            'message' => "🚨 Emergency labor alert: {$this->emergency->name} ({$this->emergency->branch->branch_name})",
            'icon'    => $icon,
            'url'     => $url,
        ];
    }
}
