<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lunar\Models\Product;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
    ];

    /**
     * Get the user that owns the wishlist item
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
     * Scope for user's wishlist
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for session's wishlist
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Check if product is in wishlist for user or session
     */
    public static function isInWishlist($productId, $userId = null, $sessionId = null): bool
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
