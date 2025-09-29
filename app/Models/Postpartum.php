<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postpartum extends Model
{
    protected $table = 'postpartum_records';

    protected $fillable = [
        'delivery_id',
        'remarks_id',
        'postpartum_bp',
        'postpartum_temp',
        'postpartum_rr',
        'postpartum_pr',
        'newborn_weight',
        'newborn_hc',
        'newborn_cc',
        'newborn_ac',
        'newborn_length',
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
        return $this->hasMany(PatientPdfRecord::class, 'postpartum_record_id');
    }
}
