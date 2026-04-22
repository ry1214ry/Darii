<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('category')->latest()->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $categories = Category::where('type', 'project')->where('status', 1)->get();
        return view('admin.projects.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProject($request);
        $image = $this->syncImage($request);
        $demoVideo = $this->syncDemoVideo($request);

        Project::create([
            'category_id'     => $validated['category_id'] ?? null,
            'title'           => $validated['title'],
            'slug'            => Str::slug($validated['title'] . '-' . time()),
            'image'           => $image,
            'demo_video'      => $demoVideo,
            'description'     => $validated['description'] ?? null,
            'technology_used' => $validated['technology_used'] ?? null,
            'project_url'     => $validated['project_url'] ?? null,
            'github_url'      => $validated['github_url'] ?? null,
            'status'          => $validated['status'],
            'is_featured'     => $validated['is_featured'],
        ]);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $categories = Category::where('type', 'project')->where('status', 1)->get();
        return view('admin.projects.edit', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $this->validateProject($request);
        $image = $this->syncImage($request, $project->image);
        $demoVideo = $this->syncDemoVideo($request, $project->demo_video);

        $project->update([
            'category_id'     => $validated['category_id'] ?? null,
            'title'           => $validated['title'],
            'slug'            => Str::slug($validated['title'] . '-' . $project->id),
            'image'           => $image,
            'demo_video'      => $demoVideo,
            'description'     => $validated['description'] ?? null,
            'technology_used' => $validated['technology_used'] ?? null,
            'project_url'     => $validated['project_url'] ?? null,
            'github_url'      => $validated['github_url'] ?? null,
            'status'          => $validated['status'],
            'is_featured'     => $validated['is_featured'],
        ]);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $this->deleteStoredPublicFile($project->image);
        $this->deleteStoredPublicFile($project->demo_video);

        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    private function validateProject(Request $request): array
    {
        return $request->validate([
            'category_id'      => 'nullable|exists:categories,id',
            'title'            => 'required|string|max:255',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp,avif,gif|max:10240',
            'remove_image'     => 'nullable|boolean',
            'demo_video_file'  => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg,video/quicktime|max:51200',
            'demo_video_url'   => 'nullable|url|max:2048',
            'remove_demo_video'=> 'nullable|boolean',
            'description'      => 'nullable|string',
            'technology_used'  => 'nullable|string',
            'project_url'      => 'nullable|url',
            'github_url'       => 'nullable|url',
            'status'           => 'required|boolean',
            'is_featured'      => 'required|boolean',
        ]);
    }

    private function syncImage(Request $request, ?string $currentImage = null): ?string
    {
        $image = $currentImage;

        if ($request->boolean('remove_image')) {
            $this->deleteStoredPublicFile($image);
            $image = null;
        }

        if ($request->hasFile('image')) {
            $this->deleteStoredPublicFile($image);

            return $request->file('image')->store('projects', 'public');
        }

        return $image;
    }

    private function syncDemoVideo(Request $request, ?string $currentDemoVideo = null): ?string
    {
        $demoVideo = $currentDemoVideo;

        if ($request->boolean('remove_demo_video')) {
            $this->deleteStoredPublicFile($demoVideo);
            $demoVideo = null;
        }

        if ($request->hasFile('demo_video_file')) {
            $this->deleteStoredPublicFile($demoVideo);

            return $request->file('demo_video_file')->store('project-demos', 'public');
        }

        $demoVideoUrl = trim((string) $request->input('demo_video_url'));

        if ($demoVideoUrl !== '') {
            if ($demoVideoUrl !== $demoVideo) {
                $this->deleteStoredPublicFile($demoVideo);
            }

            return $demoVideoUrl;
        }

        return $demoVideo;
    }

    private function deleteStoredPublicFile(?string $path): void
    {
        if (blank($path) || Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
