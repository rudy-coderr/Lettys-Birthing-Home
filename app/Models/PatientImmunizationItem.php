<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientImmunizationItem extends Model
{
    protected $table = 'patient_immunization_items';
    public $timestamps = false;

    protected $fillable = [
        'patient_immunization_id',
        'item_id',
        'quantity',
    ];

    // 🔹 Relationships
    public function immunization()
    {
        return $this->belongsTo(PatientImmunization::class, 'patient_immunization_id');
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }
}
