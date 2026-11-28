@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')

@include('components.weather')

<div class="student-dashboard">

    <section class="student-welcome">
        <div class="welcome-content">
            <span class="welcome-label">SPAMAST SUPREME STUDENT GOVERNMENT</span>
            <h1>Welcome back, {{ auth()->user()->name }}!</h1>
            <p>Your student portal for events, attendance, and campus updates.</p>
        </div>
        <div class="welcome-decoration">SSG</div>
    </section>

    <section class="student-stats">
        <article class="student-stat">
            <div class="stat-icon">▦</div>
            <div>
                <p>Upcoming Events</p>
                <h2>{{ $stats['upcoming_events'] }}</h2>
            </div>
        </article>

        <article class="student-stat">
            <div class="stat-icon">✓</div>
            <div>
                <p>Attendance Records</p>
                <h2>{{ $stats['attendance_records'] }}</h2>
            </div>
        </article>
    </section>

    <section class="student-section">
        <div class="student-section-heading">
            <div>
                <h2>Quick Access</h2>
                <p>Explore your student services and activities.</p>
            </div>
        </div>

        <div class="student-links">

            <a href="{{ route('student.events.index') }}" class="student-link">
                <span class="link-icon">▦</span>
                <span class="link-content">
                    <strong>Upcoming Events</strong>
                    <small>Discover SSG events and activities.</small>
                    <span class="link-action">Explore Events →</span>
                </span>
            </a>

            <a href="{{ route('announcements.index') }}" class="student-link">
                <span class="link-icon">▤</span>
                <span class="link-content">
                    <strong>Announcements</strong>
                    <small>Read the latest SSG updates.</small>
                    <span class="link-action">View Announcements →</span>
                </span>
            </a>

            <a href="{{ route('student.attendance.index') }}" class="student-link">
                <span class="link-icon">✓</span>
                <span class="link-content">
                    <strong>Attendance</strong>
                    <small>Review your event attendance records.</small>
                    <span class="link-action">View Attendance →</span>
                </span>
            </a>

            <a href="{{ route('student.penalties.index') }}" class="student-link">
                <span class="link-icon">₱</span>
                <span class="link-content">
                    <strong>Fines and Sanctions</strong>
                    <small>Review your fines and sanctions.</small>
                    <span class="link-action">View Records →</span>
                </span>
            </a>

            <a href="{{ route('profile.edit') }}" class="student-link">
                <span class="link-icon">♙</span>
                <span class="link-content">
                    <strong>My Profile</strong>
                    <small>View and update your information.</small>
                    <span class="link-action">Manage Profile →</span>
                </span>
            </a>

        </div>
    </section>

    <section class="student-section latest-section">
        <div class="student-section-heading">
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
.student-dashboard {
    --student-navy: var(--text);
    --student-blue: var(--accent);
    --student-muted: var(--muted);
    --student-border: var(--border);
    color: var(--text);
}

.student-welcome {
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

.welcome-content {
    position: relative;
    z-index: 1;
}

.welcome-label {
    display: inline-block;
    margin-bottom: 12px;
    color: #BFDBFE;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.4px;
}

.student-welcome h1 {
    margin: 0 0 10px;
    color: white;
    font-size: clamp(23px, 4vw, 32px);
    font-weight: 750;
}

.student-welcome p {
    margin: 0;
    color: #DBEAFE;
    line-height: 1.6;
}

.welcome-decoration {
    color: rgba(255,255,255,.10);
    font-size: clamp(65px, 12vw, 120px);
    font-weight: 900;
    letter-spacing: -8px;
    padding-right: 15px;
}

.student-stats {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 35px;
}

.student-stat {
    display: flex;
    align-items: center;
    gap: 17px;
    padding: 23px;
    background: var(--surface);
    border: 1px solid var(--student-border);
    border-radius: 14px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
}

.stat-icon, .link-icon {
    display: flex;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    color: var(--student-blue);
    background: var(--page-bg);
    border-radius: 12px;
    font-size: 25px;
    font-weight: 700;
}

.stat-icon {
    width: 53px;
    height: 53px;
}

.student-stat p {
    margin: 0 0 5px;
    color: var(--student-muted);
    font-size: 14px;
}

.student-stat h2 {
    margin: 0;
    color: var(--student-navy);
    font-size: 29px;
    font-weight: 750;
}

.student-section {
    margin-bottom: 35px;
}

.student-section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 18px;
}

.student-section-heading h2 {
    margin: 0 0 5px;
    color: var(--student-navy);
    font-size: 21px;
}

.student-section-heading p {
    margin: 0;
    color: var(--student-muted);
    font-size: 14px;
}

.student-links {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.student-link {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    min-width: 0;
    padding: 21px;
    color: inherit;
    text-decoration: none;
    background: var(--surface);
    border: 1px solid var(--student-border);
    border-radius: 14px;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}

.student-link:hover {
    transform: translateY(-3px);
    border-color: var(--accent);
    box-shadow: 0 8px 20px rgba(15, 23, 42, .07);
}

.student-link:focus-visible {
    outline: 3px solid var(--accent);
    outline-offset: 3px;
}

.link-icon {
    width: 48px;
    height: 48px;
}

.link-content {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.link-content strong {
    margin-bottom: 6px;
    color: var(--student-navy);
    font-size: 16px;
}

.link-content small {
    color: var(--student-muted);
    font-size: 13px;
    line-height: 1.5;
}

.link-action {
    margin-top: 12px;
    color: var(--student-blue);
    font-size: 13px;
    font-weight: 700;
}

.all-announcements, .latest-read {
    color: var(--student-blue);
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
}

.latest-list {
    display: grid;
    gap: 15px;
}

.latest-item {
    padding: 22px;
    background: var(--surface);
    border: 1px solid var(--student-border);
    border-radius: 14px;
}

.latest-item-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 13px;
}

.latest-tag {
    padding: 5px 9px;
    color: var(--accent);
    background: var(--page-bg);
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .5px;
}

.latest-date, .latest-meta {
    color: var(--student-muted);
    font-size: 12px;
}

.latest-item h3 {
    margin: 0 0 8px;
    font-size: 18px;
}

.latest-item h3 a {
    color: var(--student-navy);
    text-decoration: none;
}

.latest-meta {
    margin-bottom: 12px;
}

.latest-preview {
    margin-bottom: 14px;
    color: var(--muted);
    font-size: 14px;
    line-height: 1.7;
}

.latest-empty {
    padding: 35px 20px;
    text-align: center;
    background: var(--surface);
    border: 1px dashed var(--border);
    border-radius: 14px;
}

.empty-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    margin-bottom: 12px;
    color: var(--student-blue);
    background: var(--page-bg);
    border-radius: 50%;
    font-size: 25px;
}

.latest-empty h3 {
    margin: 0 0 8px;
    color: var(--student-navy);
}

.latest-empty p {
    margin: 0 0 15px;
    color: var(--student-muted);
    font-size: 14px;
}

.latest-empty a {
    color: var(--student-blue);
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
}

@media (max-width: 650px) {
    .student-welcome {
        min-height: 155px;
        padding: 24px 20px;
    }

    .welcome-decoration {
        display: none;
    }

    .student-stats, .student-links {
        grid-template-columns: 1fr;
    }

    .student-section-heading {
        align-items: flex-start;
    }

    .student-stat {
        padding: 18px;
    }
}

@media (max-width: 420px) {
    .student-section-heading {
        flex-direction: column;
    }

    .latest-item-top {
        align-items: flex-start;
        flex-direction: column;
    }
}
</style>
@endpush
