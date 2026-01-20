<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email',
        'is_verified',
        'verification_token',
        'subscribed_at',
        'unsubscribed_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    /**
     * Generate verification token
     */
    public static function generateVerificationToken(): string
    {
        return Str::random(64);
    }

    /**
     * Check if subscriber is active
     */
    public function isActive(): bool
    {
        return $this->is_verified && $this->unsubscribed_at === null;
    }

    /**
     * Mark as subscribed
     */
    public function markAsSubscribed(): void
    {
        $this->update([
            'is_verified' => true,
            'subscribed_at' => now(),
            'verification_token' => null,
        ]);
    }

    /**
     * Unsubscribe
     */
    public function unsubscribe(): void
    {
        $this->update([
            'unsubscribed_at' => now(),
        ]);
    }
}
