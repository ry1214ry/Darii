<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($setting) && $setting->site_name ? $setting->site_name . ' Admin' : 'Admin Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="admin-page">

<div class="admin-shell">
    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="admin-brand text-decoration-none">
            <span class="admin-brand-mark">RD</span>
            <span class="admin-brand-copy">
                <strong>Roeun Dary</strong>
                <span>Portfolio Admin</span>
            </span>
        </a>

        <div class="admin-sidebar-section">
            <p class="admin-sidebar-label">Navigation</p>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.profiles.index') }}" class="admin-nav-link {{ request()->routeIs('admin.profiles.*') ? 'is-active' : '' }}">Profiles</a>
                <a href="{{ route('admin.skills.index') }}" class="admin-nav-link {{ request()->routeIs('admin.skills.*') ? 'is-active' : '' }}">Skills</a>
                <a href="{{ route('admin.categories.index') }}" class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}">Categories</a>
                <a href="{{ route('admin.projects.index') }}" class="admin-nav-link {{ request()->routeIs('admin.projects.*') ? 'is-active' : '' }}">Projects</a>
                <a href="{{ route('admin.services.index') }}" class="admin-nav-link {{ request()->routeIs('admin.services.*') ? 'is-active' : '' }}">Services</a>
                <a href="{{ route('admin.blogs.index') }}" class="admin-nav-link {{ request()->routeIs('admin.blogs.*') ? 'is-active' : '' }}">Blogs</a>
                <a href="{{ route('admin.messages.index') }}" class="admin-nav-link {{ request()->routeIs('admin.messages.*') ? 'is-active' : '' }}">Messages</a>
            </nav>
        </div>

        <div class="admin-sidebar-footer">
            <p class="admin-sidebar-label">Account</p>
            <div class="admin-user-card">
                <div>
                    <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>
                    <p>{{ auth()->user()->email ?? 'dashboard access' }}</p>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="admin-logout-btn" type="submit">Logout</button>
            </form>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <div>
                <p class="admin-topbar-kicker">Control Center</p>
                <h1 class="admin-topbar-title">@yield('page_title', 'Admin Dashboard')</h1>
            </div>

            <div class="admin-topbar-meta">
                <span class="admin-meta-pill">Manage portfolio content</span>
                <span class="admin-meta-user">{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>
        </header>

        <section class="admin-content">
            @if(session('success'))
                <div class="admin-alert admin-alert-success">{{ session('success') }}</div>
            @endif

            @yield('content')
        </section>
    </main>
</div>

</body>
</html>
