<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    
    protected $table ='diagnoses';
    protected $fillable = [
        'disease_name',
        'icd_code',
        'description',
        'has_outbreak_potential',
        'max_weekly_patients',
    ];
}
