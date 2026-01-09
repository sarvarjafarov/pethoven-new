<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category',
        'author',
        'published',
        'published_at',
    ];

    protected $casts = [
        'published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Scope to get only published posts
     */
    public function scopePublished($query)
    {
        return $query->where('published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the formatted published date
     */
    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->published_at?->format('F d, Y') ?? $this->created_at->format('F d, Y')
        );
    }
}
