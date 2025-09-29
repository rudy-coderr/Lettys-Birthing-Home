<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $table = 'inventory_items';

    protected $fillable = [
        'item_name',
        'category_id',
        'batch_no',
        'expiry_date',
        'quantity',
        'unit_id',
        'reorder_level',
    ];

    // 🔹 Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    // 🔹 Computed attribute for stock status
    public function getStockStatusAttribute()
    {
        if ($this->quantity <= 0) {
            return 'Out of Stock';
        } elseif ($this->quantity <= $this->reorder_level) {
            return 'Low Stock';
        }
        return 'Available';
    }

    public function patientMedicationItems()
    {
        return $this->hasMany(PatientMedicationItem::class, 'item_id');
    }

    protected static function booted()
    {
        static::updated(function ($item) {
            $admins = \App\Models\User::where('role', 'admin')->get();

            // 🔹 Stock Alerts
            if ($item->quantity <= 0) {
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\StockAlertNotification($item, 'Out of Stock'));
                }
            } elseif ($item->quantity <= $item->reorder_level) {
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\StockAlertNotification($item, 'Low Stock'));
                }
            }

            // 🔹 Expiry Alerts
            if (! empty($item->expiry_date) && now()->greaterThanOrEqualTo($item->expiry_date)) {
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\StockAlertNotification($item, 'Expired'));
                }
            }
        });
    }

    public function immunizationItems()
    {
        return $this->hasMany(PatientImmunizationItem::class, 'item_id');
    }

}
