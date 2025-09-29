<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentStatus extends Model
{
    use HasFactory;

    // ✅ Correct table name
    protected $table = 'appointment_status';

    protected $fillable = [
        'status_name',
    ];

    public $timestamps = false;

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'status_id');
    }
}
