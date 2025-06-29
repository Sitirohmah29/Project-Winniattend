<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'check_in', 'check_out',
        'check_in_location', 'check_out_location',
        'status', 'shift', 'permission'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
