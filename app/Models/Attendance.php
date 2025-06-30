<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $table = 'attendance'; // Ganti sesuai nama tabel di database, misal 'attendance' atau 'attedance'
    protected $fillable = [
<<<<<<< HEAD
        'user_id', 'check_in', 'check_out',
        'check_in_location', 'check_out_location',
        'status', 'shift', 'permission','Attendance'
=======
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
>>>>>>> e36c41207b80397dec25bcd16c10b74fe6995a0d
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
