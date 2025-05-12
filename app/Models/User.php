<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    
    protected $fillable = [
        'name', 'email', 'phone', 'password', 'profile_photo', 'is_active',
    ];

   
    protected $hidden = [
        'password',
        'remember_token',
    ];

   
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function faceData()
    {
        return $this->hasMany(FaceData::class);
    }

    public function payrollreports()
    {
        return $this->hasMany(PayrollReport::class);
    }

    public function attendancereports()
    {
        return $this->hasMany(AttendanceReport::class);
    }
}
