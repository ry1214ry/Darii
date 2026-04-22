<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\SocialLink;
use Illuminate\Support\Str;

class PortfolioChatbotService
{
    public function respond(string $message): array
    {
        $message = trim($message);
        $context = $this->buildContext();
        $normalized = $this->normalize($message);

        $response = $this->matchSpecificContent($normalized, $context)
            ?? $this->matchIntent($normalized, $context)
            ?? $this->fallbackResponse($context);

        return $response + [
            'question' => $message,
            'suggestions' => $response['suggestions'] ?? $this->defaultSuggestions(),
        ];
    }

    public function sampleQuestions(): array
    {
        return [
            'Who is Roeun Dary?',
            'What services do you offer?',
            'What skills are highlighted?',
            'What technologies do you use?',
            'Show me featured projects.',
            'What is the latest project?',
            'Can you tell me about a project?',
            'What blog posts are available?',
            'Can I download the resume?',
            'What experience is listed?',
            'What education is listed?',
            'What are the career goals?',
            'Where are you based?',
            'What is the contact email?',
            'What phone number should I use?',
            'Which social links are available?',
            'Are you available for hire?',
            'How can I send a message?',
            'Give me a short introduction.',
            'Which page should I visit next?',
        ];
    }

    protected function buildContext(): array
    {
        return [
            'profile' => Profile::latest('id')->first(),
            'setting' => Setting::latest('id')->first(),
            'skills' => Skill::where('status', 1)->orderBy('sort_order')->orderByDesc('id')->get(),
            'services' => Service::where('status', 1)->latest('id')->get(),
            'projects' => Project::where('status', 1)->latest('id')->get(),
            'blogs' => Blog::where('status', 1)->orderByDesc('published_at')->orderByDesc('id')->get(),
            'socialLinks' => SocialLink::where('status', 1)->get(),
        ];
    }

    protected function matchSpecificContent(string $normalized, array $context): ?array
    {
        foreach ($context['projects'] as $project) {
            $title = $this->normalize($project->title ?? '');
            $slug = $this->normalize(str_replace('-', ' ', $project->slug ?? ''));

            if (($title !== '' && $this->containsPattern($normalized, $title)) || ($slug !== '' && $this->containsPattern($normalized, $slug))) {
                return [
                    'topic' => 'project-detail',
                    'answer' => $this->projectAnswer($project),
                    'links' => array_filter([
                        ['label' => 'Project details', 'url' => route('projects.show', $project->slug)],
                        $project->project_url ? ['label' => 'Live project', 'url' => $project->project_url] : null,
                        $project->github_url ? ['label' => 'Source code', 'url' => $project->github_url] : null,
                    ]),
                    'suggestions' => [
                        'Show me featured projects.',
                        'What technologies do you use?',
                        'How can I contact you?',
                    ],
                ];
            }
        }

        foreach ($context['services'] as $service) {
            $title = $this->normalize($service->title ?? '');

            if ($title !== '' && $this->containsPattern($normalized, $title)) {
                return [
                    'topic' => 'service-detail',
                    'answer' => $this->serviceAnswer($service),
                    'links' => [
                        ['label' => 'View services', 'url' => route('services')],
                        ['label' => 'Contact page', 'url' => route('contact')],
                    ],
                    'suggestions' => [
                        'What services do you offer?',
                        'Are you available for hire?',
                        'How can I send a message?',
                    ],
                ];
            }
        }

        foreach ($context['blogs'] as $blog) {
            $title = $this->normalize($blog->title ?? '');
            $slug = $this->normalize(str_replace('-', ' ', $blog->slug ?? ''));

            if (($title !== '' && $this->containsPattern($normalized, $title)) || ($slug !== '' && $this->containsPattern($normalized, $slug))) {
                return [
                    'topic' => 'blog-detail',
                    'answer' => $this->blogAnswer($blog),
                    'links' => [
                        ['label' => 'Read article', 'url' => route('blog.show', $blog->slug)],
                        ['label' => 'Visit blog', 'url' => route('blog')],
                    ],
                    'suggestions' => [
                        'What blog posts are available?',
                        'Show me featured projects.',
                        'Can I download the resume?',
                    ],
                ];
            }
        }

        return null;
    }

