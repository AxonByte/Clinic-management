<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionMedicine extends Model
{

    protected $fillable = [
        'prescription_id',
        'medicine_id',
        'dosage',
        'frequency',
        'days',
        'instructions',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }


}
