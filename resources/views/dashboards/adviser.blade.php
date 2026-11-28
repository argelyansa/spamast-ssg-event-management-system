@extends('layouts.app')

@section('title', 'Adviser Dashboard')

@section('content')

<div class="adviser-dashboard">

    @include('components.weather')

    {{-- Welcome Banner --}}
    <section class="adviser-welcome">
        <div>
            <span class="welcome-label">ADVISER PORTAL</span>
            <h1>Welcome, {{ auth()->user()->name }}!</h1>
            <p>Manage events, monitor student participation, and review SSG activities.</p>
        </div>
        <div class="welcome-icon">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
    </section>

    {{-- Statistics --}}
    <section class="adviser-stats">
        <div class="adviser-stat-card">
            <div class="stat-icon events-icon">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div>
                <p>Upcoming Events</p>
                <h2>{{ $stats['upcoming_events'] }}</h2>
            </div>
        </div>

        <div class="adviser-stat-card">
            <div class="stat-icon attendance-icon">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div>
                <p>Attendance Records</p>
                <h2>{{ $stats['attendance_records'] }}</h2>
            </div>
        </div>

        <div class="adviser-stat-card">
            <div class="stat-icon fines-icon">
                <i class="bi bi-receipt"></i>
            </div>
            <div>
                <p>Unpaid Fines</p>
                <h2>{{ $stats['unpaid_fines'] }}</h2>
            </div>
        </div>
    </section>

    {{-- Quick Access --}}
    <section class="adviser-section">
        <div class="adviser-section-heading">
            <div>
                <h2>Quick Access</h2>
                <p>Choose a section to manage or review.</p>
            </div>
        </div>

        <div class="adviser-quick-grid">

            <a href="{{ route('adviser.events.index') }}" class="adviser-quick-card">
                <div class="quick-icon blue">
                    <i class="bi bi-calendar2-week"></i>
                </div>
                <h3>Events</h3>
                <p>View SSG events and scheduled activities.</p>
                <span>View Events <i class="bi bi-arrow-right"></i></span>
            </a>

            <a href="{{ route('announcements.index') }}" class="adviser-quick-card">
                <div class="quick-icon purple">
                    <i class="bi bi-megaphone"></i>
                </div>
                <h3>Announcements</h3>
                <p>Read important SSG announcements.</p>
                <span>View Announcements <i class="bi bi-arrow-right"></i></span>
            </a>

            <a href="{{ route('adviser.attendance.events') }}" class="adviser-quick-card">
                <div class="quick-icon green">
                    <i class="bi bi-clipboard2-check"></i>
                </div>
                <h3>Attendance Records</h3>
                <p>Review student attendance by institute and program.</p>
                <span>View Attendance <i class="bi bi-arrow-right"></i></span>
            </a>

            <a href="{{ route('adviser.penalties.events') }}" class="adviser-quick-card">
                <div class="quick-icon orange">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <h3>Fines and Sanctions</h3>
                <p>Review student fines and sanctions.</p>
                <span>View Records <i class="bi bi-arrow-right"></i></span>
            </a>

            <a href="{{ route('reports.index') }}" class="adviser-quick-card">
                <div class="quick-icon teal">
                    <i class="bi bi-bar-chart-line"></i>
                </div>
                <h3>Reports</h3>
                <p>View event and attendance reports.</p>
                <span>Open Reports <i class="bi bi-arrow-right"></i></span>
            </a>

        </div>
    </section>

    {{-- Latest Announcements --}}
    <section class="adviser-section latest-section">
        <div class="adviser-section-heading">
            <div>
                <h2>Latest Announcements</h2>
                <p>Stay updated with the latest SSG announcements.</p>
            </div>

            <a href="{{ route('announcements.index') }}" class="all-announcements">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @forelse ($latestAnnouncements as $announcement)
            <article class="adviser-announcement">
                <div class="announcement-icon">
                    <i class="bi bi-megaphone"></i>
                </div>

                <div class="announcement-content">
                    <h3>
                        <a href="{{ route('announcements.show', $announcement) }}">
                            {{ $announcement->title }}
                        </a>
                    </h3>

                    <p class="announcement-meta">
                        <i class="bi bi-person"></i>
                        {{ $announcement->user->name ?? 'Unknown' }}
                        <span>·</span>
                        <i class="bi bi-calendar3"></i>
                        {{ $announcement->created_at->format('F d, Y h:i A') }}
                    </p>

                    <p class="announcement-body">
                        {{ \Illuminate\Support\Str::limit($announcement->body, 150) }}
                    </p>

                    <a href="{{ route('announcements.show', $announcement) }}"
                       class="announcement-read-more">
                        Read Announcement <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </article>
        @empty
            <div class="adviser-empty-state">
                <div class="empty-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h3>No Announcements Yet</h3>
                <p>There are currently no published announcements.</p>
            </div>
        @endforelse
    </section>

</div>

@endsection

