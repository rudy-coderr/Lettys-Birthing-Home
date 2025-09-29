<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intrapartum extends Model
{
    protected $table    = 'intrapartum_records';
    protected $fillable = [
        'delivery_id', 'bp', 'temp', 'rr', 'pr',
        'fundic_height', 'fetal_heart_tone', 'internal_exam',
        'bag_of_water', 'baby_delivered', 'placenta_delivered', 'baby_sex',
        'remarks_id', // ✅ Add this
    ];

    public function delivery()
    {
        return $this->belongsTo(PatientDelivery::class, 'delivery_id');
    }

    public function remarks()
    {
        return $this->belongsTo(Remarks::class, 'remarks_id');
    }

    public function pdfRecords()
    {
        return $this->hasMany(PatientPdfRecord::class, 'intrapartum_record_id');
    }
}
