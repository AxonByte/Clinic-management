<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressNote extends Model
{
    protected $fillable = ['admission_id', 'nurse_id', 'description','date','time'];

    public function admission()
    {
        return $this->belongsTo(PatientAdmitted::class);
    }

    public function nurse()
    {
        return $this->belongsTo(User::class,'nurse_id');
    }
}
