<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remarks extends Model
{
    protected $table = 'remarks';

    protected $fillable = [
        'notes',
    ];

    // Relation to prenatal visits
    public function prenatalVisits()
    {
        return $this->hasMany(PrenatalVisit::class, 'remarks_id');
    }

    // ✅ Relation to intrapartum records
    public function intrapartumRecords()
    {
        return $this->hasMany(Intrapartum::class, 'remarks_id');
    }
    public function postpartumRecords()
    {
        return $this->hasMany(Postpartum::class, 'remarks_id');
    }
}
