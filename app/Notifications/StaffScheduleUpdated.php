<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StaffScheduleUpdated extends Notification
{
    use Queueable;

    protected $staff;

    public function __construct($staff)
    {
        $this->staff = $staff;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $days  = $this->staff->workDays->pluck('day')->implode(', ');
        $shift = optional($this->staff->workDays->first())->shift ?? 'N/A';

        return [
            'message' => "Your schedule has been updated to {$shift} shift ({$days}).",
            'icon'    => 'fas fa-clock text-info',
            'url'     => route('schedules'),
        ];
    }

}
