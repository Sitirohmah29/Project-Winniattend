<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    // Attendance.php
    const STATUS_PRESENT = 0;
    const STATUS_PERMISSION = 1;
    const STATUS_SICK = 2;

    use HasFactory;
    // protected $table = 'attendance'; 
    protected $fillable = [

        'user_id',
        'date',
        'check_in',
        'check_out',
        'check_in_location',
        'check_out_location',
        'latitude',
        'longitude',
        'status',
        'permission'

    ];

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            0 => 'present',
            1 => 'permission',
            2 => 'sick',
            default => 'unknown',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
