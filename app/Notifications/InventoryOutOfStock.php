<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InventoryOutOfStock extends Notification
{
    use Queueable;

    protected $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // Default URL
        $url = route('admin.inventory.medicines');

        // Adjust depende sa type
        if ($this->item->type === 'medicine') {
            $url = route('viewMedicine', $this->item->id);
        } elseif ($this->item->type === 'supply') {
            $url = route('viewMedSupply', $this->item->id);
        }

        return [
            'message' => "The {$this->item->type} '{$this->item->name}' is now OUT OF STOCK.",
            'icon'    => 'fas fa-box text-danger',
            'url'     => $url,
        ];
    }
}
