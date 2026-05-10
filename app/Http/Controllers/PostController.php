<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     */
    public function index(): View
    {
        $posts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->with(['author', 'taxonomies'])
            ->latest('published_at')
            ->paginate(12);

        return view('blog.index', compact('posts'));
    }

    /**
     * Display the specified post.
     */
    public function show(string $slug): View
    {
        $post = Post::where('status', 'published')
            ->where('slug', $slug)
            ->with(['author', 'taxonomies'])
            ->firstOrFail();

        return view('blog.show', compact('post'));
    }
}
