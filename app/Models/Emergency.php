<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Notifications\EmergencyAlertNotification;

class Emergency extends Model
{
    use HasFactory;

    protected $table = 'emergency';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'branch_id',
        'created_at' 
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // 🔔 Kapag may bagong record, auto fire notification
    protected static function booted()
    {
        static::created(function ($emergency) {
            // Notify admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new EmergencyAlertNotification($emergency));
            }

            // Notify staff in same branch
            $staff = User::where('role', 'staff')
                        ->whereHas('staff', function($q) use ($emergency) {
                            $q->where('branch_id', $emergency->branch_id);
                        })->get();

            foreach ($staff as $s) {
                $s->notify(new EmergencyAlertNotification($emergency));
            }
        });
    }
}
