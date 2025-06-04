<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientService extends Model
{
    protected $fillable = ['name','code','alphacode','price','is_active'];
}
