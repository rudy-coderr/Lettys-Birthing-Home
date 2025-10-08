<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitInfo extends Model
{
    protected $table = 'visit_info';

    protected $fillable = [
        'prenatal_visit_id',
        'branch_id', 
        'visit_number',
        'visit_date',
        'next_visit_date',
        'next_visit_time',
    ];

    public function prenatalVisit()
    {
        return $this->belongsTo(PrenatalVisit::class, 'prenatal_visit_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}