    protected function matchIntent(string $normalized, array $context): ?array
    {
        $profile = $context['profile'];
        $setting = $context['setting'];
        $skills = $context['skills'];
        $services = $context['services'];
        $projects = $context['projects'];
        $blogs = $context['blogs'];
        $socialLinks = $context['socialLinks'];
        $techStack = $this->technologyList($profile?->technologies);

        $intents = [
            [
                'topic' => 'greeting',
                'patterns' => ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening'],
                'answer' => 'Hello. I can answer questions about the portfolio, including skills, services, projects, blog posts, resume, and contact details.',
                'links' => [
                    ['label' => 'Home page', 'url' => route('home')],
                    ['label' => 'Contact page', 'url' => route('contact')],
                ],
            ],
            [
                'topic' => 'name',
                'patterns' => ['who are you', 'your name', 'who is roeun dary', 'introduce yourself'],
                'answer' => $profile?->full_name
                    ? $profile->full_name . ' is presented here as ' . ($profile->job_title ?: 'a Laravel developer') . '.'
                    : 'This portfolio presents Roeun Dary and the work showcased across the site.',
                'links' => [
                    ['label' => 'About page', 'url' => route('about')],
                    ['label' => 'Resume page', 'url' => route('resume')],
                ],
            ],
            [
                'topic' => 'role',
                'patterns' => ['job title', 'what do you do', 'profession', 'role', 'developer'],
                'answer' => $profile?->job_title
                    ? 'Current role: ' . $profile->job_title . '.'
                    : 'The portfolio is centered on Laravel development and modern web application work.',
                'links' => [
                    ['label' => 'About page', 'url' => route('about')],
                    ['label' => 'Services page', 'url' => route('services')],
                ],
            ],
            [
                'topic' => 'short-intro',
                'patterns' => ['short introduction', 'short intro', 'summary', 'about you', 'tell me about roeun dary'],
                'answer' => $profile?->short_intro
                    ? Str::limit(trim(strip_tags($profile->short_intro)), 220)
                    : 'This portfolio highlights Laravel development, frontend interfaces, and maintainable web solutions.',
                'links' => [
                    ['label' => 'About page', 'url' => route('about')],
                ],
            ],
            [
                'topic' => 'skills',
                'patterns' => ['skills', 'skill set', 'strengths', 'strongest skill'],
                'answer' => $skills->isNotEmpty()
                    ? 'Highlighted skills include ' . $this->formatSkillList($skills) . '.'
                    : 'Skills have not been added yet, but the site is focused on Laravel-based web development.',
                'links' => [
                    ['label' => 'About page', 'url' => route('about')],
                    ['label' => 'Resume page', 'url' => route('resume')],
                ],
            ],
            [
                'topic' => 'technology',
                'patterns' => ['technology', 'technologies', 'tech stack', 'stack', 'tools', 'framework'],
                'answer' => !empty($techStack)
                    ? 'The current tech stack includes ' . $this->joinItems($techStack, 8) . '.'
                    : 'The portfolio emphasizes Laravel, frontend interfaces, and database-backed applications.',
                'links' => [
                    ['label' => 'Home page', 'url' => route('home')],
                    ['label' => 'Projects page', 'url' => route('projects')],
                ],
            ],
            [
                'topic' => 'services',
                'patterns' => ['services', 'service', 'offer', 'provide', 'help with', 'build for me'],
                'answer' => $services->isNotEmpty()
                    ? 'Available services include ' . $this->joinItems($services->pluck('title')->all(), 6) . '.'
                    : 'Service details have not been added yet, but the portfolio is geared toward web development support.',
                'links' => [
                    ['label' => 'Services page', 'url' => route('services')],
                    ['label' => 'Contact page', 'url' => route('contact')],
                ],
            ],
            [
                'topic' => 'latest-project',
                'patterns' => ['latest project', 'recent project', 'new project', 'most recent project'],
                'answer' => $projects->isNotEmpty()
                    ? $this->projectAnswer($projects->first())
                    : 'There are no published projects yet.',
                'links' => $projects->isNotEmpty()
                    ? [
                        ['label' => 'Project details', 'url' => route('projects.show', $projects->first()->slug)],
                        ['label' => 'All projects', 'url' => route('projects')],
                    ]
                    : [
                        ['label' => 'Projects page', 'url' => route('projects')],
                    ],
            ],
            [
                'topic' => 'projects',
                'patterns' => ['projects', 'portfolio work', 'portfolio', 'featured project', 'work samples'],
                'answer' => $projects->isNotEmpty()
                    ? 'Featured project highlights: ' . $this->joinItems($projects->take(3)->pluck('title')->all(), 3) . '.'
                    : 'There are no published projects yet.',
                'links' => [
                    ['label' => 'Projects page', 'url' => route('projects')],
                ],
            ],
            [
                'topic' => 'blog',
                'patterns' => ['blog', 'blogs', 'article', 'articles', 'post', 'writing'],
                'answer' => $blogs->isNotEmpty()
                    ? 'Recent writing includes ' . $this->formatBlogList($blogs) . '.'
                    : 'There are no published blog posts yet.',
                'links' => [
                    ['label' => 'Visit blog', 'url' => route('blog')],
                ],
            ],
            [
                'topic' => 'resume',
                'patterns' => ['resume', 'cv'],
                'answer' => !empty($profile?->cv_file)
                    ? 'A resume is available to download from the resume section.'
                    : 'The resume page is available, but no CV file is attached right now.',
                'links' => array_filter([
                    ['label' => 'Resume page', 'url' => route('resume')],
                    !empty($profile?->cv_file) ? ['label' => 'Download CV', 'url' => asset('storage/' . $profile->cv_file)] : null,
                ]),
            ],
            [
                'topic' => 'experience',
                'patterns' => ['experience', 'work experience', 'background experience', 'worked on'],
                'answer' => $profile?->experience
                    ? 'Experience: ' . $profile->experience . '.'
                    : 'Experience details have not been filled in yet.',
                'links' => [
                    ['label' => 'Resume page', 'url' => route('resume')],
                    ['label' => 'Projects page', 'url' => route('projects')],
                ],
            ],
            [
                'topic' => 'education',
                'patterns' => ['education', 'study', 'degree', 'school', 'university'],
                'answer' => $profile?->education
                    ? 'Education: ' . Str::limit(trim(strip_tags($profile->education)), 220)
                    : 'Education details have not been added yet.',
                'links' => [
                    ['label' => 'Resume page', 'url' => route('resume')],
                ],
            ],
            [
                'topic' => 'goals',
                'patterns' => ['goal', 'goals', 'future plan', 'career plan', 'career goal'],
                'answer' => $profile?->goals
                    ? 'Career goals: ' . Str::limit(trim(strip_tags($profile->goals)), 220)
                    : 'Goals have not been added yet, but the portfolio points toward continued web development growth.',
                'links' => [
                    ['label' => 'About page', 'url' => route('about')],
                    ['label' => 'Resume page', 'url' => route('resume')],
                ],
            ],
            [
                'topic' => 'location',
                'patterns' => ['where are you', 'location', 'based', 'address', 'where do you live'],
                'answer' => $profile?->address || $setting?->address
                    ? 'Location: ' . ($profile?->address ?: $setting?->address) . '.'
                    : 'A location has not been published yet.',
                'links' => [
                    ['label' => 'Contact page', 'url' => route('contact')],
                ],
            ],
            [
                'topic' => 'email',
                'patterns' => ['email', 'mail address', 'email address'],
                'answer' => $profile?->email || $setting?->contact_email
                    ? 'Email: ' . ($profile?->email ?: $setting?->contact_email) . '.'
                    : 'No contact email has been published yet.',
                'links' => [
                    ['label' => 'Contact page', 'url' => route('contact')],
                ],
            ],
            [
                'topic' => 'phone',
                'patterns' => ['phone', 'number', 'call', 'mobile', 'whatsapp number'],
                'answer' => $profile?->phone || $setting?->contact_phone
                    ? 'Phone: ' . ($profile?->phone ?: $setting?->contact_phone) . '.'
                    : 'No phone number has been published yet.',
                'links' => [
                    ['label' => 'Contact page', 'url' => route('contact')],
                ],
            ],
            [
                'topic' => 'social',
                'patterns' => ['social', 'linkedin', 'github', 'telegram', 'facebook', 'instagram'],
                'answer' => $socialLinks->isNotEmpty()
                    ? 'Available social links: ' . $this->joinItems($socialLinks->pluck('platform')->all(), 6) . '.'
                    : 'No social links are published yet.',
                'links' => $socialLinks->take(4)->map(function ($socialLink) {
                    return [
                        'label' => $socialLink->platform,
                        'url' => $socialLink->url,
                    ];
                })->values()->all(),
            ],
            [
                'topic' => 'availability',
                'patterns' => ['available', 'availability', 'hire', 'freelance', 'internship'],
                'answer' => 'The portfolio states availability for internships and project discussions.',
                'links' => [
                    ['label' => 'Contact page', 'url' => route('contact')],
                    ['label' => 'Services page', 'url' => route('services')],
                ],
            ],
            [
                'topic' => 'contact',
                'patterns' => ['contact', 'get in touch', 'send message', 'reach out', 'message you'],
                'answer' => 'Use the contact page form to send a direct message, or use the published email and phone details if they are available.',
                'links' => [
                    ['label' => 'Contact page', 'url' => route('contact')],
                ],
            ],
            [
                'topic' => 'navigation',
                'patterns' => ['which page should i visit', 'where should i go next', 'next page', 'which page'],
                'answer' => 'For work samples, open Projects. For capabilities, open Services or About. For direct outreach, open Contact.',
                'links' => [
                    ['label' => 'Projects page', 'url' => route('projects')],
                    ['label' => 'About page', 'url' => route('about')],
                    ['label' => 'Contact page', 'url' => route('contact')],
                ],
            ],
        ];

        foreach ($intents as $intent) {
            foreach ($intent['patterns'] as $pattern) {
                if ($this->containsPattern($normalized, $pattern)) {
                    return $intent + ['suggestions' => $this->defaultSuggestionsForTopic($intent['topic'])];
                }
            }
        }

        return null;
    }

