<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts
     */
    public function index(Request $request)
    {
        $query = BlogPost::published()
            ->latest('published_at');

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('excerpt', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        // Get all posts (no pagination for template structure)
        $allPosts = $query->get();
        
        // New Posts (latest 2 posts)
        $newPosts = $allPosts->take(2);
        
        // Others Posts (remaining posts)
        $othersPosts = $allPosts->skip(2);
        
        $categories = BlogPost::published()
            ->select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category');

        return view('frontend.blog.index', compact('newPosts', 'othersPosts', 'categories'));
    }

    /**
     * Display the specified blog post
     */
    public function show($slug)
    {
        $post = BlogPost::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Get related posts (same category, excluding current post)
        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->latest('published_at')
            ->take(3)
            ->get();

        // If not enough related posts, fill with latest posts
        if ($relatedPosts->count() < 3) {
            $additionalPosts = BlogPost::published()
                ->where('id', '!=', $post->id)
                ->whereNotIn('id', $relatedPosts->pluck('id'))
                ->latest('published_at')
                ->take(3 - $relatedPosts->count())
                ->get();

            $relatedPosts = $relatedPosts->merge($additionalPosts);
        }

        return view('frontend.blog.show', compact('post', 'relatedPosts'));
    }
}
