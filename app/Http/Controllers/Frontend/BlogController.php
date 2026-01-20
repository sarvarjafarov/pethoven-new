<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        // We render the page using the Brancy `blog.html` structure.
        // The view will map the first N posts into the template slots and fall back to Brancy demo content.
        $posts = $query->get();

        return view('frontend.blog.index', compact('posts'));
    }

    /**
     * Display the specified blog post
     */
    public function show($slug)
    {
        // Demo fallback: allow template-like inner pages even when DB has no posts.
        if (Str::startsWith($slug, 'demo-')) {
            $cdn = 'https://template.hasthemes.com/brancy/brancy/assets/images';

            $demoPosts = collect([
                [
                    'slug' => 'demo-1',
                    'title' => 'Lorem ipsum dolor sit amet consectetur adipiscing.',
                    'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In vel arcu aliquet sem risus nisl. Neque, scelerisque in erat lacus ridiculus habitant porttitor. Malesuada pulvinar sollicitudin enim, quis sapien tellus est.',
                    'featured_image' => "{$cdn}/blog/blog-detail1.webp",
                    'category' => 'beauty',
                    'author' => 'Tomas De Momen',
                    'formatted_date' => 'February 13, 2022',
                ],
                [
                    'slug' => 'demo-2',
                    'title' => 'Benefit of Hot Ston Spa for your health & life.',
                    'excerpt' => 'Lorem ipsum dolor sit amet, conseur adipiscing elit ut aliqua, purus sit amet luctus venenatis.',
                    'content' => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus, repellendus. Nam voluptate illo ut quia non sapiente provident alias quos laborum incidunt, earum accusamus, natus.',
                    'featured_image' => "{$cdn}/blog/blog-detail2.webp",
                    'category' => 'beauty',
                    'author' => 'Tomas De Momen',
                    'formatted_date' => 'February 13, 2022',
                ],
                [
                    'slug' => 'demo-3',
                    'title' => 'Facial Scrub is natural treatment for face.',
                    'excerpt' => 'Lorem ipsum dolor sit amet, conseur adipiscing elit ut aliqua, purus.',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis. In vel arcu aliquet sem risus nisl. Neque, scelerisque in erat lacus ridiculus habitant porttitor.',
                    'featured_image' => "{$cdn}/blog/blog-detail3.webp",
                    'category' => 'beauty',
                    'author' => 'Tomas De Momen',
                    'formatted_date' => 'February 13, 2022',
                ],
            ])->keyBy('slug');

            $post = (object) ($demoPosts[$slug] ?? $demoPosts['demo-1']);
            $relatedPosts = $demoPosts->except($post->slug)->values()->map(fn ($p) => (object) $p);

            return view('frontend.blog.show', compact('post', 'relatedPosts'));
        }

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
