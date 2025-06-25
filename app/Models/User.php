<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'address', 'service_ids',
        'department_id',
        'description',
        'photo',
        'sign',
        'role',
        'hospital_id',  
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


        public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function hasPermission(string $permissionName): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permissionName) {
            $query->where('name', $permissionName);
        })->exists();
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }public function role()
{
    return $this->belongsTo(Role::class, 'role_id');  // adjust foreign key if different
}


}