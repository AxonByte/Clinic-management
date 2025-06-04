<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientAdmitted extends Model
{
    protected $fillable = [
        'admission_time', 'category', 'bed_category_id', 'bed_id', 'patient_id',
        'blood_group', 'doctor_id', 'accepting_doctor_id', 'diagnosis',
        'diagnosis_at_hospitalization', 'other_illnesses', 'history', 'reactions', 'transferred_from'
    ];

    public function patient() { return $this->belongsTo(User::class,'patient_id'); }
    public function doctor() { return $this->belongsTo(User::class); }
    public function acceptingDoctor() { return $this->belongsTo(User::class, 'accepting_doctor_id'); }
    public function bedCategory() { return $this->belongsTo(BedCategory::class); }
    public function bed() { return $this->belongsTo(Bed::class,'bed_id'); }
    
    public function progressNotes()
    {
        return $this->hasMany(ProgressNote::class);
    }


}
