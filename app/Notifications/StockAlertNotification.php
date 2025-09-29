<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StockAlertNotification extends Notification
{
    use Queueable;

    protected $item;
    protected $status;

    public function __construct($item, $status)
    {
        $this->item   = $item;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $url = route('admin.inventory.medicines'); // default

        switch ($this->item->category_id) {
            case 2: // Medicine
                $url = route('admin.inventory.medicines');
                break;
            case 3: // Supply
                $url = route('admin.inventory.supplies');
                break;
            case 4: // Vaccine
                $url = route('admin.inventory.vaccines');
                break;
        }

        // 🔹 Icon depende sa status
        $icon = match ($this->status) {
            'Out of Stock' => 'fas fa-exclamation-triangle text-danger',
            'Low Stock'    => 'fas fa-boxes text-warning',
            'Expired'      => 'fas fa-calendar-times text-danger',
            default        => 'fas fa-info-circle text-muted',
        };

        // 🔹 Message depende sa status
        $message = match ($this->status) {
            'Expired' => "{$this->item->item_name} has expired! (Expiry: {$this->item->expiry_date})",
            default   => "{$this->item->item_name} is {$this->status}! (Current stock: {$this->item->quantity})",
        };

        return [
            'message' => $message,
            'icon'    => $icon,
            'url'     => $url,
        ];
    }
}
