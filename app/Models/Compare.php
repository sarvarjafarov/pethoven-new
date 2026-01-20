<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lunar\Models\Product;

class Compare extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
    ];

    /**
     * Get the user that owns the compare item
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope for user's compare list
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for session's compare list
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Check if product is in compare list for user or session
     */
    public static function isInCompare($productId, $userId = null, $sessionId = null): bool
    {
        $query = static::where('product_id', $productId);

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        } else {
            return false;
        }

        return $query->exists();
    }
}
