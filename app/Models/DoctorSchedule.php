<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $table = 'doctor_schedules';

    protected $fillable = [
        'doctor_id',
        'weekday',
        'start_time',
        'end_time',
        'appointment_duration'
    ];
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