    protected function fallbackResponse(array $context): array
    {
        $siteName = $context['setting']?->site_name ?: 'this portfolio';

        return [
            'topic' => 'fallback',
            'answer' => 'I can help with questions about ' . $siteName . ', including skills, technologies, services, projects, blog posts, resume, and contact details.',
            'links' => [
                ['label' => 'Home page', 'url' => route('home')],
                ['label' => 'Projects page', 'url' => route('projects')],
                ['label' => 'Contact page', 'url' => route('contact')],
            ],
            'suggestions' => $this->defaultSuggestions(),
        ];
    }

    protected function defaultSuggestions(): array
    {
        return [
            'What services do you offer?',
            'Show me featured projects.',
            'What skills are highlighted?',
            'How can I contact you?',
        ];
    }

    protected function defaultSuggestionsForTopic(string $topic): array
    {
        return match ($topic) {
            'skills', 'technology' => [
                'What services do you offer?',
                'Show me featured projects.',
                'Can I download the resume?',
            ],
            'services', 'availability', 'contact' => [
                'How can I send a message?',
                'What is the contact email?',
                'Show me featured projects.',
            ],
            'projects', 'latest-project', 'project-detail' => [
                'What technologies do you use?',
                'What services do you offer?',
                'How can I contact you?',
            ],
            'blog', 'blog-detail' => [
                'Show me featured projects.',
                'Can I download the resume?',
                'How can I contact you?',
            ],
            'resume', 'experience', 'education', 'goals' => [
                'What skills are highlighted?',
                'What services do you offer?',
                'How can I contact you?',
            ],
            default => $this->defaultSuggestions(),
        };
    }

