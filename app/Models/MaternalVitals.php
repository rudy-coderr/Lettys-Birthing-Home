<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaternalVitals extends Model
{
    protected $table = 'maternal_vitals';

    protected $fillable = [
        'prenatal_visit_id',
        'fht',
        'fh',
        'weight',
        'blood_pressure',
        'temperature',
        'respiratory_rate',
        'pulse_rate',
    ];

    public function prenatalVisit()
    {
        return $this->belongsTo(PrenatalVisit::class, 'prenatal_visit_id', 'id');
    }
}
