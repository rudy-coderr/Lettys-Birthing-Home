<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientDelivery extends Model
{
    protected $table    = 'patient_deliveries';
    protected $fillable = [
        'patient_id',
        'staff_id',
        'delivery_status_id','prenatal_visit_id'];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function intrapartum()
    {
        return $this->hasOne(Intrapartum::class, 'delivery_id');
    }

    public function postpartum()
    {
        return $this->hasOne(Postpartum::class, 'delivery_id');
    }

    public function babyRegistration()
    {
        return $this->hasOne(BabyRegistration::class, 'delivery_id');
    }

     public function deliveryStatus()
    {
        return $this->belongsTo(DeliveryStatus::class, 'delivery_status_id');
    }

    public function prenatalVisit()
    {
        return $this->belongsTo(PrenatalVisit::class, 'prenatal_visit_id');
    }

  
}
