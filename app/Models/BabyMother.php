<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BabyMother extends Model
{
    protected $table = 'baby_mothers';
   protected $fillable = [
        'registration_id',
        'patient_id',
        'maiden_middle_name',
        'citizenship',
        'religion',
        'total_children_alive',
        'children_still_living',
        'children_deceased',
        'occupation',
    ];

    public function registration()
    {
        return $this->belongsTo(BabyRegistration::class, 'registration_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
