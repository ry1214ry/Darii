<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|space-grotesk:500,700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        (function () {
            document.documentElement.classList.add('js');
            var savedTheme = localStorage.getItem('site-theme');
            var systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            var theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body
    class="frontend-page {{ data_get($pageData, 'component') === 'HomePage' ? 'has-home-loader' : '' }}"
    @if(data_get($pageData, 'component') === 'HomePage')
        data-site-home-loader="true"
    @endif
>
    <div id="frontend-app"></div>

    <script>
        window.__FRONTEND_PAGE__ = {!! Illuminate\Support\Js::from($pageData) !!};
    </script>
</body>
</html>
