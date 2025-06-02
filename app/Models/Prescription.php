<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'date',
        'doctor_id',
        'patient_id',
        'note',
        'history',
        'advice',
    ];

    public function prescriptionMedicines()
    {
        return $this->hasMany(PrescriptionMedicine::class);
    }
    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'prescription_medicines')
                    ->withPivot(['dosage', 'frequency', 'days', 'instructions'])
                    ->withTimestamps();
    }


    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class,'patient_id');
    }
}
