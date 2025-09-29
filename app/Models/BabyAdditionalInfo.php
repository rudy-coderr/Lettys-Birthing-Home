<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BabyAdditionalInfo extends Model
{
     protected $table = 'baby_additional_info';
    protected $fillable = [
        'registration_id', 'marriage_date', 'marriage_place',
        'birth_attendant'
    ];

    public function registration()
    {
        return $this->belongsTo(BabyRegistration::class, 'registration_id');
    }
}
