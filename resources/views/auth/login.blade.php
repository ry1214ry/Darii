<x-guest-layout>
    <div class="auth-form-head">
        <p class="auth-form-eyebrow">Welcome back</p>
        <h2>Log in to the dashboard</h2>
        <p>Use your account credentials to manage your portfolio content and admin data.</p>
    </div>

    <x-auth-session-status class="auth-status" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <label for="email" class="auth-label">{{ __('Email Address') }}</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                class="auth-input"
                placeholder="you@example.com"
            >
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>

        <div class="auth-field">
            <div class="auth-label-row">
                <label for="password" class="auth-label">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a class="auth-link" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="auth-input"
                placeholder="Enter your password"
            >
            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        <label for="remember_me" class="auth-check">
            <input id="remember_me" type="checkbox" name="remember">
            <span>{{ __('Keep me signed in on this device') }}</span>
        </label>

        <button type="submit" class="auth-submit">
            {{ __('Log in') }}
        </button>
    </form>
</x-guest-layout>
