@extends('layouts.app')

@section('content')
<div class="admin-events-page">

    <div class="events-topbar">
        <a href="{{ route('admin.dashboard') }}" class="back-link">
            <span>←</span> Back to Dashboard
        </a>
        <span class="page-context">ADMINISTRATION / EVENTS</span>
    </div>

    <section class="events-hero">
        <div class="hero-content">
            <span class="hero-eyebrow">ADMINISTRATOR PANEL</span>
            <h1>Event Management</h1>
            <p>Monitor all organizational events, attendance records, and session schedules in one place.</p>
        </div>
        <div class="hero-icon">📅</div>
    </section>

    <section class="event-statistics">
        <div class="event-stat-card">
            <div class="stat-icon navy">📅</div>
            <div>
                <span class="stat-label">Total Events</span>
                <h2>{{ number_format($stats['total_events']) }}</h2>
                <small>All registered events</small>
            </div>
        </div>

        <div class="event-stat-card">
            <div class="stat-icon green">🗓️</div>
            <div>
                <span class="stat-label">Upcoming Events</span>
                <h2>{{ number_format($stats['upcoming_events']) }}</h2>
                <small>Today and upcoming</small>
            </div>
        </div>

        <div class="event-stat-card">
            <div class="stat-icon gray">📁</div>
            <div>
                <span class="stat-label">Past Events</span>
                <h2>{{ number_format($stats['past_events']) }}</h2>
                <small>Previously scheduled</small>
            </div>
        </div>

        <div class="event-stat-card">
            <div class="stat-icon blue">✓</div>
            <div>
                <span class="stat-label">Attendance Records</span>
                <h2>{{ number_format($stats['attendance_records']) }}</h2>
                <small>Recorded check-ins</small>
            </div>
        </div>
    </section>

    <section class="events-list-section">
        <div class="events-section-heading">
            <div>
                <h2>All Events</h2>
                <p>Browse event information and monitor session details.</p>
            </div>
            <span class="event-count">{{ $events->total() }} Events</span>
        </div>

        @forelse ($events as $event)
            @php
                $isUpcoming = $event->event_date && $event->event_date->gte(today());
            @endphp

            <article class="event-card">
                <div class="event-card-header">
                    <div class="event-date-block">
                        <span class="event-month">{{ $event->event_date?->format('M') }}</span>
                        <strong>{{ $event->event_date?->format('d') }}</strong>
                        <span class="event-year">{{ $event->event_date?->format('Y') }}</span>
                    </div>

                    <div class="event-main-info">
                        <div class="event-title-row">
                            <h3>{{ $event->title }}</h3>
                            <span class="event-status {{ $isUpcoming ? 'upcoming' : 'past' }}">
                                {{ $isUpcoming ? 'Upcoming' : 'Past Event' }}
                            </span>
                        </div>

                        <p class="event-venue">📍 {{ $event->venue }}</p>

                        <div class="event-badges">
                            <span>{{ ucfirst(str_replace('_', ' ', $event->day_type)) }}</span>
                            <span>{{ ucfirst($event->availability) }} Availability</span>
                        </div>
                    </div>
                </div>

                @if ($event->description)
                    <p class="event-description">{{ $event->description }}</p>
                @endif

                <div class="event-metrics">
                    <div>
                        <span>Created By</span>
                        <strong>{{ $event->creator?->name ?? 'Unavailable' }}</strong>
                    </div>
                    <div>
                        <span>Attendance Records</span>
                        <strong>{{ $event->attendances_count }}</strong>
                    </div>
                    <div>
                        <span>Penalty Records</span>
                        <strong>{{ $event->penalties_count }}</strong>
                    </div>
                    <div>
                        <span>Fine per Missed Session</span>
                        <strong>₱{{ number_format((float) $event->fine_per_miss, 2) }}</strong>
                    </div>
                </div>

                <div class="sessions-heading">
                    <h4>Event Sessions</h4>
                    <span>{{ $event->sessions->count() }} Sessions</span>
                </div>

                <div class="sessions-grid">
                    @forelse ($event->sessions as $session)
                        <div class="session-card">
                            <div class="session-title">
                                <span class="session-dot"></span>
                                <h5>{{ ucfirst($session->period) }} Session</h5>
                            </div>

                            <div class="session-schedule">
                                <div>
                                    <span>Check-in</span>
                                    <strong>
                                        {{ $session->check_in_start ? \Carbon\Carbon::parse($session->check_in_start)->format('h:i A') : 'Not specified' }}
                                        @if ($session->check_in_end)
                                            – {{ \Carbon\Carbon::parse($session->check_in_end)->format('h:i A') }}
                                        @endif
                                    </strong>
                                </div>
                                <div>
                                    <span>Check-out</span>
                                    <strong>
                                        {{ $session->check_out_start ? \Carbon\Carbon::parse($session->check_out_start)->format('h:i A') : 'Not specified' }}
                                        @if ($session->check_out_end)
                                            – {{ \Carbon\Carbon::parse($session->check_out_end)->format('h:i A') }}
                                        @endif
                                    </strong>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="no-sessions">No sessions have been recorded for this event.</p>
                    @endforelse
                </div>
            </article>
        @empty
            <div class="events-empty-state">
                <div>📅</div>
                <h3>No Events Found</h3>
                <p>There are currently no events registered in the system.</p>
            </div>
        @endforelse

        @if ($events->hasPages())
            <div class="events-pagination">
                {{ $events->links() }}
            </div>
        @endif
    </section>

