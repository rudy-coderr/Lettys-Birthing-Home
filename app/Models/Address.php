<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';

    protected $fillable = [
        'village',
        'city_municipality',
        'province',
    ];

    /**
     * Get all clients at this address
     */
    public function clients()
    {
        return $this->hasMany(Client::class, 'address_id', 'id');
    }
}
