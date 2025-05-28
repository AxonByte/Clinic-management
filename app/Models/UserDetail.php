<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $table = 'user_details';

    protected $fillable = [
       'dob', 'nationalid','gender','blood_group','height','weight','allergies','medical_history','emergency_contact_person',
       'emergency_contact','sms'
    ];
    public function details()
    {
        return $this->belongsTo(User::class);
    }
}
