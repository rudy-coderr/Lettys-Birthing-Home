<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branch';

    protected $fillable = [
        'branch_name',
    ];

    public $timestamps = false;

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
    public function emergencies()
    {
        return $this->hasMany(Emergency::class);
    }

}
