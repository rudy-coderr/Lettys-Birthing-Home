<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointment';

    public $timestamps = true;

    protected $fillable = [
        'client_id',
        'branch_id',
        'status_id',
        'appointment_date',
        'appointment_time',
        'appointment_reason',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function appointment_status()
    {
        return $this->belongsTo(AppointmentStatus::class, 'status_id');
    }
}
