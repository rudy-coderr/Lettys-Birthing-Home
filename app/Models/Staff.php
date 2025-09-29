<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staff_id',
        'first_name',
        'last_name',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'status',
        'avatar_path',
        'branch_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($staff) {
            // Generate staff_id only if not manually set
            if (empty($staff->staff_id)) {
                do {
                    $randomId = 'ST' . rand(10000, 99999);
                } while (self::where('staff_id', $randomId)->exists());

                $staff->staff_id = $randomId;
            }
        });
    }

    public function workDays()
    {
        return $this->hasMany(StaffWorkDays::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class, 'staff_id');
    }

    public function prenatalVisits()
    {
        return $this->hasMany(PrenatalVisit::class, 'staff_id', 'id');
    }

}
