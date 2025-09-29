<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryStatus extends Model
{
    // dahil singular ang table name
    protected $table = 'delivery_status';

    protected $fillable = ['name'];

    public function deliveries()
    {
        return $this->hasMany(PatientDelivery::class, 'delivery_status_id');
    }

}
