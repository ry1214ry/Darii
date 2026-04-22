<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $setting->site_name ?? 'Portfolio Website' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|space-grotesk:500,700&display=swap" rel="stylesheet" />
    <script>
        (function () {
            document.documentElement.classList.add('js');
            var savedTheme = localStorage.getItem('site-theme');
            var systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            var theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body
    class="frontend-page {{ request()->routeIs('home') ? 'has-home-loader' : '' }}"
    @if(request()->routeIs('home'))
        data-site-home-loader="true"
    @endif
>
@if(request()->routeIs('home'))
    <div class="site-loader" data-site-loader role="status" aria-live="polite" aria-label="Loading Roeun Dary homepage">
        <div class="site-loader-shell">
            <h1 class="site-loader-title">ROEUN DARY</h1>
            <span class="site-loader-progress" aria-hidden="true">
                <span></span>
            </span>
        </div>
    </div>
@endif

<div class="site-shell">

<header class="site-header">
    <div class="container">
        <nav class="navbar site-navbar">
            <a class="site-brand text-decoration-none" href="{{ route('home') }}">
                <span class="site-brand-mark">RD</span>
                <span class="site-brand-copy">
                    <strong>{{ $setting->site_name ?? 'Roeun Dary Portfolio' }}</strong>
                    <span>Laravel Developer</span>
                </span>
            </a>

            <div class="site-nav-wrap" id="site-nav-panel" data-site-nav-panel>
                <div class="site-nav-list">
                    <a class="site-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">Home</a>
                    <a class="site-nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}" href="{{ route('about') }}">About</a>
                    <a class="site-nav-link {{ request()->routeIs('projects', 'projects.show') ? 'is-active' : '' }}" href="{{ route('projects') }}">Projects</a>
                    <a class="site-nav-link {{ request()->routeIs('services') ? 'is-active' : '' }}" href="{{ route('services') }}">Services</a>
                    <a class="site-nav-link {{ request()->routeIs('blog', 'blog.show') ? 'is-active' : '' }}" href="{{ route('blog') }}">Blog</a>
                    <a class="site-nav-link {{ request()->routeIs('resume') ? 'is-active' : '' }}" href="{{ route('resume') }}">Resume</a>
                    <a class="site-nav-link {{ request()->routeIs('contact') ? 'is-active' : '' }}" href="{{ route('contact') }}">Contact</a>
                </div>

                <a class="site-btn site-btn-primary site-nav-cta" href="{{ route('contact') }}">Let's Talk</a>
            </div>

            <div class="site-navbar-actions">
                <button type="button" class="site-theme-toggle" data-site-theme-toggle aria-label="Toggle dark mode" aria-pressed="false">
                    <span class="site-theme-icon-wrap" aria-hidden="true">
                        <svg class="site-theme-icon site-theme-icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4.2"></circle>
                            <path d="M12 2.8v2.3M12 18.9v2.3M21.2 12h-2.3M5.1 12H2.8M18.5 5.5l-1.6 1.6M7.1 16.9l-1.6 1.6M18.5 18.5l-1.6-1.6M7.1 7.1 5.5 5.5"></path>
                        </svg>
                        <svg class="site-theme-icon site-theme-icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 15.2A8.6 8.6 0 1 1 8.8 4a7.1 7.1 0 0 0 11.2 11.2Z"></path>
                        </svg>
                    </span>
                    <span class="site-theme-toggle-state visually-hidden" data-site-theme-state>Light</span>
                </button>

                <button
                    type="button"
                    class="site-nav-toggle"
                    data-site-nav-toggle
                    aria-label="Toggle navigation menu"
                    aria-expanded="false"
                    aria-controls="site-nav-panel"
                >
                    <span class="site-nav-toggle-box" aria-hidden="true">
                        <span class="site-nav-toggle-line"></span>
                        <span class="site-nav-toggle-line"></span>
                        <span class="site-nav-toggle-line"></span>
                    </span>
                </button>
            </div>
        </nav>
    </div>
</header>

<main class="site-main">
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container">
        <div class="site-footer-grid">
            <div class="site-footer-block">
                <a class="site-brand text-decoration-none" href="{{ route('home') }}">
                    <span class="site-brand-mark">RD</span>
                    <span class="site-brand-copy">
                        <strong>{{ $setting->site_name ?? 'Roeun Dary Portfolio' }}</strong>
                        <span>Modern web portfolio</span>
                    </span>
                </a>
                <p class="site-footer-copy mt-4 mb-0">{{ $setting->footer_text ?? 'Portfolio website. All rights reserved.' }}</p>
            </div>

            <div class="site-footer-block">
                <p class="site-footer-title">Explore</p>
                <div class="site-footer-links">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('about') }}">About</a>
                    <a href="{{ route('projects') }}">Projects</a>
                    <a href="{{ route('services') }}">Services</a>
                    <a href="{{ route('blog') }}">Blog</a>
                    <a href="{{ route('resume') }}">Resume</a>
                    <a href="{{ route('contact') }}">Contact</a>
                </div>
            </div>

            <div class="site-footer-block">
                <p class="site-footer-title">Contact</p>
                <ul class="site-footer-contact">
                    <li>{{ $setting->contact_email ?? 'Email not set' }}</li>
                    <li>{{ $setting->contact_phone ?? 'Phone not set' }}</li>
                    <li>{{ $setting->address ?? 'Address not set' }}</li>
                </ul>
            </div>
        </div>
    </div>
</footer>

@include('frontend.partials.chatbot')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
