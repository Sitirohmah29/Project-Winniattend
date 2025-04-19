<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'user_roles_id', 'punch_in', 'punch_out',
        'punch_in_location', 'punch_out_location',
        'punch_in_photo', 'punch_out_photo',
        'status', 'is_late', 'late_duration', 'shift', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'user_roles_id');
    }
}
