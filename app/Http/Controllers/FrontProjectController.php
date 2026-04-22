<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Category;
use App\Models\Setting;

class FrontProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('category')->where('status', 1)->latest()->paginate(9);
        $categories = Category::where('type', 'project')->where('status', 1)->get();
        $setting = Setting::latest()->first();

        return $this->renderFrontendPage('ProjectsPage', [
            'projects' => $this->paginatedPayload($projects, fn ($project) => $this->projectPayload($project)),
            'categories' => $categories->map(fn ($category) => $this->categoryPayload($category))->values()->all(),
        ], $setting, 'Projects');
    }

    public function show($slug)
    {
        $project = Project::with('category')->where('slug', $slug)->where('status', 1)->firstOrFail();
        $setting = Setting::latest()->first();

        return $this->renderFrontendPage('ProjectDetailPage', [
            'project' => $this->projectPayload($project),
        ], $setting, $project->title);
    }
}
