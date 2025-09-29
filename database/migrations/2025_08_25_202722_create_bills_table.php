<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $table = 'bills';

    protected $fillable = [
        'patient_id',
        'bill_code',
        'issue_date',
        'due_date',
        'philhealth_status',
        'philhealth_number',
        'total_amount',
    ];

    // RELATIONSHIPS

    // A Bill belongs to a Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    // A Bill has many BillItems
    public function items()
    {
        return $this->hasMany(BillItem::class, 'bill_id');
    }
}
