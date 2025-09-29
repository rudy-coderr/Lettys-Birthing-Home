<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $table    = 'units';
    public $timestamps  = false;
    protected $fillable = ['name'];

    public function items()
    {
        return $this->hasMany(InventoryItem::class, 'unit_id');
    }
}
