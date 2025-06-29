<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceVerification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'status',
        'blink_count',
        'verified_at',
        'face_data',
        'verification_method',
        'is_active',
        'failure_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
        'is_active' => 'boolean',
        'face_data' => 'array', // Cast JSON string to array
    ];

    /**
     * Status constants for better code readability
     */
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_FAILED = 'failed';
    public const STATUS_PENDING = 'pending';

    /**
     * Verification methods
     */
    public const METHOD_BLINK_DETECTION = 'blink_detection';

    /**
     * Failure reasons
     */
    public const FAILURE_INSUFFICIENT_BLINKS = 'insufficient_blinks';
    public const FAILURE_LIVENESS_FAILED = 'liveness_failed';

    /**
     * Relationship with User model
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for active verifications
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for verified status
     */
    public function scopeVerified($query)
    {
        return $query->where('status', self::STATUS_VERIFIED);
    }

    /**
     * Scope for failed status
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Check if verification is successful
     */
    public function isVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED && $this->is_active;
    }

    /**
     * Mark verification as failed
     */
    public function markAsFailed(string $reason): self
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'is_active' => false,
            'failure_reason' => $reason,
        ]);

        return $this;
    }

    /**
     * Get the latest active verification for a user
     */
    public static function getLatestForUser(int $userId): ?self
    {
        return self::where('user_id', $userId)
            ->active()
            ->latest()
            ->first();
    }
}