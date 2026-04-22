<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Blog;
use App\Models\ContactMessage;
use App\Models\Service;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalProjects'     => Project::count(),
            'totalSkills'       => Skill::count(),
            'totalBlogs'        => Blog::count(),
            'totalMessages'     => ContactMessage::count(),
            'totalServices'     => Service::count(),
            'totalTestimonials' => Testimonial::count(),
        ]);
    }
}