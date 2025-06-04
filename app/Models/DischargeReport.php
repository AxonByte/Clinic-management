<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DischargeReport extends Model
{
    protected $fillable = [
        'patient_admitted_id',
        'doctor_id',
        'discharge_time',
        'final_diagnosis',
        'anatomopatologic_diagnosis',
        'checkout_diagnosis',
        'checkout_state',
        'initial_diagnosis',
        'medicine_description',
        'instruction'
    ];
}
