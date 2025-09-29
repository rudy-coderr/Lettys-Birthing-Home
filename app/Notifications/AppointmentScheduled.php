<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentScheduled extends Notification
{
    use Queueable;

    protected $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $url = route('pendingAppointment'); // default

        if ($notifiable->role === 'admin') {
            $url = route('admin.allAppointments');
        } elseif ($notifiable->role === 'staff') {
            $url = route('pendingAppointment');
        }

        return [
            'message' => "New appointment for {$this->appointment->client->first_name} {$this->appointment->client->last_name} "
                       . "at {$this->appointment->branch->branch_name} on "
                       . "{$this->appointment->appointment_date} {$this->appointment->appointment_time}",
            'icon'    => 'fas fa-calendar-check text-primary',
            'url'     => $url,
        ];
    }
}
