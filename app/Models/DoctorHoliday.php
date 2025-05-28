<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorHoliday extends Model
{
    protected $table = 'doctor_holidays';

    protected $fillable =[
        'doctor_id',
        'date'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
