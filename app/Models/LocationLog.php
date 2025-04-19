<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationLog extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['user_id', 'location_name', 'latitude', 'longitude', 'recorded_at', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
