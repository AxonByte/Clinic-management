<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'visit_type',
        'time_slot',
        'visit_charges',
        'discount',
        'total_amount',
        'pay_now',
        'status',
        'notes'
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

}
