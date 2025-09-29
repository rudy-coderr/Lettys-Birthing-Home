<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaritalStatus extends Model
{
    protected $table = 'marital_status';

    protected $fillable = [
        'marital_status_name',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class, 'marital_status_id');
    }
}
