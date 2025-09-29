<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    use HasFactory;

    protected $table = 'bill_items';

    protected $fillable = [
        'bill_id',
        'inventory_item_id',
        'item_type',
        'quantity',
        'unit_price',
        'total',
    ];

    // RELATIONSHIPS

    // A BillItem belongs to a Bill
    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }

    // A BillItem optionally belongs to an Inventory Item
    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
