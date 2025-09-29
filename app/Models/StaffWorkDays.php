<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffWorkDays extends Model
{
    use HasFactory;
    protected $table = 'staff_work_days';
    public $timestamps = false;

    protected $fillable = [
        'staff_id',
        'day',
        'shift',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
