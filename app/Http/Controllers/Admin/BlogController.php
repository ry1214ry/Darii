<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('category')->latest()->get();
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $categories = Category::where('type', 'blog')->where('status', 1)->get();
        return view('admin.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'        => 'nullable|exists:categories,id',
            'title'              => 'required|string|max:255',
            'image'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'short_description'  => 'nullable|string',
            'content'            => 'nullable|string',
            'author'             => 'nullable|string|max:255',
            'status'             => 'required|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
        }

        Blog::create([
            'category_id'       => $request->category_id,
            'title'             => $request->title,
            'slug'              => Str::slug($request->title . '-' . time()),
            'image'             => $imagePath,
            'short_description' => $request->short_description,
            'content'           => $request->content,
            'author'            => $request->author,
            'status'            => $request->status,
            'published_at'      => now(),
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully.');
    }

    public function show(Blog $blog)
    {
        return view('admin.blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        $categories = Category::where('type', 'blog')->where('status', 1)->get();
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'category_id'        => 'nullable|exists:categories,id',
            'title'              => 'required|string|max:255',
            'image'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'short_description'  => 'nullable|string',
            'content'            => 'nullable|string',
            'author'             => 'nullable|string|max:255',
            'status'             => 'required|boolean',
        ]);

        $imagePath = $blog->image;

        if ($request->hasFile('image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }

            $imagePath = $request->file('image')->store('blogs', 'public');
        }

        $blog->update([
            'category_id'       => $request->category_id,
            'title'             => $request->title,
            'slug'              => Str::slug($request->title . '-' . $blog->id),
            'image'             => $imagePath,
            'short_description' => $request->short_description,
            'content'           => $request->content,
            'author'            => $request->author,
            'status'            => $request->status,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted successfully.');
    }
}