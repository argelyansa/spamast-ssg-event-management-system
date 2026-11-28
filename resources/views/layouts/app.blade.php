<!DOCTYPE html>
<html lang="en" data-theme="{{ auth()->check() ? (auth()->user()->theme ?? 'navy') : 'navy' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPAMAST SSG Event Management System')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root,
        html[data-theme="navy"] {
            --page-bg: #F1F5F9;
            --surface: #FFFFFF;
            --text: #1E293B;
            --muted: #64748B;
            --header-bg: #0B1120;
            --accent: #3B82F6;
            --accent-hover: #2563EB;
            --border: #CBD5E1;
            --focus-ring: rgba(59,130,246,.15);
        }

        html[data-theme="light"] {
            --page-bg: #F8FAFC;
            --surface: #FFFFFF;
            --text: #334155;
            --muted: #64748B;
            --header-bg: #334155;
            --accent: #475569;
            --accent-hover: #334155;
            --border: #CBD5E1;
            --focus-ring: rgba(71,85,105,.15);
        }

        html[data-theme="dark"] {
            --page-bg: #111827;
            --surface: #1F2937;
            --text: #F1F5F9;
            --muted: #CBD5E1;
            --header-bg: #030712;
            --accent: #6366F1;
            --accent-hover: #4F46E5;
            --border: #475569;
            --focus-ring: rgba(99,102,241,.25);
        }

        html[data-theme="forest"] {
            --page-bg: #ECFDF5;
            --surface: #FFFFFF;
            --text: #14532D;
            --muted: #4B6B55;
            --header-bg: #123524;
            --accent: #228B52;
            --accent-hover: #166534;
            --border: #BBF7D0;
            --focus-ring: rgba(34,139,82,.15);
        }

        html[data-theme="pink"] {
            --page-bg: #FFF1F5;
            --surface: #FFFFFF;
            --text: #831843;
            --muted: #9D647D;
            --header-bg: #831843;
            --accent: #EC80A8;
            --accent-hover: #DB6492;
            --border: #FBCFE8;
            --focus-ring: rgba(236,128,168,.2);
        }

        html[data-theme="rose"] {
            --page-bg: #FFF0F3;
            --surface: #FFFFFF;
            --text: #881337;
            --muted: #9F526B;
            --header-bg: #881337;
            --accent: #E11D72;
            --accent-hover: #BE185D;
            --border: #FECDD3;
            --focus-ring: rgba(225,29,114,.18);
        }

        html[data-theme="lavender"] {
            --page-bg: #F5F3FF;
            --surface: #FFFFFF;
            --text: #4C1D95;
            --muted: #7C6A9B;
            --header-bg: #31205F;
            --accent: #8B5CF6;
            --accent-hover: #7C3AED;
            --border: #DDD6FE;
            --focus-ring: rgba(139,92,246,.18);
        }

        html[data-theme="teal"] {
            --page-bg: #ECFEFF;
            --surface: #FFFFFF;
            --text: #134E4A;
            --muted: #527B78;
            --header-bg: #123C3A;
            --accent: #0D9488;
            --accent-hover: #0F766E;
            --border: #99F6E4;
            --focus-ring: rgba(13,148,136,.18);
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: var(--page-bg);
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            flex-direction: column;
        }

        .app-header {
            background: var(--header-bg);
            color: #FFFFFF;
            padding: 16px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            border-bottom: 3px solid var(--accent);
            box-shadow: 0 3px 12px rgba(0,0,0,.12);
        }

        .brand {
            font-size: 23px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #FFFFFF;
        }

        .brand span {
            color: var(--accent);
        }

        .header-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 14px;
            flex-wrap: wrap;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #FFFFFF;
            font-weight: 600;
        }

        .header-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background: var(--accent);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .header-actions a {
            color: #FFFFFF;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .header-actions a:hover {
            color: var(--accent);
        }

        .logout-button,
        .theme-save {
            border: none;
            border-radius: 7px;
            padding: 8px 13px;
            background: var(--accent);
            color: #FFFFFF;
            font-weight: 600;
            cursor: pointer;
        }

        .logout-button:hover,
        .theme-save:hover {
            background: var(--accent-hover);
        }

        .theme-form {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .theme-select {
            max-width: 145px;
            padding: 8px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: var(--surface);
            color: var(--text);
            font-size: 13px;
        }

        .form-control,
        .form-select,
        .input-group-text {
            background-color: var(--surface);
            color: var(--text);
        }

        .form-control::placeholder {
            color: var(--muted);
            opacity: 1;
        }

        .form-control:disabled,
        .form-select:disabled {
            background-color: var(--page-bg);
            color: var(--muted);
        }

        .page-container {
            width: 100%;
            max-width: 1500px;
            margin: 0 auto;
            padding: 28px 5%;
            flex: 1;
        }

        .alert {
            border-radius: 10px;
        }

        .app-footer {
            padding: 18px 5%;
            text-align: center;
            background: var(--header-bg);
            color: #FFFFFF;
            font-size: 13px;
            margin-top: auto;
        }

        .card {
            border-radius: 14px !important;
            background-color: var(--surface);
            color: var(--text);
        }

        .form-control,
        .form-select,
        .input-group-text {
            border-color: var(--border);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 .2rem var(--focus-ring);
        }

        .btn-primary {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        .btn-primary:hover {
            background-color: var(--accent-hover);
            border-color: var(--accent-hover);
        }

        @media (max-width: 600px) {
            .app-header {
                padding: 14px 5%;
            }

            .header-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .page-container {
                padding: 20px 4%;
            }

            .theme-form {
                width: 100%;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

<header class="app-header">
    <div class="brand">
        SPAMAST <span>SSG</span>
    </div>

    @auth
        <nav class="header-actions">
            <span class="header-user">
                @if(auth()->user()->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->photo))
                    <img
                        src="{{ asset('storage/' . auth()->user()->photo) }}"
                        alt="Profile photo"
                        class="header-avatar"
                    >
                @else
                    <span class="header-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                @endif

                {{ auth()->user()->name }}
            </span>

            <a href="{{ route('profile.edit') }}">My Profile</a>

            <form action="{{ route('theme.update') }}" method="POST" class="theme-form">
                @csrf
                @method('PUT')

                <select name="theme" class="theme-select" aria-label="Choose theme">
                    <option value="navy" {{ auth()->user()->theme === 'navy' ? 'selected' : '' }}>Navy Blue</option>
                    <option value="light" {{ auth()->user()->theme === 'light' ? 'selected' : '' }}>Light</option>
                    <option value="dark" {{ auth()->user()->theme === 'dark' ? 'selected' : '' }}>Dark</option>
                    <option value="forest" {{ auth()->user()->theme === 'forest' ? 'selected' : '' }}>Forest Green</option>
                    <option value="pink" {{ auth()->user()->theme === 'pink' ? 'selected' : '' }}>Soft Pink</option>
                    <option value="rose" {{ auth()->user()->theme === 'rose' ? 'selected' : '' }}>Rose Pink</option>
                    <option value="lavender" {{ auth()->user()->theme === 'lavender' ? 'selected' : '' }}>Lavender</option>
                    <option value="teal" {{ auth()->user()->theme === 'teal' ? 'selected' : '' }}>Ocean Teal</option>
                </select>

                <button type="submit" class="theme-save">Save Theme</button>
            </form>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </nav>
    @endauth
</header>

<main class="page-container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

<footer class="app-footer">
    SPAMAST Supreme Student Government Event Management System
    &copy; {{ date('Y') }}
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>
