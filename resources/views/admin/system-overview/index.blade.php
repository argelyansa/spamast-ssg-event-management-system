@extends('layouts.app')

@section('title', 'System Overview')

@section('content')
<div class="system-overview">

    <div class="overview-topbar">
        <a href="{{ route('admin.dashboard') }}" class="back-link">← Back to Dashboard</a>
        <span class="overview-label">ADMINISTRATION / SYSTEM OVERVIEW</span>
    </div>

    <section class="overview-hero">
        <div>
            <span class="hero-label">SYSTEM MONITORING</span>
            <h1>System Overview</h1>
            <p>A consolidated view of accounts, academic records, events, attendance, fines, and announcements.</p>
            <span class="live-indicator"><span></span> Live database statistics</span>
        </div>
        <div class="hero-icon">📊</div>
    </section>

    <section class="overview-section">
        <div class="overview-heading">
            <h2>Accounts & Academic Structure</h2>
            <p>Registered users and academic records currently stored in the system.</p>
        </div>

        <div class="overview-grid">
            <div class="overview-card">
                <div class="overview-icon blue">👥</div>
                <div class="card-label">Total Users</div>
                <div class="card-value">{{ number_format($stats['total_users']) }}</div>
                <div class="card-note">All registered accounts</div>
            </div>

            <div class="overview-card">
                <div class="overview-icon purple">🎓</div>
                <div class="card-label">Students</div>
                <div class="card-value">{{ number_format($stats['total_students']) }}</div>
                <div class="card-note">Registered student records</div>
            </div>

            <div class="overview-card">
                <div class="overview-icon green">🏛️</div>
                <div class="card-label">Institutes</div>
                <div class="card-value">{{ number_format($stats['total_institutes']) }}</div>
                <div class="card-note">Academic institutes</div>
            </div>

            <div class="overview-card">
                <div class="overview-icon orange">📚</div>
                <div class="card-label">Programs</div>
                <div class="card-value">{{ number_format($stats['total_programs']) }}</div>
                <div class="card-note">Academic programs</div>
            </div>
        </div>
    </section>

    <section class="overview-section">
        <div class="overview-heading">
            <h2>Users by Role</h2>
            <p>Distribution of registered accounts according to assigned system roles.</p>
        </div>

        <div class="role-panel">
            @forelse ($roleStats as $role)
                <div class="role-row">
                    <div class="role-name">
                        <span class="role-dot"></span>
                        {{ $role->name }}
                    </div>
                    <strong>{{ number_format($role->users_count) }}</strong>
                </div>
            @empty
                <p class="empty-message">No role records found.</p>
            @endforelse
        </div>
    </section>

    <section class="overview-section">
        <div class="overview-heading">
            <h2>Events & Attendance</h2>
            <p>Overview of event scheduling, sessions, and recorded attendance activity.</p>
        </div>

        <div class="overview-grid">
            <div class="overview-card">
                <div class="overview-icon blue">📅</div>
                <div class="card-label">Total Events</div>
                <div class="card-value">{{ number_format($stats['total_events']) }}</div>
                <div class="card-note">All recorded events</div>
            </div>

            <div class="overview-card">
                <div class="overview-icon green">🗓️</div>
                <div class="card-label">Upcoming Events</div>
                <div class="card-value">{{ number_format($stats['upcoming_events']) }}</div>
                <div class="card-note">Today and future dates</div>
            </div>

            <div class="overview-card">
                <div class="overview-icon orange">📁</div>
                <div class="card-label">Past Events</div>
                <div class="card-value">{{ number_format($stats['past_events']) }}</div>
                <div class="card-note">Events before today</div>
            </div>

            <div class="overview-card">
                <div class="overview-icon purple">🕒</div>
                <div class="card-label">Event Sessions</div>
                <div class="card-value">{{ number_format($stats['total_sessions']) }}</div>
                <div class="card-note">All scheduled sessions</div>
            </div>

            <div class="overview-card">
                <div class="overview-icon blue">📝</div>
                <div class="card-label">Attendance Records</div>
                <div class="card-value">{{ number_format($stats['total_attendance']) }}</div>
                <div class="card-note">Recorded attendance entries</div>
            </div>

            <div class="overview-card">
                <div class="overview-icon green">↪️</div>
                <div class="card-label">Checked In</div>
                <div class="card-value">{{ number_format($stats['checked_in']) }}</div>
                <div class="card-note">Records with check-in time</div>
            </div>

            <div class="overview-card">
                <div class="overview-icon purple">✓</div>
                <div class="card-label">Checked Out</div>
                <div class="card-value">{{ number_format($stats['checked_out']) }}</div>
                <div class="card-note">Records with check-out time</div>
            </div>
        </div>
    </section>

    <section class="overview-section">
        <div class="overview-heading">
            <h2>Fines & Sanctions</h2>
            <p>Summary of penalties and their current payment or service status.</p>
        </div>

        <div class="overview-grid">
            <div class="overview-card">
                <div class="overview-icon blue">📋</div>
                <div class="card-label">Total Penalties</div>
                <div class="card-value">{{ number_format($stats['total_penalties']) }}</div>
                <div class="card-note">All recorded penalties</div>
            </div>

            <div class="overview-card">
                <div class="overview-icon red">⚠️</div>
                <div class="card-label">Unpaid</div>
                <div class="card-value">{{ number_format($stats['unpaid_penalties']) }}</div>
                <div class="card-note">Pending payment</div>
            </div>

            <div class="overview-card">
                <div class="overview-icon green">💰</div>
                <div class="card-label">Paid</div>
                <div class="card-value">{{ number_format($stats['paid_penalties']) }}</div>
                <div class="card-note">Paid penalties</div>
            </div>

            <div class="overview-card">
                <div class="overview-icon purple">✓</div>
                <div class="card-label">Served</div>
                <div class="card-value">{{ number_format($stats['served_penalties']) }}</div>
                <div class="card-note">Served sanctions</div>
            </div>
        </div>

        <div class="amount-panel">
            <div>
                <span class="amount-label">TOTAL UNPAID AMOUNT</span>
                <p>Outstanding penalty balance</p>
            </div>
            <strong>₱{{ number_format((float) $stats['unpaid_amount'], 2) }}</strong>
        </div>
    </section>

    <section class="overview-section">
        <div class="overview-heading">
            <h2>Announcements</h2>
            <p>Summary of published announcements and uploaded attachments.</p>
        </div>

        <div class="overview-grid overview-grid-two">
            <div class="overview-card">
                <div class="overview-icon blue">📢</div>
                <div class="card-label">Total Announcements</div>
                <div class="card-value">{{ number_format($stats['total_announcements']) }}</div>
                <div class="card-note">All published announcements</div>
            </div>

            <div class="overview-card">
                <div class="overview-icon orange">📎</div>
                <div class="card-label">With Attachments</div>
                <div class="card-value">{{ number_format($stats['announcements_with_attachments']) }}</div>
                <div class="card-note">Announcements with uploaded files</div>
            </div>
        </div>
    </section>

    <div class="overview-footer">
        <span>SPAMAST SSG Event Management System</span>
        <span>Administrative view · Read only</span>
    </div>

