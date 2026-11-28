@extends('layouts.app')

@section('title', 'Officer Dashboard')

@section('content')

@include('components.weather')

<div class="officer-dashboard">

    <section class="officer-welcome">
        <div class="welcome-content">
            <span class="welcome-label">SPAMAST SUPREME STUDENT GOVERNMENT</span>
            <h1>Welcome back, {{ auth()->user()->name }}!</h1>
            <p>Manage student activities, events, attendance, and campus updates.</p>
        </div>
        <div class="welcome-decoration">SSG</div>
    </section>

    <section class="officer-stats">
        <article class="officer-stat">
            <div class="stat-icon">♙</div>
            <div>
                <p>Total Students</p>
                <h2>{{ $stats['students'] }}</h2>
            </div>
        </article>

        <article class="officer-stat">
            <div class="stat-icon">▦</div>
            <div>
                <p>Upcoming Events</p>
                <h2>{{ $stats['upcoming_events'] }}</h2>
            </div>
        </article>

        <article class="officer-stat">
            <div class="stat-icon">₱</div>
            <div>
                <p>Unpaid Fines</p>
                <h2>{{ $stats['unpaid_fines'] }}</h2>
            </div>
        </article>

        <article class="officer-stat">
            <div class="stat-icon">▤</div>
            <div>
                <p>Announcements</p>
                <h2>{{ $stats['announcements'] }}</h2>
            </div>
        </article>
    </section>

    <section class="officer-section">
        <div class="officer-section-heading">
            <div>
                <h2>Quick Access</h2>
                <p>Manage your SSG operations and student services.</p>
            </div>
        </div>

        <div class="officer-links">

            <a href="{{ route('officer.students.index') }}" class="officer-link">
                <span class="link-icon">♙</span>
                <span class="link-content">
                    <strong>Student Management</strong>
                    <small>View and manage student records.</small>
                    <span class="link-action">Open Student Management →</span>
                </span>
            </a>

            <a href="{{ route('officer.events.index') }}" class="officer-link">
                <span class="link-icon">▦</span>
                <span class="link-content">
                    <strong>Event Management</strong>
                    <small>Create, update, and manage SSG events.</small>
                    <span class="link-action">Open Event Management →</span>
                </span>
            </a>

            <a href="{{ route('officer.attendance.events') }}" class="officer-link">
                <span class="link-icon">✓</span>
                <span class="link-content">
                    <strong>Attendance Monitoring</strong>
                    <small>Monitor student event attendance and check-ins.</small>
                    <span class="link-action">Monitor Attendance →</span>
                </span>
            </a>

            <a href="{{ route('officer.penalties.events') }}" class="officer-link">
                <span class="link-icon">₱</span>
                <span class="link-content">
                    <strong>Fines and Sanctions</strong>
                    <small>Manage attendance-related fines and sanctions.</small>
                    <span class="link-action">Manage Penalties →</span>
                </span>
            </a>

            <a href="{{ route('announcements.index') }}" class="officer-link">
                <span class="link-icon">▤</span>
                <span class="link-content">
                    <strong>Announcements</strong>
                    <small>Publish and manage important SSG announcements.</small>
                    <span class="link-action">Open Announcements →</span>
                </span>
            </a>

            <a href="{{ route('reports.index') }}" class="officer-link">
                <span class="link-icon">▧</span>
                <span class="link-content">
                    <strong>Reports</strong>
                    <small>View event and attendance reports.</small>
                    <span class="link-action">Open Reports →</span>
                </span>
            </a>

        </div>
    </section>

    <section class="officer-section latest-section">
        <div class="officer-section-heading">
            <div>
                <h2>Latest Announcements</h2>
                <p>Stay informed about important SSG updates.</p>
            </div>

            <a href="{{ route('announcements.index') }}" class="all-announcements">
                View All →
            </a>
        </div>

        <div class="latest-list">
            @forelse ($latestAnnouncements as $announcement)
                <article class="latest-item">
                    <div class="latest-item-top">
                        <span class="latest-tag">SSG UPDATE</span>
                        <span class="latest-date">
                            {{ $announcement->created_at->format('M d, Y') }}
                        </span>
                    </div>

                    <h3>
                        <a href="{{ route('announcements.show', $announcement) }}">
                            {{ $announcement->title }}
                        </a>
                    </h3>

                    <p class="latest-meta">
                        Posted by {{ $announcement->user->name ?? 'Unknown' }}
                        ·
                        {{ $announcement->created_at->format('h:i A') }}
                    </p>

                    <p class="latest-preview">
                        {{ \Illuminate\Support\Str::limit($announcement->body, 150) }}
                    </p>

                    <a href="{{ route('announcements.show', $announcement) }}"
                       class="latest-read">
                        Read Announcement →
                    </a>
                </article>
            @empty
                <div class="latest-empty">
                    <div class="empty-icon">▤</div>
                    <h3>No Announcements Yet</h3>
                    <p>There are currently no SSG announcements. Check back later for updates.</p>
                    <a href="{{ route('announcements.index') }}">Visit Announcements →</a>
                </div>
            @endforelse
        </div>
    </section>

</div>

@endsection

@push('styles')
<style>
.officer-dashboard {
    --officer-navy: var(--text);
    --officer-blue: var(--accent);
    --officer-muted: var(--muted);
    --officer-border: var(--border);
    color: var(--text);
}

