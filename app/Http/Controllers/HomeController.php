<?php

namespace App\Http\Controllers;

use App\Models\HomeSection;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Blog;
use App\Models\SocialLink;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $home = HomeSection::where('status', 1)->latest()->first();
        $profile = Profile::latest()->first();
        $skills = Skill::where('status', 1)->orderBy('sort_order')->get();
        $projects = Project::with('category')->where('status', 1)->where('is_featured', 1)->latest()->take(6)->get();
        $services = Service::where('status', 1)->latest()->take(6)->get();
        $blogs = Blog::with('category')->where('status', 1)->latest()->take(3)->get();
        $socialLinks = SocialLink::where('status', 1)->get();
        $setting = Setting::latest()->first();

        return $this->renderFrontendPage('HomePage', [
            'home' => $this->homeSectionPayload($home),
            'profile' => $this->profilePayload($profile),
            'skills' => $skills->map(fn ($skill) => $this->skillPayload($skill))->values()->all(),
            'projects' => $projects->map(fn ($project) => $this->projectPayload($project))->values()->all(),
            'services' => $services->map(fn ($service) => $this->servicePayload($service))->values()->all(),
            'blogs' => $blogs->map(fn ($blog) => $this->blogPayload($blog))->values()->all(),
            'socialLinks' => $socialLinks->map(fn ($socialLink) => $this->socialLinkPayload($socialLink))->values()->all(),
        ], $setting, 'Home');
    }
}
