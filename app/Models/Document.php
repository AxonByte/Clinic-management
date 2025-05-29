<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = ['patient_id', 'title', 'file_path'];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
