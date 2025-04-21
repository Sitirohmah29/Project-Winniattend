<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'working_days',
        'total_overtime',
        'total_work_duration',
        'month',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
