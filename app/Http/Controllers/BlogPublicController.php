<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogPublicController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::where('is_published', true)->orderBy('published_at', 'desc');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('summary', 'like', "%{$s}%")
                  ->orWhere('content', 'like', "%{$s}%");
            });
        }

        $posts = $query->paginate(9)->withQueryString();
        $recentPosts = BlogPost::where('is_published', true)->orderBy('published_at', 'desc')->take(5)->get();

        return view('pages.blog.index', compact('posts', 'recentPosts'));
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $recentPosts = BlogPost::where('is_published', true)->where('id', '!=', $post->id)->orderBy('published_at', 'desc')->take(4)->get();

        return view('pages.blog.show', compact('post', 'recentPosts'));
    }
}
