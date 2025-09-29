<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientMedicationItem extends Model
{
    protected $table = 'patient_medication_items';

     public $timestamps = false;
    protected $fillable = [
        'patient_medication_id',
        'item_id',
        'quantity',
    ];

    // 🔹 Relationships
    public function medication()
    {
        return $this->belongsTo(PatientMedication::class, 'patient_medication_id');
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }
}
