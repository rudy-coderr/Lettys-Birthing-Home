<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BabyRegistration extends Model
{
    protected $fillable = [
        'delivery_id',
        'baby_first_name', 'baby_middle_name', 'baby_last_name',
        'sex', 'date_of_birth', 'time_of_birth',
        'place_of_birth', 'type_of_birth', 'birth_order', 'weight_at_birth',
    ];

    public function delivery()
    {
        return $this->belongsTo(PatientDelivery::class, 'delivery_id');
    }

    public function mother()
    {
        return $this->hasOne(BabyMother::class, 'registration_id');
    }

    public function father()
    {
        return $this->hasOne(BabyFather::class, 'registration_id');
    }

    public function additionalInfo()
    {
        return $this->hasOne(BabyAdditionalInfo::class, 'registration_id');
    }
     public function pdfRecords()
    {
        return $this->hasMany(PatientPdfRecord::class, 'baby_registration_id');
    }
}
