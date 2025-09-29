<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientImmunization extends Model
{
    protected $table = 'patient_immunizations';
    public $timestamps = false;

    protected $fillable = [
        'patient_id',
        'prenatal_visit_id',
        'notes',
        'immunized_at',
    ];

    // 🔹 Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function prenatalVisit()
    {
        return $this->belongsTo(PrenatalVisit::class, 'prenatal_visit_id');
    }

    public function items()
    {
        return $this->hasMany(PatientImmunizationItem::class, 'patient_immunization_id');
    }
}
