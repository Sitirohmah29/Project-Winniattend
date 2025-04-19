<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['user_id', 'description', 'activity_type', 'ip_address', 'user_agent', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
