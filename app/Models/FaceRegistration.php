<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FaceRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image_path',
        'image_name',
        'metadata',
        'captured_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'captured_at' => 'datetime',
    ];

    /**
     * Get the user that owns the face verification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full URL of the image.
     */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }


    /**
     * Scope to get recent verifications.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}