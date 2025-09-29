<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrenatalVisit extends Model
{
    protected $table = 'prenatal_visit';

    protected $fillable = [
        'client_id',
        'staff_id',
        'prenatal_status_id',
        'remarks_id',
        'lmp',
        'edc',
        'aog',
        'gravida',
        'para',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function maternalVitals()
    {
        return $this->hasMany(MaternalVitals::class, 'prenatal_visit_id', 'id');
    }

    public function visitInfo()
    {
        return $this->hasMany(VisitInfo::class, 'prenatal_visit_id', 'id');
    }
    public function pdfRecords()
    {
        return $this->hasMany(PatientPdfRecord::class, 'prenatal_visit_id', 'id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(PrenatalStatus::class, 'prenatal_status_id');
    }

    public function deliveries()
    {
        return $this->hasMany(PatientDelivery::class, 'prenatal_visit_id');
    }

    public function remarks()
    {
        return $this->belongsTo(Remarks::class, 'remarks_id');
    }

    public function immunizations()
    {
        return $this->hasMany(PatientImmunization::class, 'prenatal_visit_id', 'id');
    }

}
