<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Services\PortfolioChatbotService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\View\View;

abstract class Controller
{
    protected function renderFrontendPage(
        string $component,
        array $props = [],
        ?Setting $setting = null,
        ?string $pageTitle = null
    ): View {
        $setting ??= Setting::latest()->first();
        $siteName = $setting?->site_name ?: 'Portfolio Website';

        return view('frontend.react', [
            'pageTitle' => $pageTitle ? $pageTitle . ' | ' . $siteName : $siteName,
            'pageData' => [
                'component' => $component,
                'props' => array_merge($this->sharedFrontendProps($setting), $props),
            ],
        ]);
    }

    protected function sharedFrontendProps(?Setting $setting): array
    {
        $errors = session('errors');

        return [
            'app' => [
                'currentRoute' => request()->route()?->getName(),
                'site' => $this->settingPayload($setting),
                'urls' => [
                    'home' => route('home'),
                    'about' => route('about'),
                    'projects' => route('projects'),
                    'services' => route('services'),
                    'blog' => route('blog'),
                    'resume' => route('resume'),
                    'contact' => route('contact'),
                    'contactStore' => route('contact.store'),
                    'chatbot' => route('chatbot.message'),
                ],
                'chatbot' => [
                    'endpoint' => route('chatbot.message'),
                    'title' => ($setting?->site_name ?: 'Portfolio') . ' Assistant',
                    'sampleQuestions' => app(PortfolioChatbotService::class)->sampleQuestions(),
                ],
                'flash' => [
                    'success' => session('success'),
                ],
                'formState' => [
                    'old' => session()->getOldInput(),
                    'errors' => $errors?->getBag('default')->toArray() ?? [],
                ],
            ],
        ];
    }

    protected function homeSectionPayload(?HomeSection $home): ?array
    {
        if (! $home) {
            return null;
        }

        return [
            'title' => $home->title,
            'subtitle' => $home->subtitle,
            'short_description' => $home->short_description,
            'hero_image_url' => $this->storageUrl($home->hero_image),
            'hero_video_url' => $this->mediaUrl($home->hero_video),
            'hire_me_link' => $home->hire_me_link,
            'cv_button_text' => $home->cv_button_text,
        ];
    }

    protected function profilePayload(?Profile $profile): ?array
    {
        if (! $profile) {
            return null;
        }

        return [
            'full_name' => $profile->full_name,
            'job_title' => $profile->job_title,
            'short_intro' => $profile->short_intro,
            'about_me' => $profile->about_me,
            'profile_image_url' => $this->storageUrl($profile->profile_image),
            'profile_video' => $profile->profile_video,
            'profile_video_url' => $this->mediaUrl($profile->profile_video),
            'experience' => $profile->experience,
            'education' => $profile->education,
            'technologies' => $profile->technologies,
            'technology_list' => $this->csvList($profile->technologies),
            'goals' => $profile->goals,
            'cv_url' => $profile->cvUrl(),
            'cv_download_name' => $profile->cvDownloadName(),
            'email' => $profile->email,
            'phone' => $profile->phone,
            'address' => $profile->address,
        ];
    }

    protected function skillPayload(Skill $skill): array
    {
        return [
            'skill_name' => $skill->skill_name,
            'percentage' => (int) $skill->percentage,
            'level' => $skill->level,
            'sort_order' => $skill->sort_order,
        ];
    }

    protected function projectPayload(Project $project): array
    {
        return [
            'title' => $project->title,
            'slug' => $project->slug,
            'image_url' => $this->storageUrl($project->image),
            'demo_video' => $project->demo_video,
            'demo_video_url' => $project->demoVideoUrl(),
            'demo_video_embed_url' => $project->demoVideoEmbedUrl(),
            'description' => $project->description,
            'technology_used' => $project->technology_used,
            'project_url' => $project->project_url,
            'github_url' => $project->github_url,
            'category' => $project->category ? $this->categoryPayload($project->category) : null,
            'detail_url' => route('projects.show', $project->slug),
        ];
    }

    protected function servicePayload(Service $service): array
    {
        return [
            'title' => $service->title,
            'icon' => $service->icon,
            'short_description' => $service->short_description,
        ];
    }

    protected function blogPayload(Blog $blog): array
    {
        return [
            'title' => $blog->title,
            'slug' => $blog->slug,
            'image_url' => $this->storageUrl($blog->image),
            'short_description' => $blog->short_description,
            'content' => $blog->content,
            'author' => $blog->author,
            'published_at' => $blog->published_at?->toIso8601String(),
            'published_label' => $blog->published_at?->format('F d, Y') ?? 'Draft',
            'category' => $blog->category ? $this->categoryPayload($blog->category) : null,
            'detail_url' => route('blog.show', $blog->slug),
        ];
    }

    protected function socialLinkPayload(SocialLink $socialLink): array
    {
        return [
            'platform' => $socialLink->platform,
            'url' => $socialLink->url,
            'icon' => $socialLink->icon,
        ];
    }

    protected function settingPayload(?Setting $setting): array
    {
        return [
            'site_name' => $setting?->site_name ?: 'Portfolio Website',
            'site_logo_url' => $this->storageUrl($setting?->site_logo),
            'favicon_url' => $this->storageUrl($setting?->favicon),
            'footer_text' => $setting?->footer_text ?: 'Portfolio website. All rights reserved.',
            'contact_email' => $setting?->contact_email,
            'contact_phone' => $setting?->contact_phone,
            'address' => $setting?->address,
            'google_map_embed' => $setting?->google_map_embed,
            'whatsapp_link' => $setting?->whatsapp_link,
            'telegram_link' => $setting?->telegram_link,
        ];
    }

    protected function categoryPayload(?Category $category): ?array
    {
        if (! $category) {
            return null;
        }

        return [
            'name' => $category->name,
            'type' => $category->type,
        ];
    }

    protected function paginatedPayload(LengthAwarePaginator $paginator, callable $transform): array
    {
        return [
            'items' => $paginator->getCollection()
                ->map(fn ($item) => $transform($item))
                ->values()
                ->all(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'links' => $paginator->linkCollection()
                    ->map(fn (array $link) => [
                        'url' => $link['url'],
                        'label' => html_entity_decode(strip_tags((string) $link['label'])),
                        'active' => (bool) $link['active'],
                    ])
                    ->values()
                    ->all(),
            ],
        ];
    }

    protected function csvList(?string $value): array
    {
        return collect(explode(',', (string) $value))
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    protected function storageUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    protected function mediaUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return $this->storageUrl($path);
    }
}