@push('styles')
<style>
    .adviser-dashboard {
        --adviser-navy: var(--text);
        --adviser-blue: var(--accent);
        --adviser-muted: var(--muted);
        --adviser-border: var(--border);
        padding-bottom: 30px;
    }

    /* Welcome */
    .adviser-welcome {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
        padding: 32px 36px;
        margin: 24px 0;
        border-radius: 18px;
        color: #fff;
        background: linear-gradient(120deg, var(--header-bg), var(--accent));
        box-shadow: 0 8px 24px rgba(20, 43, 74, .12);
    }

    .welcome-label {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.6px;
        color: #bfdbfe;
        margin-bottom: 10px;
    }

    .adviser-welcome h1 {
        font-size: clamp(23px, 3vw, 30px);
        font-weight: 750;
        margin: 0 0 9px;
        color: #fff;
    }

    .adviser-welcome p {
        margin: 0;
        color: #dbeafe;
        font-size: 14px;
        line-height: 1.6;
    }

    .welcome-icon {
        width: 76px;
        height: 76px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 20px;
        background: rgba(255, 255, 255, .12);
        color: #fff;
        font-size: 38px;
    }

    /* Statistics */
    .adviser-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 38px;
    }

    .adviser-stat-card {
        display: flex;
        align-items: center;
        gap: 17px;
        padding: 23px;
        min-width: 0;
        background: var(--surface);
        border: 1px solid var(--adviser-border);
        border-radius: 15px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
    }

    .stat-icon {
        width: 53px;
        height: 53px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 14px;
        font-size: 23px;
    }

    .events-icon {
        color: var(--accent);
        background: var(--page-bg);
    }

    .attendance-icon {
        color: #059669;
        background: #ecfdf5;
    }

    .fines-icon {
        color: #d97706;
        background: #fffbeb;
    }

    .adviser-stat-card p {
        color: var(--adviser-muted);
        font-size: 13px;
        font-weight: 600;
        margin: 0 0 5px;
    }

    .adviser-stat-card h2 {
        color: var(--adviser-navy);
        font-size: 27px;
        font-weight: 750;
        margin: 0;
    }

    /* Sections */
    .adviser-section {
        margin-bottom: 38px;
    }

    .adviser-section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 18px;
    }

    .adviser-section-heading h2 {
        color: var(--text);
        font-size: 20px;
        font-weight: 750;
        margin: 0 0 5px;
    }

    .adviser-section-heading p {
        color: var(--adviser-muted);
        font-size: 13px;
        margin: 0;
    }

    /* Quick Access */
    .adviser-quick-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 17px;
    }

    .adviser-quick-card {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        min-height: 205px;
        padding: 22px;
        background: var(--surface);
        border: 1px solid var(--adviser-border);
        border-radius: 15px;
        text-decoration: none;
        color: inherit;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .adviser-quick-card:hover {
        transform: translateY(-3px);
        border-color: var(--accent);
        box-shadow: 0 9px 22px rgba(15, 23, 42, .08);
        text-decoration: none;
        color: inherit;
    }

    .quick-icon {
        width: 45px;
        height: 45px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        font-size: 21px;
        margin-bottom: 17px;
    }

    .quick-icon.blue {
        color: var(--accent);
        background: var(--page-bg);
    }

    .quick-icon.purple {
        color: #7c3aed;
        background: #f5f3ff;
    }

    .quick-icon.green {
        color: #059669;
        background: #ecfdf5;
    }

    .quick-icon.orange {
        color: #d97706;
        background: #fffbeb;
    }

    .quick-icon.teal {
        color: #0d9488;
        background: #f0fdfa;
    }

    .adviser-quick-card h3 {
        color: var(--text);
        font-size: 15px;
        font-weight: 750;
        margin: 0 0 7px;
    }

    .adviser-quick-card p {
        color: var(--adviser-muted);
        font-size: 13px;
        line-height: 1.55;
        margin: 0 0 15px;
    }

    .adviser-quick-card > span {
        color: var(--adviser-blue);
        font-size: 12px;
        font-weight: 700;
        margin-top: auto;
    }

    /* Announcements */
    .all-announcements {
        color: var(--adviser-blue);
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .all-announcements:hover,
    .announcement-read-more:hover {
        text-decoration: underline;
    }

    .adviser-announcement {
        display: flex;
        gap: 17px;
        padding: 22px;
        margin-bottom: 14px;
        background: var(--surface);
        border: 1px solid var(--adviser-border);
        border-radius: 15px;
    }

    .announcement-icon {
        width: 43px;
        height: 43px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        color: var(--accent);
        background: var(--page-bg);
        border-radius: 12px;
        font-size: 19px;
    }

    .announcement-content {
        min-width: 0;
    }

    .announcement-content h3 {
        font-size: 16px;
        font-weight: 750;
        margin: 0 0 7px;
    }

    .announcement-content h3 a {
        color: var(--text);
        text-decoration: none;
    }

    .announcement-content h3 a:hover {
        color: var(--adviser-blue);
    }

    .announcement-meta {
        color: #94a3b8;
        font-size: 11px;
        margin-bottom: 10px;
    }

    .announcement-body {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.65;
        margin-bottom: 10px;
    }

    .announcement-read-more {
        color: var(--adviser-blue);
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
    }

    .adviser-empty-state {
        padding: 38px 20px;
        text-align: center;
        background: var(--surface);
        border: 1px dashed var(--border);
        border-radius: 15px;
    }

    .empty-icon {
        width: 52px;
        height: 52px;
        display: grid;
        place-items: center;
        margin: 0 auto 12px;
        border-radius: 50%;
        color: var(--muted);
        background: var(--page-bg);
        font-size: 23px;
    }

    .adviser-empty-state h3 {
        color: var(--text);
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .adviser-empty-state p {
        color: var(--adviser-muted);
        font-size: 13px;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .adviser-quick-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 650px) {
        .adviser-welcome {
            padding: 25px 22px;
        }

        .welcome-icon {
            width: 55px;
            height: 55px;
            font-size: 27px;
        }

        .adviser-stats {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .adviser-quick-grid {
            grid-template-columns: 1fr;
        }

        .adviser-quick-card {
            min-height: 175px;
        }
    }

    @media (max-width: 420px) {
        .welcome-icon {
            display: none;
        }

        .adviser-section-heading {
            align-items: flex-start;
        }
    }
</style>
@endpush