.officer-welcome {
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 185px;
    padding: 32px;
    margin-bottom: 25px;
    border-radius: 18px;
    color: white;
    background: linear-gradient(120deg, var(--header-bg), var(--accent));
}

.officer-dashboard .welcome-content {
    position: relative;
    z-index: 1;
}

.officer-dashboard .welcome-label {
    display: inline-block;
    margin-bottom: 12px;
    color: #BFDBFE;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.4px;
}

.officer-welcome h1 {
    margin: 0 0 10px;
    color: white;
    font-size: clamp(23px, 4vw, 32px);
    font-weight: 750;
}

.officer-welcome p {
    margin: 0;
    color: #DBEAFE;
    line-height: 1.6;
}

.officer-dashboard .welcome-decoration {
    color: rgba(255,255,255,.10);
    font-size: clamp(65px, 12vw, 120px);
    font-weight: 900;
    letter-spacing: -8px;
    padding-right: 15px;
}

.officer-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 35px;
}

.officer-stat {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
    padding: 20px;
    background: var(--surface);
    border: 1px solid var(--officer-border);
    border-radius: 14px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
}

.officer-dashboard .stat-icon,
.officer-dashboard .link-icon {
    display: flex;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    color: var(--officer-blue);
    background: var(--page-bg);
    border-radius: 12px;
    font-size: 24px;
    font-weight: 700;
}

.officer-dashboard .stat-icon {
    width: 48px;
    height: 48px;
}

.officer-stat p {
    margin: 0 0 5px;
    color: var(--officer-muted);
    font-size: 13px;
}

.officer-stat h2 {
    margin: 0;
    color: var(--officer-navy);
    font-size: 27px;
    font-weight: 750;
}

.officer-section {
    margin-bottom: 35px;
}

.officer-section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 18px;
}

.officer-section-heading h2 {
    margin: 0 0 5px;
    color: var(--officer-navy);
    font-size: 21px;
}

.officer-section-heading p {
    margin: 0;
    color: var(--officer-muted);
    font-size: 14px;
}

.officer-links {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.officer-link {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    min-width: 0;
    padding: 21px;
    color: inherit;
    text-decoration: none;
    background: var(--surface);
    border: 1px solid var(--officer-border);
    border-radius: 14px;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}

.officer-link:hover {
    transform: translateY(-3px);
    border-color: var(--accent);
    box-shadow: 0 8px 20px rgba(15, 23, 42, .07);
}

.officer-link:focus-visible {
    outline: 3px solid var(--accent);
    outline-offset: 3px;
}

.officer-dashboard .link-icon {
    width: 48px;
    height: 48px;
}

.officer-dashboard .link-content {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.officer-dashboard .link-content strong {
    margin-bottom: 6px;
    color: var(--officer-navy);
    font-size: 16px;
}

.officer-dashboard .link-content small {
    color: var(--officer-muted);
    font-size: 13px;
    line-height: 1.5;
}

.officer-dashboard .link-action {
    margin-top: 12px;
    color: var(--officer-blue);
    font-size: 13px;
    font-weight: 700;
}

.officer-dashboard .all-announcements,
.officer-dashboard .latest-read {
    color: var(--officer-blue);
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
}

.officer-dashboard .latest-list {
    display: grid;
    gap: 15px;
}

.officer-dashboard .latest-item {
    padding: 22px;
    background: var(--surface);
    border: 1px solid var(--officer-border);
    border-radius: 14px;
}

.officer-dashboard .latest-item-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 13px;
}

.officer-dashboard .latest-tag {
    padding: 5px 9px;
    color: var(--accent);
    background: var(--page-bg);
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .5px;
}

.officer-dashboard .latest-date,
.officer-dashboard .latest-meta {
    color: var(--officer-muted);
    font-size: 12px;
}

.officer-dashboard .latest-item h3 {
    margin: 0 0 8px;
    font-size: 18px;
}

.officer-dashboard .latest-item h3 a {
    color: var(--officer-navy);
    text-decoration: none;
}

.officer-dashboard .latest-meta {
    margin-bottom: 12px;
}

.officer-dashboard .latest-preview {
    margin-bottom: 14px;
    color: var(--muted);
    font-size: 14px;
    line-height: 1.7;
}

.officer-dashboard .latest-empty {
    padding: 35px 20px;
    text-align: center;
    background: var(--surface);
    border: 1px dashed var(--border);
    border-radius: 14px;
}

.officer-dashboard .empty-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    margin-bottom: 12px;
    color: var(--officer-blue);
    background: var(--page-bg);
    border-radius: 50%;
    font-size: 25px;
}

.officer-dashboard .latest-empty h3 {
    margin: 0 0 8px;
    color: var(--officer-navy);
}

.officer-dashboard .latest-empty p {
    margin: 0 0 15px;
    color: var(--officer-muted);
    font-size: 14px;
}

.officer-dashboard .latest-empty a {
    color: var(--officer-blue);
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
}

@media (max-width: 900px) {
    .officer-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 650px) {
    .officer-welcome {
        min-height: 155px;
        padding: 24px 20px;
    }

    .officer-dashboard .welcome-decoration {
        display: none;
    }

    .officer-links {
        grid-template-columns: 1fr;
    }

    .officer-section-heading {
        align-items: flex-start;
    }
}

@media (max-width: 420px) {
    .officer-stats {
        grid-template-columns: 1fr;
    }

    .officer-section-heading,
    .officer-dashboard .latest-item-top {
        align-items: flex-start;
        flex-direction: column;
    }
}
</style>
@endpush
