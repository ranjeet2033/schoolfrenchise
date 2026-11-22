<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where('title', 'like', "%{$s}%")->orWhere('category', 'like', "%{$s}%");
        }

        $posts = $query->paginate(15)->withQueryString();

        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'category'       => 'nullable|string|max:100',
            'summary'        => 'nullable|string',
            'content'        => 'required|string',
            'featured_image' => 'nullable|image|max:3072',
            'author_name'    => 'nullable|string|max:100',
            'is_published'   => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('blog', 'public');
        }

        $post = BlogPost::create([
            'title'          => $validated['title'],
            'slug'           => Str::slug($validated['title']) . '-' . rand(1000, 9999),
            'category'       => $validated['category'] ?? 'General',
            'summary'        => $validated['summary'] ?? Str::limit(strip_tags($validated['content']), 160),
            'content'        => $validated['content'],
            'featured_image' => $imagePath,
            'author_name'    => !empty($validated['author_name']) ? $validated['author_name'] : 'Tech Era Team',
            'is_published'   => $request->boolean('is_published', true),
            'published_at'   => now(),
        ]);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post published successfully!');
    }

    public function edit($post)
    {
        $post = $post instanceof BlogPost ? $post : BlogPost::findOrFail($post);
        return view('admin.blog.edit', compact('post'));
    }

    public function update(Request $request, $post)
    {
        $post = $post instanceof BlogPost ? $post : BlogPost::findOrFail($post);

        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'category'       => 'nullable|string|max:100',
            'summary'        => 'nullable|string',
            'content'        => 'required|string',
            'featured_image' => 'nullable|image|max:3072',
            'author_name'    => 'nullable|string|max:100',
            'is_published'   => 'nullable|boolean',
        ]);

        if ($request->hasFile('featured_image')) {
            $post->featured_image = $request->file('featured_image')->store('blog', 'public');
        }

        $post->update([
            'title'        => $validated['title'],
            'category'     => $validated['category'] ?? $post->category,
            'summary'      => $validated['summary'] ?? Str::limit(strip_tags($validated['content']), 160),
            'content'      => $validated['content'],
            'author_name'  => !empty($validated['author_name']) ? $validated['author_name'] : ($post->author_name ?? 'Tech Era Team'),
            'is_published' => $request->boolean('is_published', true),
        ]);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully!');
    }

    public function destroy($post)
    {
        $post = $post instanceof BlogPost ? $post : BlogPost::findOrFail($post);
        $post->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted successfully.');
    }
}
