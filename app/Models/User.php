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
        'fullname',
        'email',
        'birth_date',
        'phone',
        'address',
        'password',
        'profile_photo',
        'is_active',
        'role_id',
        'shift',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }

        return asset('images/default-profile.jpg'); // Default image
    }


    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }


    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
