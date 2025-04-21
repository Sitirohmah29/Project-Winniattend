<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'salary',
        'alpha_deduction',
        'total_salary',
        'month',
    ];

    // Relasi ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
