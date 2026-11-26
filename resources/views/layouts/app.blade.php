<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | SPAMAST SSG</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --midnight: #0B1120;
            --deep-navy: #111C32;
            --slate: #1E293B;
            --blue: #3B82F6;
            --white: #F8FAFC;
            --gray: #64748B;
            --light-gray: #E2E8F0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: #F1F5F9;
            color: var(--slate);
            font-family: Arial, Helvetica, sans-serif;
        }

        .app-header {
            background: var(--midnight);
            color: white;
            padding: 16px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            border-bottom: 3px solid var(--blue);
            box-shadow: 0 3px 12px rgba(15, 23, 42, 0.15);
        }

        .brand {
            font-size: 21px;
            font-weight: 800;
            letter-spacing: .3px;
        }

        .brand span {
            color: var(--blue);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        .header-actions a {
            color: white;
            text-decoration: none;
        }

        .header-actions a:hover {
            color: #93C5FD;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-weight: 600;
        }

        .header-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #93C5FD;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--blue);
            color: white;
            font-size: 14px;
            font-weight: 700;
            overflow: hidden;
        }

        .logout-button {
            background: var(--blue);
            color: white;
            border: none;
            border-radius: 7px;
            padding: 9px 16px;
            cursor: pointer;
            font-weight: 600;
        }

        .logout-button:hover {
            background: #2563EB;
        }

        .page-container {
            width: 92%;
            max-width: 1440px;
            margin: 35px auto;
            min-height: calc(100vh - 190px);
        }

        .alert {
            border-radius: 9px;
            margin-bottom: 18px;
        }

        .app-footer {
            text-align: center;
            padding: 25px 15px;
            color: var(--gray);
            font-size: 13px;
        }

        .card {
            border-radius: 14px !important;
        }

        .form-control,
        .form-select,
        .input-group-text {
            border-color: #CBD5E1;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 .2rem rgba(59, 130, 246, .15);
        }

        .btn-primary {
            background-color: var(--blue);
            border-color: var(--blue);
        }

        .btn-primary:hover {
            background-color: #2563EB;
            border-color: #2563EB;
        }

        @media (max-width: 600px) {
            .app-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .header-actions {
                gap: 12px;
            }

            .page-container {
                width: 94%;
                margin: 22px auto;
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
                    @if (auth()->user()->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->photo))
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url(auth()->user()->photo) }}"
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

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-button">
                        Logout
                    </button>
                </form>
            </nav>
        @endauth
    </header>

    <main class="page-container">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
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
