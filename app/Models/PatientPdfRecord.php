<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientPdfRecord extends Model
{
    protected $table = 'patient_pdf_records';

    protected $fillable = [
        'patient_id',
        'file_name',
        'prenatal_visit_id',
        'intrapartum_record_id',
        'postpartum_record_id',
        'file_data',
        'baby_registration_id',
    ];

    /**
     * Relationship to Patient/Client
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }
    public function visit()
    {
        return $this->belongsTo(PrenatalVisit::class, 'prenatal_visit_id');
    }
    public function staff()
    {
        return $this->visit->staff ?? null;
    }

    public function babyRegistration()
    {
        return $this->belongsTo(BabyRegistration::class, 'baby_registration_id');
    }

    public function intrapartumRecord()
    {
        return $this->belongsTo(Intrapartum::class, 'intrapartum_record_id');
    }

    public function postpartumRecord()
    {
        return $this->belongsTo(Postpartum::class, 'postpartum_record_id');
    }
    public function prenatalVisit()
    {
        return $this->belongsTo(PrenatalVisit::class, 'prenatal_visit_id');
    }

}