</div>
@endsection

@push('styles')
<style>
    .admin-events-page {
        max-width: 1250px;
        margin: 0 auto;
        padding: 24px 20px 50px;
        color: var(--text);
    }

    .events-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        gap: 12px;
    }

    .back-link {
        color: var(--accent);
        font-weight: 700;
        text-decoration: none;
        font-size: 14px;
    }

    .back-link:hover { color: var(--accent-hover); }

    .back-link span {
        font-size: 19px;
        margin-right: 5px;
    }

    .page-context {
        color: var(--muted);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.2px;
    }

    .events-hero {
        background: linear-gradient(120deg, var(--header-bg), var(--accent));
        color: white;
        border-radius: 16px;
        padding: 32px 36px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        box-shadow: 0 8px 22px rgba(20, 45, 80, .12);
    }

    .hero-eyebrow {
        font-size: 11px;
        letter-spacing: 1.5px;
        font-weight: 800;
        opacity: .75;
    }

    .events-hero h1 {
        color: white;
        font-size: clamp(25px, 4vw, 34px);
        font-weight: 800;
        margin: 8px 0;
    }

    .events-hero p {
        color: rgba(255, 255, 255, .85);
        font-size: 14px;
        margin: 0;
        max-width: 650px;
        line-height: 1.6;
    }

    .hero-icon {
        font-size: 52px;
        background: rgba(255,255,255,.12);
        width: 85px;
        height: 85px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 20px;
    }

    .event-statistics {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 35px;
    }

    .event-stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 13px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 3px 12px rgba(25, 45, 75, .035);
        min-width: 0;
    }

    .stat-icon {
        width: 47px;
        height: 47px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 12px;
        font-size: 21px;
    }

    .stat-icon.navy { background: #eaf0f8; }
    .stat-icon.green { background: #e8f5ed; }
    .stat-icon.gray { background: #eff1f4; }
    .stat-icon.blue { background: #e8f2fb; }

    .stat-label {
        display: block;
        color: var(--muted);
        font-size: 12px;
        font-weight: 700;
    }

    .event-stat-card h2 {
        font-size: 27px;
        font-weight: 800;
        margin: 3px 0;
        color: var(--text);
    }

    .event-stat-card small {
        color: var(--muted);
        font-size: 11px;
    }

    .events-section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        gap: 12px;
    }

    .events-section-heading h2 {
        font-size: 21px;
        color: var(--text);
        font-weight: 800;
        margin: 0 0 5px;
    }

    .events-section-heading p {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .event-count {
        background: var(--page-bg);
        color: var(--accent);
        padding: 8px 13px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .event-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 15px;
        padding: 23px;
        margin-bottom: 18px;
        box-shadow: 0 4px 15px rgba(25, 45, 75, .035);
    }

    .event-card-header {
        display: flex;
        align-items: flex-start;
        gap: 17px;
    }

    .event-date-block {
        width: 68px;
        min-width: 68px;
        border: 1px solid var(--border);
        border-radius: 11px;
        text-align: center;
        padding: 8px 5px;
        background: var(--page-bg);
    }

    .event-month {
        display: block;
        color: var(--muted);
        text-transform: uppercase;
        font-size: 11px;
        font-weight: 800;
    }

    .event-date-block strong {
        display: block;
        color: var(--text);
        font-size: 27px;
        line-height: 1.2;
    }

    .event-year {
        color: var(--muted);
        font-size: 10px;
    }

    .event-main-info { flex: 1; min-width: 0; }

    .event-title-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .event-title-row h3 {
        color: var(--text);
        font-size: 17px;
        font-weight: 800;
        margin: 0;
    }

    .event-status {
        font-size: 10px;
        font-weight: 800;
        border-radius: 20px;
        padding: 5px 9px;
    }

    .event-status.upcoming {
        color: #237348;
        background: #e7f5ed;
    }

    .event-status.past {
        color: #657184;
        background: #eef1f5;
    }

    .event-venue {
        color: var(--muted);
        font-size: 13px;
        margin: 7px 0 9px;
    }

    .event-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .event-badges span {
        background: var(--page-bg);
        color: var(--muted);
        padding: 5px 9px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
    }

    .event-description {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.6;
        margin: 17px 0;
        padding-left: 85px;
    }

    .event-metrics {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        background: var(--page-bg);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 15px;
        gap: 14px;
        margin-top: 19px;
    }

    .event-metrics span {
        display: block;
        color: var(--muted);
        font-size: 11px;
        margin-bottom: 5px;
    }

    .event-metrics strong {
        color: var(--text);
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    .sessions-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 21px 0 11px;
    }

    .sessions-heading h4 {
        color: var(--text);
        font-size: 14px;
        font-weight: 800;
        margin: 0;
    }

    .sessions-heading span {
        color: var(--muted);
        font-size: 11px;
        font-weight: 700;
    }

    .sessions-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .session-card {
        background: var(--page-bg);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 14px;
        min-width: 0;
    }

    .session-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 13px;
    }

    .session-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--accent);
    }

    .session-title h5 {
        color: var(--text);
        font-size: 13px;
        font-weight: 800;
        margin: 0;
    }

    .session-schedule {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .session-schedule span {
        display: block;
        color: var(--muted);
        font-size: 10px;
        margin-bottom: 5px;
    }

    .session-schedule strong {
        color: var(--text);
        font-size: 11px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .no-sessions {
        color: var(--muted);
        font-size: 12px;
        margin: 0;
    }

    .events-empty-state {
        text-align: center;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 15px;
        padding: 55px 20px;
    }

    .events-empty-state div { font-size: 42px; }
    .events-empty-state h3 { color: var(--text); font-size: 18px; font-weight: 800; margin: 12px 0 6px; }
    .events-empty-state p { color: var(--muted); font-size: 13px; margin: 0; }

    .events-pagination { margin-top: 22px; }

    @media (max-width: 1000px) {
        .event-statistics { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .event-metrics { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 600px) {
        .admin-events-page { padding: 16px 12px 35px; }
        .events-hero { padding: 25px 20px; }
        .hero-icon { width: 58px; height: 58px; font-size: 30px; }
        .events-hero p { font-size: 12px; }
        .event-statistics { gap: 9px; }
        .event-stat-card { padding: 13px 10px; gap: 9px; }
        .stat-icon { width: 36px; height: 36px; font-size: 16px; }
        .event-stat-card h2 { font-size: 22px; }
        .event-stat-card small { font-size: 10px; }
        .event-card { padding: 16px; }
        .event-card-header { gap: 11px; }
        .event-title-row h3 { font-size: 15px; }
        .event-description { padding-left: 0; }
        .event-metrics { padding: 12px; gap: 12px; }
        .sessions-grid { grid-template-columns: 1fr; }
        .sessions-heading { align-items: flex-start; }
        .page-context { font-size: 9px; }
    }
</style>
@endpush
