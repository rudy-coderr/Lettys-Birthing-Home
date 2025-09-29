<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patient';

    protected $fillable = [
        'client_id',
        'patient_id',
        'age',
        'spouse_fname',
        'spouse_lname',
        'marital_status_id',

        'branch_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            if (empty($patient->patient_id)) {
                do {
                    $patientId = 'PT' . str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
                } while (self::where('patient_id', $patientId)->exists());

                $patient->patient_id = $patientId;
            }
        });
    }

    /**
     * Relationships
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status_id');
    }
    public function pdfRecords()
    {
        return $this->hasMany(PatientPdfRecord::class, 'patient_id', 'id');
        
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function deliveries()
    {
        return $this->hasMany(PatientDelivery::class, 'patient_id');
    }

    public function babyRegistrations()
    {
        return $this->hasManyThrough(
            BabyRegistration::class, // Final model
            PatientDelivery::class,  // Intermediate
            'patient_id',            // Foreign key on PatientDelivery table
            'delivery_id',           // Foreign key on BabyRegistration table
            'id',                    // Local key on Patient table
            'id'                     // Local key on PatientDelivery table
        );
    }

    

    public function medications()
    {
        return $this->hasMany(PatientMedication::class, 'patient_id');
    }

}
