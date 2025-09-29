<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientMedication extends Model
{
    protected $table   = 'patient_medications';
    public $timestamps = false;

    protected $fillable = [
        'patient_id',
        'notes',
        'prescribed_at',
    ];

    // 🔹 Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function items()
    {
        return $this->hasMany(PatientMedicationItem::class, 'patient_medication_id');
    }
}
