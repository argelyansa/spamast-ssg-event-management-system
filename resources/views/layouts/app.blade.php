
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | SPAMAST SSG</title>

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
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            background: var(--white);
            color: var(--slate);
            font-family: Arial, Helvetica, sans-serif;
        }

        .app-header {
            background: var(--midnight);
            color: white;
            padding: 18px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .brand {
            font-size: 20px;
            font-weight: bold;
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

        .logout-button {
            background: var(--blue);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 9px 15px;
            cursor: pointer;
            font-weight: bold;
        }

        .logout-button:hover {
            background: #2563EB;
        }

        .page-container {
            width: 90%;
            max-width: 1200px;
            margin: 35px auto;
        }

        .page-heading {
            margin-bottom: 25px;
        }

        .page-heading h1 {
            color: var(--midnight);
            font-size: 28px;
            margin-bottom: 8px;
        }

        .page-heading p {
            color: var(--gray);
        }

        .alert {
            padding: 13px 16px;
            border-radius: 7px;
            margin-bottom: 18px;
        }

        .alert-success {
            background: #DCFCE7;
            color: #166534;
        }

        .alert-error {
            background: #FEE2E2;
            color: #991B1B;
        }

        .content-card {
            background: white;
            border: 1px solid var(--light-gray);
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, 0.05);
        }

        .app-footer {
            text-align: center;
            padding: 25px 15px;
            color: var(--gray);
            font-size: 13px;
        }

        @media (max-width: 600px) {
            .app-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .header-actions {
                gap: 12px;
            }

            .page-heading h1 {
                font-size: 23px;
            }

            .content-card {
                padding: 18px;
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
                <span>{{ auth()->user()->name }}</span>

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
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </main>

    <footer class="app-footer">
        SPAMAST Supreme Student Government Event Management System
        &copy; {{ date('Y') }}
    </footer>

    @stack('scripts')
</body>
</html>
