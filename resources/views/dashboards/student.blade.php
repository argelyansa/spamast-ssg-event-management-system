
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | SPAMAST SSG</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #0B1120;
            color: #F8FAFC;
            min-height: 100vh;
        }

        header {
            background: #111C32;
            padding: 22px 40px;
            border-bottom: 1px solid #1E293B;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            color: #3B82F6;
            font-size: 22px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-actions a,
        .logout button {
            background: #3B82F6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .header-actions a {
            background: #334155;
        }

        .header-actions a:hover {
            background: #475569;
        }

        .logout button:hover {
            background: #2563EB;
        }

        main {
            padding: 40px;
        }

        .welcome {
            margin-bottom: 30px;
        }

        .welcome h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .welcome p {
            color: #94A3B8;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .dashboard-card {
            background: #111C32;
            border: 1px solid #1E293B;
            border-radius: 12px;
            padding: 25px;
        }

        .dashboard-card h3 {
            color: #3B82F6;
            margin-bottom: 12px;
        }

        .dashboard-card p {
            color: #CBD5E1;
            line-height: 1.5;
        }

        .profile-card {
            display: block;
            text-decoration: none;
            color: inherit;
            transition: border-color 0.2s;
        }

        .profile-card:hover {
            border-color: #3B82F6;
        }

        @media (max-width: 600px) {
            header, main {
                padding: 20px;
            }

            header {
                flex-wrap: wrap;
                gap: 15px;
            }

            .header-actions {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>

    <header>
        <h1>SPAMAST SSG</h1>

        <div class="header-actions">
            <a href="{{ route('profile.edit') }}">My Profile</a>

            <form class="logout" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </header>

    <main>
        <section class="welcome">
            <h2>Welcome, {{ auth()->user()->name }}!</h2>
            <p>Student Dashboard — Event Management System</p>
        </section>

        <section class="dashboard-grid">

            <div class="dashboard-card">
                <h3>Upcoming Events</h3>
                <p>View upcoming SSG events and activities.</p>
            </div>

            <div class="dashboard-card">
                <h3>Announcements</h3>
                <p>Read the latest SSG announcements.</p>
            </div>

            <div class="dashboard-card">
                <h3>Attendance</h3>
                <p>Check your event attendance records.</p>
            </div>

            <div class="dashboard-card">
                <h3>Fines and Sanctions</h3>
                <p>View your attendance-related fines and sanctions.</p>
            </div>

            <a href="{{ route('profile.edit') }}" class="dashboard-card profile-card">
                <h3>Profile</h3>
                <p>View and update your personal information.</p>
            </a>

        </section>
    </main>

</body>
</html>
