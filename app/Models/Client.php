<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'client';

    protected $fillable = [
        'messenger_id',
        'first_name',
        'last_name',
        'client_phone',
        'address_id',
    ];

    public $timestamps = false;

    /**
     * Relationships
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'client_id', 'id');
    }

    public function prenatalVisits()
    {
        return $this->hasMany(PrenatalVisit::class, 'client_id', 'id');
    }

    public function patient()
    {
        return $this->hasOne(Patient::class, 'client_id', 'id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }

    /**
     * Accessor for full address (via Patient -> Address)
     */
    public function getFullAddressAttribute()
    {
        $address = $this->address; // fetch directly from client

        if (! $address) {
            return '';
        }

        return trim(collect([
            $address->village,
            $address->city_municipality,
            $address->province,
        ])->filter()->implode(', '), ', ');
    }

}
