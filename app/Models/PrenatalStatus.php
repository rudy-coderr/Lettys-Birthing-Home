<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrenatalStatus extends Model
{
    protected $table = 'prenatal_status';

    protected $fillable = [
        'status_name',
    ];

    public $timestamps = false;

    // 🔹 One status can belong to many patients
     public function visits()
    {
        return $this->hasMany(PrenatalVisit::class, 'prenatal_status_id');
    }
}
