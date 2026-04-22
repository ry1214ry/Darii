<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Setting;

class FrontBlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('category')->where('status', 1)->latest()->paginate(6);
        $setting = Setting::latest()->first();

        return $this->renderFrontendPage('BlogPage', [
            'blogs' => $this->paginatedPayload($blogs, fn ($blog) => $this->blogPayload($blog)),
        ], $setting, 'Blog');
    }

    public function show($slug)
    {
        $blog = Blog::with('category')->where('slug', $slug)->where('status', 1)->firstOrFail();
        $setting = Setting::latest()->first();

        return $this->renderFrontendPage('BlogDetailPage', [
            'blog' => $this->blogPayload($blog),
        ], $setting, $blog->title);
    }
}