</div>
@endsection

@push('styles')
<style>
    .system-overview {
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 35px;
    }

    .overview-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin: 8px 0 22px;
    }

    .back-link {
        color: #2563EB;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .overview-label {
        color: #64748B;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .overview-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 31px 34px;
        margin-bottom: 32px;
        border-radius: 16px;
        background: linear-gradient(120deg, #0B1120, #1E3A5F);
        color: #fff;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.12);
    }

    .hero-label {
        color: #93C5FD;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
    }

    .overview-hero h1 {
        color: #fff;
        font-size: 28px;
        font-weight: 750;
        margin: 8px 0;
    }

    .overview-hero p {
        color: #CBD5E1;
        font-size: 14px;
        line-height: 1.6;
        max-width: 680px;
        margin: 0 0 17px;
    }

    .live-indicator {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #D1FAE5;
        font-size: 12px;
        font-weight: 600;
    }

    .live-indicator span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #34D399;
    }

    .hero-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 76px;
        height: 76px;
        flex-shrink: 0;
        border-radius: 18px;
        background: rgba(255,255,255,0.10);
        font-size: 39px;
    }

    .overview-section {
        margin-bottom: 34px;
    }

    .overview-heading {
        margin-bottom: 16px;
    }

    .overview-heading h2 {
        color: #0B1120;
        font-size: 20px;
        font-weight: 750;
        margin: 0 0 5px;
    }

    .overview-heading p {
        color: #64748B;
        font-size: 13px;
        margin: 0;
    }

    .overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 15px;
    }

    .overview-grid-two {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .overview-card {
        min-width: 0;
        padding: 19px;
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 13px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
    }

    .overview-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        margin-bottom: 14px;
        border-radius: 11px;
        font-size: 20px;
    }

    .overview-icon.blue { background: #EFF6FF; }
    .overview-icon.purple { background: #F3E8FF; }
    .overview-icon.green { background: #DCFCE7; }
    .overview-icon.orange { background: #FFEDD5; }
    .overview-icon.red { background: #FEE2E2; }

    .card-label {
        color: #64748B;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .card-value {
        color: #0B1120;
        font-size: 27px;
        font-weight: 800;
        line-height: 1.2;
        overflow-wrap: anywhere;
    }

    .card-note {
        color: #94A3B8;
        font-size: 11px;
        margin-top: 7px;
    }

    .role-panel {
        padding: 5px 20px;
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 13px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
    }

    .role-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #F1F5F9;
    }

    .role-row:last-child {
        border-bottom: none;
    }

    .role-name {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #334155;
        font-size: 14px;
        font-weight: 600;
    }

    .role-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #3B82F6;
    }

    .role-row strong {
        color: #0B1120;
        font-size: 16px;
        font-weight: 750;
    }

    .empty-message {
        padding: 15px 0;
        color: #64748B;
        font-size: 13px;
    }

    .amount-panel {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
        padding: 22px 24px;
        margin-top: 16px;
        border: 1px solid #FECACA;
        border-radius: 13px;
        background: #FFF7F7;
    }

    .amount-label {
        color: #991B1B;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .amount-panel p {
        color: #64748B;
        font-size: 12px;
        margin: 5px 0 0;
    }

    .amount-panel strong {
        color: #B91C1C;
        font-size: 27px;
        font-weight: 800;
    }

    .overview-footer {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        padding-top: 18px;
        border-top: 1px solid #E2E8F0;
        color: #94A3B8;
        font-size: 11px;
    }

    @media (max-width: 600px) {
        .overview-hero {
            padding: 23px;
        }

        .overview-hero h1 {
            font-size: 23px;
        }

        .hero-icon {
            width: 55px;
            height: 55px;
            font-size: 29px;
        }

        .overview-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .overview-grid-two {
            grid-template-columns: 1fr;
        }

        .overview-card {
            padding: 14px;
        }

        .card-value {
            font-size: 23px;
        }

        .amount-panel {
            padding: 18px;
        }

        .amount-panel strong {
            font-size: 23px;
        }
    }

    @media (max-width: 360px) {
        .hero-icon {
            display: none;
        }

        .overview-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
