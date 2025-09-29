<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BabyFather extends Model
{
    protected $fillable = [
        'registration_id',
        'patient_id',
        'middle_name',
        'age',
        'address',
        'citizenship',
        'religion',
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
