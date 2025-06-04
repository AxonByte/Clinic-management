<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
     
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'photo',
        'sign',
        'description',
        'department_id',
        'address',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // app/Models/User.php

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id');
    }
    public function visits()
    {
        return $this->hasMany(DoctorVisit::class, 'doctor_id');
    }

    public function schedule()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id');
    }
    public function holiday()
    {
        return $this->hasMany(DoctorHoliday::class, 'doctor_id');
    }

    public function detail()
    {
       return $this->hasOne(UserDetail::class, 'user_id');
    }

    public function progressNotes()
    {
        return $this->hasMany(ProgressNote::class.'nurse_id');
    }



}