    protected function projectAnswer(Project $project): string
    {
        $parts = [
            'Project: ' . $project->title . '.',
        ];

        if (!empty($project->technology_used)) {
            $parts[] = 'Tech: ' . Str::limit(trim(strip_tags($project->technology_used)), 120) . '.';
        }

        if (!empty($project->description)) {
            $parts[] = Str::limit(trim(strip_tags($project->description)), 180);
        }

        return implode(' ', $parts);
    }

    protected function serviceAnswer(Service $service): string
    {
        $description = $service->short_description
            ? Str::limit(trim(strip_tags($service->short_description)), 180)
            : 'A service listed in the portfolio.';

        return $service->title . ': ' . $description;
    }

    protected function blogAnswer(Blog $blog): string
    {
        $parts = [
            'Article: ' . $blog->title . '.',
        ];

        if ($blog->published_at) {
            $parts[] = 'Published ' . $blog->published_at->format('F d, Y') . '.';
        }

        if (!empty($blog->short_description)) {
            $parts[] = Str::limit(trim(strip_tags($blog->short_description)), 180);
        }

        return implode(' ', $parts);
    }

    protected function formatSkillList($skills): string
    {
        return $skills
            ->take(6)
            ->map(function ($skill) {
                $percentage = $skill->percentage ? ' (' . $skill->percentage . '%)' : '';

                return $skill->skill_name . $percentage;
            })
            ->implode(', ');
    }

    protected function formatBlogList($blogs): string
    {
        return $blogs
            ->take(3)
            ->map(function ($blog) {
                if ($blog->published_at) {
                    return $blog->title . ' (' . $blog->published_at->format('M d, Y') . ')';
                }

                return $blog->title;
            })
            ->implode(', ');
    }

    protected function technologyList(?string $value): array
    {
        if (empty($value)) {
            return [];
        }

        return collect(explode(',', $value))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    protected function joinItems(array $items, int $limit = 5): string
    {
        return collect($items)
            ->filter(fn ($item) => filled($item))
            ->take($limit)
            ->implode(', ');
    }

    protected function normalize(string $value): string
    {
        $normalized = Str::lower($value);
        $normalized = preg_replace('/[^a-z0-9\s]/', ' ', $normalized) ?? $normalized;

        return trim(preg_replace('/\s+/', ' ', $normalized) ?? $normalized);
    }

    protected function containsPattern(string $normalized, string $pattern): bool
    {
        $normalized = ' ' . trim($normalized) . ' ';
        $pattern = ' ' . trim($this->normalize($pattern)) . ' ';

        return str_contains($normalized, $pattern);
    }
}
