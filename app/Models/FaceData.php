<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceData extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'face_encoding', 'is_primary'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
