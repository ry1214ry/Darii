<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|space-grotesk:500,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="auth-page antialiased">
        <div class="auth-shell">
            <div class="auth-grid">
                <section class="auth-showcase">
                    <a href="/" class="auth-mark">
                        <span class="auth-mark-icon">RD</span>
                        <span class="auth-mark-text">
                            <strong>Roeun Dary</strong>
                            <span>Laravel Developer</span>
                        </span>
                    </a>

                    <div class="auth-showcase-copy">
                        <p class="auth-kicker">Admin Access</p>
                        <h1>Manage your portfolio with a sharper workspace.</h1>
                        <p>
                            Sign in to update projects, services, blog posts, profile details,
                            and the content that appears across the public website.
                        </p>
                    </div>

                    <div class="auth-showcase-panels">
                        <div class="auth-panel">
                            <span class="auth-panel-label">Content</span>
                            <strong>Projects, profile, blog, services</strong>
                        </div>
                        <div class="auth-panel">
                            <span class="auth-panel-label">Goal</span>
                            <strong>Keep the portfolio complete and current</strong>
                        </div>
                    </div>
                </section>

                <section class="auth-form-wrap">
                    <div class="auth-form-card">
                        {{ $slot }}
                    </div>
                </section>
            </div>
        </div>
    </body>
</html>
