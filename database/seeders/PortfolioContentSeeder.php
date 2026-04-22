<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\SocialLink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PortfolioContentSeeder extends Seeder
{
    public function run(): void
    {
        $profile = Profile::query()->latest('id')->first();
        $profileImage = $this->existingPublicFile($profile?->profile_image)
            ?? $this->preferredPublicFile('profiles', [
                'profiles/X0wWJ8gedLgNDUuUo6tY3gHoqZqbumlaCpcXJcsX.jpg',
                'profiles/gPbh7kiqAxBgTZpJAfp09Z2ra59TBRq0YW7RGqWE.png',
                'profiles/Mhk3fNrQNYT1qP6cpNDQ6PoWx0drPFKm1HRrxCih.png',
                'profiles/cpdF3FuOYImRkHPMUjJglS2ArYWOekTjsl3T9scA.jpg',
            ]);
        $cvFile = $this->existingPublicFile($profile?->cv_file)
            ?? $this->preferredPublicFile('cv', [
                'cv/RkTkxAwsYIMpKLHHW3PNr3jCuUWeIQtY0isPUOSk.pdf',
                'cv/WnNpodcVO9bG8ZfMAaRTJlp2JWaMsuIpKCQ4jQlm.pdf',
                'cv/8ZPkRFp4PMsHcWNrIPPJOq8s1xmHcXtaxWWywgTg.pdf',
            ]);
        $projectImages = $this->preferredPublicFiles('projects', [
            'projects/N2Acy4WXd0HYzUkOhNxbINse5XPjycOA9EQvTwmh.png',
            'projects/ggtUmj0fIIpUyomQbcHekmWHDmXAMcW909ws0rpm.png',
            'projects/lDTfx9KUR0W1g1ikrSmMDZL1GWmfNH7HUYpWsq9g.png',
            'projects/1pml4bhUv0v4iveQsJfcgNtVFiX9fvPLP1jqZWT0.png',
            'projects/dw2ySWMZKJjxTAOSG5dskAuGxNxhOD4FUSaDxNz1.png',
            'projects/IZx0DpVFDBaXHjPUTdfpHS1DToVotPoEenj67h36.png',
        ]);
        $projectVideos = $this->preferredPublicFiles('project-demos', [
            'project-demos/flower.mp4',
            'project-demos/mov_bbb.mp4',
            'project-demos/sample-5s.mp4',
        ]);
        $blogImages = [
            $this->nthOrFirst($projectImages, 2),
            $this->nthOrFirst($projectImages, 0),
            $this->nthOrFirst($projectImages, 1),
            $this->nthOrFirst($projectImages, 3),
        ];

        $profileData = [
            'full_name' => 'Roeun Dary',
            'job_title' => 'Laravel Developer',
            'short_intro' => 'I build practical web applications with Laravel, MySQL, Bootstrap, and modern frontend tooling for portfolios, dashboards, and content-driven websites.',
            'about_me' => 'I am Roeun Dary, an Information Technology and Security student at BELTEI University with a strong interest in Laravel development, database structure, and practical user interfaces. I enjoy turning ideas into clean portfolio sites, internal dashboards, and structured web applications that are easy to manage. My current focus is strengthening backend logic, improving frontend polish, and building portfolio work that reflects real project delivery.',
            'profile_image' => $profileImage,
            'profile_video' => null,
            'experience' => 'Built and refined Laravel-based portfolio, dashboard, booking, and academic system projects with a focus on practical CRUD workflows and responsive frontend delivery.',
            'education' => 'Bachelor of Information Technology and Security, BELTEI University (2023 - Present). Graduated from Hun Sen Chumpuvorn High School (2021 - 2023).',
            'technologies' => 'Laravel, PHP, MySQL, Bootstrap, JavaScript, HTML, CSS, Vite',
            'goals' => 'Grow into a strong full-stack developer, contribute to real client projects, and continue improving backend architecture, frontend presentation, and deployment skills.',
            'cv_file' => $cvFile,
            'email' => 'roeundary28@gmail.com',
            'phone' => '+855 96 897 0713',
            'address' => 'House A33, Lum Road, Trapeang Krasaing Village, Sangkat Trapeang Krasaing, Khan Por Senchey, Phnom Penh, Cambodia',
        ];

        if ($profile) {
            $profile->fill($profileData);
            $profile->save();
        } else {
            $profile = Profile::create($profileData);
        }

        $homeSection = HomeSection::query()->latest('id')->first();
        $homeSectionData = [
            'title' => 'Roeun Dary',
            'subtitle' => 'Laravel Developer',
            'short_description' => 'I create responsive portfolio sites, admin dashboards, booking systems, and database-driven web applications that are simple to use and easy to maintain.',
            'hero_image' => $profile->profile_image,
            'hero_video' => null,
            'hire_me_link' => '/contact',
            'cv_button_text' => 'Download CV',
            'status' => true,
        ];

        if ($homeSection) {
            $homeSection->fill($homeSectionData);
            $homeSection->save();
        } else {
            HomeSection::create($homeSectionData);
        }

        $projectCategories = collect([
            ['name' => 'Web Application', 'slug' => 'web-application', 'type' => 'project'],
            ['name' => 'Dashboard System', 'slug' => 'dashboard-system', 'type' => 'project'],
            ['name' => 'Portfolio Website', 'slug' => 'portfolio-website', 'type' => 'project'],
        ])->mapWithKeys(function (array $category) {
            $record = Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'type' => $category['type'],
                    'status' => true,
                ]
            );

            return [$category['slug'] => $record];
        });

        $blogCategories = collect([
            ['name' => 'Laravel', 'slug' => 'laravel', 'type' => 'blog'],
            ['name' => 'Career Growth', 'slug' => 'career-growth', 'type' => 'blog'],
            ['name' => 'Web Development', 'slug' => 'web-development', 'type' => 'blog'],
        ])->mapWithKeys(function (array $category) {
            $record = Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'type' => $category['type'],
                    'status' => true,
                ]
            );

            return [$category['slug'] => $record];
        });

        $skills = [
            ['skill_name' => 'Laravel', 'percentage' => 95, 'level' => 'Advanced', 'sort_order' => 1],
            ['skill_name' => 'PHP', 'percentage' => 90, 'level' => 'Advanced', 'sort_order' => 2],
            ['skill_name' => 'MySQL', 'percentage' => 88, 'level' => 'Advanced', 'sort_order' => 3],
            ['skill_name' => 'Bootstrap', 'percentage' => 86, 'level' => 'Advanced', 'sort_order' => 4],
            ['skill_name' => 'JavaScript', 'percentage' => 80, 'level' => 'Intermediate', 'sort_order' => 5],
            ['skill_name' => 'HTML & CSS', 'percentage' => 92, 'level' => 'Advanced', 'sort_order' => 6],
            ['skill_name' => 'Vite', 'percentage' => 78, 'level' => 'Intermediate', 'sort_order' => 7],
            ['skill_name' => 'Git & GitHub', 'percentage' => 75, 'level' => 'Intermediate', 'sort_order' => 8],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(
                ['skill_name' => $skill['skill_name']],
                $skill + ['status' => true]
            );
        }

        $projects = [
            [
                'title' => 'Student Registration Dashboard',
                'slug' => 'student-registration-dashboard',
                'category_id' => $projectCategories['dashboard-system']->id,
                'description' => 'A dashboard-based system for managing student registration data, departments, class records, and enrollment workflows. This project focused on structured admin screens, table organization, and cleaner academic data handling.',
                'technology_used' => 'Laravel, PHP, MySQL, Bootstrap',
                'image' => null,
                'project_url' => null,
                'github_url' => null,
                'demo_video' => $this->nthOrFirst($projectVideos, 0),
                'is_featured' => true,
            ],
            [
                'title' => 'Inventory Management System',
                'slug' => 'inventory-management-system',
                'category_id' => $projectCategories['dashboard-system']->id,
                'description' => 'A practical inventory manager for tracking product records, stock levels, status updates, and category-based organization. It helped strengthen my work with relationships, validation, and reporting-oriented interfaces.',
                'technology_used' => 'Laravel, MySQL, JavaScript, Bootstrap',
                'image' => null,
                'project_url' => null,
                'github_url' => null,
                'demo_video' => $this->nthOrFirst($projectVideos, 1),
                'is_featured' => true,
            ],
            [
                'title' => 'Service Booking Website',
                'slug' => 'service-booking-website',
                'category_id' => $projectCategories['web-application']->id,
                'description' => 'A service-oriented website with booking-focused call-to-actions, service sections, and responsive layouts. The goal was to improve reusable UI sections, cleaner content flow, and mobile-friendly presentation.',
                'technology_used' => 'Laravel, Blade, Bootstrap, CSS',
                'image' => null,
                'project_url' => null,
                'github_url' => null,
                'demo_video' => $this->nthOrFirst($projectVideos, 2),
                'is_featured' => true,
            ],
            [
                'title' => 'Academic Result Portal',
                'slug' => 'academic-result-portal',
                'category_id' => $projectCategories['web-application']->id,
                'description' => 'A result portal concept for displaying student performance information in a clear, searchable format. This project strengthened my experience with data presentation, sorting, and frontend clarity for academic records.',
                'technology_used' => 'Laravel, MySQL, HTML, CSS',
                'image' => null,
                'project_url' => null,
                'github_url' => null,
                'demo_video' => $this->nthOrFirst($projectVideos, 0),
                'is_featured' => true,
            ],
            [
                'title' => 'Business Profile Website',
                'slug' => 'business-profile-website',
                'category_id' => $projectCategories['portfolio-website']->id,
                'description' => 'A company profile style website created to present business services, key information, and contact options in a clean layout. The focus was clarity, responsiveness, and manageable content structure.',
                'technology_used' => 'Laravel, Bootstrap, JavaScript, Vite',
                'image' => null,
                'project_url' => null,
                'github_url' => null,
                'demo_video' => $this->nthOrFirst($projectVideos, 1),
                'is_featured' => true,
            ],
            [
                'title' => 'Dary Portfolio Platform',
                'slug' => 'dary-portfolio-platform',
                'category_id' => $projectCategories['portfolio-website']->id,
                'description' => 'A personal portfolio website with an admin dashboard for managing profile content, projects, services, blogs, and contact messages. It combines a polished frontend with practical content management on the backend.',
                'technology_used' => 'Laravel, Blade, MySQL, Bootstrap, Vite',
                'image' => null,
                'project_url' => null,
                'github_url' => null,
                'demo_video' => $this->nthOrFirst($projectVideos, 2),
                'is_featured' => true,
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['slug' => $project['slug']],
                $project + ['status' => true]
            );
        }

        $services = [
            [
                'title' => 'Laravel Web Development',
                'short_description' => 'Building secure, database-driven Laravel websites and application features for portfolios, dashboards, and business systems.',
            ],
            [
                'title' => 'Responsive Frontend Implementation',
                'short_description' => 'Creating clean Bootstrap-based interfaces that work well on desktop, tablet, and mobile screens.',
            ],
            [
                'title' => 'Database Design & CRUD Systems',
                'short_description' => 'Designing MySQL tables, relationships, and data management flows that support maintainable web applications.',
            ],
            [
                'title' => 'Admin Panel Development',
                'short_description' => 'Building practical admin dashboards for managing content, users, projects, services, and contact messages.',
            ],
            [
                'title' => 'Website Maintenance',
                'short_description' => 'Improving existing Laravel projects by fixing bugs, adjusting content, and refining frontend presentation.',
            ],
            [
                'title' => 'Portfolio Content Management',
                'short_description' => 'Setting up portfolio sections, blog content, project showcases, and admin-managed pages so the website stays complete and easy to update.',
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['title' => $service['title']],
                $service + ['status' => true, 'icon' => null]
            );
        }

        $blogs = [
            [
                'title' => 'Why I Enjoy Building With Laravel',
                'slug' => 'why-i-enjoy-building-with-laravel',
                'category_id' => $blogCategories['laravel']->id,
                'short_description' => 'Laravel gives me a clear structure for routing, database work, validation, and building maintainable features.',
                'content' => "Laravel helps me organize projects in a professional way. I can separate routes, controllers, models, and views clearly, which makes development easier to manage.\n\nI especially enjoy working with migrations, Eloquent, and Blade because they let me build complete web applications faster while still keeping the code understandable.\n\nAs I continue learning, Laravel remains one of the main tools I use to improve my backend thinking and application structure.",
                'published_at' => now()->subDays(18),
            ],
            [
                'title' => 'What I Learned From Database-Driven Projects',
                'slug' => 'what-i-learned-from-database-driven-projects',
                'category_id' => $blogCategories['web-development']->id,
                'short_description' => 'Working with real project tables taught me how important clean relationships and validation are.',
                'content' => "When I started building projects with MySQL, I learned that a good database structure makes the whole application easier to maintain.\n\nClear table relationships, validation rules, and organized CRUD logic reduce mistakes and help the user experience stay consistent.\n\nThis is why I pay attention to migration design, column naming, and how the frontend depends on backend data.",
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'My Next Goal As a Junior Developer',
                'slug' => 'my-next-goal-as-a-junior-developer',
                'category_id' => $blogCategories['career-growth']->id,
                'short_description' => 'My next step is turning student projects into stronger real-world portfolio work and internship experience.',
                'content' => "My goal is to become a stronger professional web developer by working on projects that reflect real business needs.\n\nI want to improve code quality, understand deployment better, and gain more experience collaborating with others on practical Laravel applications.\n\nEvery project I build is another chance to strengthen problem-solving, communication, and technical discipline.",
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => 'How I Improve Frontend Presentation In Laravel Projects',
                'slug' => 'how-i-improve-frontend-presentation-in-laravel-projects',
                'category_id' => $blogCategories['web-development']->id,
                'short_description' => 'Clear spacing, consistent cards, and organized sections make even simple Laravel websites look more professional.',
                'content' => "Frontend quality is not only about colors or effects. For me, it starts with structure, spacing, readable content, and sections that help visitors understand the website quickly.\n\nWhen I build Laravel projects, I try to keep layouts responsive, content blocks reusable, and admin-managed information easy to display without breaking the design.\n\nThis mindset helps me turn portfolio and dashboard projects into cleaner, more complete websites.",
                'published_at' => now()->subDays(1),
            ],
        ];

        foreach ($blogs as $index => $blog) {
            Blog::updateOrCreate(
                ['slug' => $blog['slug']],
                $blog + [
                    'image' => $blogImages[$index] ?? $blogImages[0] ?? null,
                    'author' => 'Roeun Dary',
                    'status' => true,
                ]
            );
        }

        $socialLinks = [
            [
                'platform' => 'Email',
                'url' => 'mailto:roeundary28@gmail.com',
            ],
            [
                'platform' => 'WhatsApp',
                'url' => 'https://wa.me/855968970713',
            ],
            [
                'platform' => 'Phone',
                'url' => 'tel:+855968970713',
            ],
        ];

        foreach ($socialLinks as $socialLink) {
            SocialLink::updateOrCreate(
                ['platform' => $socialLink['platform']],
                $socialLink + ['icon' => null, 'status' => true]
            );
        }

        $setting = Setting::query()->latest('id')->first();
        $settingData = [
            'site_name' => 'Dary Portfolio',
            'footer_text' => 'Dary Portfolio | Laravel Developer based in Phnom Penh, Cambodia.',
            'contact_email' => $profile->email,
            'contact_phone' => $profile->phone,
            'address' => $profile->address,
            'whatsapp_link' => 'https://wa.me/855968970713',
            'telegram_link' => null,
            'google_map_embed' => null,
        ];

        DB::table('settings')->updateOrInsert(
            ['id' => $setting?->id ?? 1],
            $settingData + [
                'site_logo' => $setting?->site_logo,
                'favicon' => $setting?->favicon,
                'updated_at' => now(),
                'created_at' => $setting?->created_at ?? now(),
            ]
        );
    }

    private function existingPublicFile(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return Storage::disk('public')->exists($path) ? $path : null;
    }

    private function preferredPublicFile(string $directory, array $preferred = []): ?string
    {
        return collect($preferred)
            ->merge(Storage::disk('public')->files($directory))
            ->filter(fn (?string $path) => filled($path) && Storage::disk('public')->exists($path))
            ->unique()
            ->values()
            ->first();
    }

    private function preferredPublicFiles(string $directory, array $preferred = []): array
    {
        return collect($preferred)
            ->merge(Storage::disk('public')->files($directory))
            ->filter(fn (?string $path) => filled($path) && Storage::disk('public')->exists($path))
            ->unique()
            ->values()
            ->all();
    }

    private function nthOrFirst(array $paths, int $index): ?string
    {
        return $paths[$index] ?? $paths[0] ?? null;
    }
}
