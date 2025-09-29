<?php
namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'role',
        'two_factor_code',
        'two_factor_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['full_name'];

    // ✅ For Laravel 10+
    /*
    protected function casts(): array
    {
        return [
            'email_verified_at'     => 'datetime',
            'two_factor_expires_at' => 'datetime',
            'password'              => 'hashed',
        ];
    }
    */

    // ✅ Use this version for Laravel 9 and below:
    protected $casts = [
        'email_verified_at'     => 'datetime',
        'two_factor_expires_at' => 'datetime',
        'password'              => 'hashed',
        'is_active'             => 'boolean',
    ];

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function staff()
    {
        return $this->hasOne(\App\Models\Staff::class);
    }

    public function generateTwoFactorCode()
    {
        $this->two_factor_code       = random_int(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(5);
        $this->save();
    }

    public function resetTwoFactorCode()
    {
        $this->two_factor_code       = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    public function getFullNameAttribute()
    {
        if ($this->role === 'admin' && $this->admin) {
            return $this->admin->first_name . ' ' . $this->admin->last_name;
        } elseif ($this->role === 'staff' && $this->staff) {
            return $this->staff->first_name . ' ' . $this->staff->last_name;
        }
        return $this->email;
    }

}
