@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="admin-dashboard">

    @include('components.weather')

    <section class="admin-welcome">
        <div>
            <span class="welcome-label">ADMINISTRATOR PANEL</span>
            <h1>Welcome to the Admin Dashboard</h1>
            <p>Manage users, institutes, and academic programs in the SPAMAST SSG Event Management System.</p>
        </div>
        <div class="welcome-icon">⚙️</div>
    </section>

    <section class="admin-stats-section">
        <div class="section-heading">
            <h2>System Statistics</h2>
            <p>Current overview of registered accounts and academic structure.</p>
        </div>

        <div class="admin-stats-grid">
            @foreach ($stats['users_by_role'] as $role => $count)
                <div class="admin-stat-card">
                    <div class="stat-icon">👤</div>
                    <div>
                        <p>{{ $role }} Users</p>
                        <h3>{{ $count }}</h3>
                    </div>
                </div>
            @endforeach

            <div class="admin-stat-card">
                <div class="stat-icon">🏛️</div>
                <div>
                    <p>Institutes</p>
                    <h3>{{ $stats['institutes'] }}</h3>
                </div>
            </div>

            <div class="admin-stat-card">
                <div class="stat-icon">🎓</div>
                <div>
                    <p>Programs</p>
                    <h3>{{ $stats['programs'] }}</h3>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-management-section">
        <div class="section-heading">
            <h2>Management & Tools</h2>
            <p>Quick access to the system's administrative features.</p>
        </div>

        <div class="admin-management-grid">

            <a href="{{ route('admin.users.index') }}" class="admin-management-card">
                <div class="management-icon">👥</div>
                <h3>User Management</h3>
                <p>Manage Officer, Adviser, and Student accounts.</p>
                <span class="management-link">Manage Users <span>→</span></span>
            </a>

            <a href="{{ route('admin.institutes.index') }}" class="admin-management-card">
                <div class="management-icon">🏛️</div>
                <h3>Institute Management</h3>
                <p>Manage SPAMAST institutes and their information.</p>
                <span class="management-link">Manage Institutes <span>→</span></span>
            </a>

            <a href="{{ route('admin.programs.index') }}" class="admin-management-card">
                <div class="management-icon">🎓</div>
                <h3>Program Management</h3>
                <p>Manage academic programs under each institute.</p>
                <span class="management-link">Manage Programs <span>→</span></span>
            </a>

            <a href="{{ route('admin.events.index') }}" class="admin-management-card">
                <div class="management-icon">📅</div>
                <h3>Event Management</h3>
                <p>View and monitor all events and their attendance records.</p>
                <span class="management-link">View Events →</span>
            </a>

            <a href="{{ route('admin.system-overview') }}" class="admin-management-card">
                <div class="management-icon">📊</div>
                <h3>System Overview</h3>
                <p>View system statistics, account distribution, and system activity.</p>
                <span class="management-link">View Overview →</span>
            </a>

        </div>
    </section>

</div>
@endsection

@push('styles')
<style>
    .admin-dashboard {
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 30px;
    }

    .admin-welcome {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 30px 34px;
        margin: 26px 0 32px;
        border-radius: 16px;
        background: linear-gradient(120deg, var(--header-bg), var(--accent));
        color: #fff;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.12);
    }

    .admin-welcome h1 {
        color: #fff;
        font-size: 27px;
        font-weight: 700;
        margin: 8px 0;
    }

    .admin-welcome p {
        color: rgba(255,255,255,.85);
        margin: 0;
        line-height: 1.6;
        max-width: 650px;
    }

    .welcome-label {
        color: rgba(255,255,255,.85);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
    }

    .welcome-icon {
        font-size: 48px;
        background: rgba(255,255,255,0.10);
        border-radius: 16px;
        padding: 16px;
        flex-shrink: 0;
    }

    .admin-stats-section,
    .admin-management-section {
        margin-bottom: 34px;
    }

    .section-heading {
        margin-bottom: 17px;
    }

    .section-heading h2 {
        color: var(--text);
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .section-heading p {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .admin-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(155px, 1fr));
        gap: 15px;
    }

    .admin-stat-card {
        display: flex;
        align-items: center;
        gap: 13px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 13px;
        padding: 19px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
    }

    .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 43px;
        height: 43px;
        flex-shrink: 0;
        border-radius: 11px;
        background: var(--page-bg);
        font-size: 21px;
    }

    .admin-stat-card p {
        color: var(--muted);
        font-size: 12px;
        margin: 0 0 5px;
    }

    .admin-stat-card h3 {
        color: var(--text);
        font-size: 25px;
        font-weight: 750;
        margin: 0;
    }

    .admin-management-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(205px, 1fr));
        gap: 17px;
    }

    .admin-management-card {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        min-height: 205px;
        padding: 23px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 14px;
        text-decoration: none;
        color: var(--text);
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    a.admin-management-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 9px 22px rgba(15, 23, 42, 0.10);
    }

    .management-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
        margin-bottom: 16px;
        border-radius: 12px;
        background: var(--page-bg);
        font-size: 24px;
    }

    .admin-management-card h3 {
        color: var(--text);
        font-size: 17px;
        font-weight: 700;
        margin: 0 0 8px;
    }

    .admin-management-card p {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.55;
        margin: 0 0 17px;
    }

    .management-link {
        color: var(--accent);
        font-size: 13px;
        font-weight: 700;
        margin-top: auto;
    }

    .management-link span {
        margin-left: 3px;
    }

    .admin-disabled-card {
        background: var(--page-bg);
        opacity: 0.78;
        cursor: default;
    }

    .admin-disabled-card .management-link {
        color: var(--muted);
    }

    @media (max-width: 600px) {
        .admin-welcome {
            padding: 23px;
            margin-top: 20px;
        }

        .admin-welcome h1 {
            font-size: 22px;
        }

        .welcome-icon {
            font-size: 32px;
            padding: 11px;
        }

        .admin-stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .admin-management-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 380px) {
        .welcome-icon {
            display: none;
        }

        .admin-stat-card {
            padding: 13px;
            gap: 9px;
        }
    }
</style>
@endpush
