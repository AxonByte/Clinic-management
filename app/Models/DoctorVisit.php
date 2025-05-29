<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorVisit extends Model
{
    protected $table = 'doctor_visits';
    protected $fillable = ['doctor_id', 'visit_description','visit_charges','status'];
    
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }


}
