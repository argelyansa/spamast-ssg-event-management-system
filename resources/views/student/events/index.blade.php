
@extends('layouts.app')

@section('title', 'Upcoming Events')

@section('content')

<div class="student-events-page">

    <section class="events-hero">
        <div class="hero-content">
            <span class="hero-label">SPAMAST SSG STUDENT PORTAL</span>
            <h1>Discover Upcoming Events</h1>
            <p>
                Stay informed, explore upcoming activities, and never miss
                an opportunity to participate in SPAMAST SSG events.
            </p>
        </div>

        <div class="hero-icon">
            <span>✦</span>
        </div>
    </section>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <section class="events-section">

        <div class="section-heading">
            <div>
                <span class="section-label">EVENT CALENDAR</span>
                <h2>Available Events</h2>
                <p>Explore activities prepared for the SPAMAST community.</p>
            </div>

            <div class="event-count">
                <span class="count-number">{{ $events->total() }}</span>
                <span class="count-label">
                    {{ $events->total() === 1 ? 'Event' : 'Events' }}
                </span>
            </div>
        </div>

        @if ($events->isEmpty())

            <div class="empty-events">
                <div class="empty-icon">✦</div>
                <h3>No Upcoming Events</h3>
                <p>
                    There are currently no upcoming SSG events.
                    Please check again later for new activities.
                </p>
            </div>

        @else

            <div class="event-grid">

                @foreach ($events as $event)

                    <article class="event-card">

                        <div class="event-card-top">
                            <div class="date-box">
                                <span class="date-month">
                                    {{ $event->event_date->format('M') }}
                                </span>
                                <span class="date-day">
                                    {{ $event->event_date->format('d') }}
                                </span>
                                <span class="date-year">
                                    {{ $event->event_date->format('Y') }}
                                </span>
                            </div>

                            <span class="event-type">
                                {{ $event->day_type === 'whole_day'
                                    ? 'WHOLE DAY'
                                    : 'HALF DAY' }}
                            </span>
                        </div>

                        <div class="event-card-body">

                            <p class="event-date-text">
                                {{ $event->event_date->format('l, F d, Y') }}
                            </p>

                            <h3>{{ $event->title }}</h3>

                            <div class="event-venue">
                                <span class="venue-icon">⌖</span>
                                <span>{{ $event->venue }}</span>
                            </div>

                            <div class="event-divider"></div>

                            <div class="event-card-footer">
                                <span class="event-status">
                                    <span class="status-dot"></span>
                                    Upcoming Event
                                </span>

                                <a
                                    href="{{ route('student.events.show', $event) }}"
                                    class="event-view-btn"
                                >
                                    View Details
                                    <span>→</span>
                                </a>
                            </div>

                        </div>

                    </article>

                @endforeach

            </div>

            <div class="events-pagination">
                {{ $events->links() }}
            </div>

        @endif

    </section>

</div>

@endsection

@push('styles')
<style>
    .student-events-page {
        width: 100%;
        color: #0B1120;
    }

    /* Hero section */

    .events-hero {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
        overflow: hidden;
        padding: 38px 42px;
        margin-bottom: 35px;
        border-radius: 16px;
        background: linear-gradient(120deg, #0B1120, #172B4D);
        color: #FFFFFF;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 650px;
    }

    .hero-label {
        display: inline-block;
        margin-bottom: 13px;
        color: #93C5FD;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2px;
    }

    .events-hero h1 {
        margin: 0 0 12px;
        color: #FFFFFF;
        font-size: clamp(27px, 4vw, 38px);
        font-weight: 750;
        line-height: 1.2;
    }

    .events-hero p {
        max-width: 580px;
        margin: 0;
        color: #CBD5E1;
        font-size: 15px;
        line-height: 1.7;
    }

    .hero-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 105px;
        height: 105px;
        border: 1px solid rgba(147, 197, 253, 0.3);
        border-radius: 25px;
        background: rgba(59, 130, 246, 0.15);
        color: #60A5FA;
        font-size: 60px;
    }

    /* Section heading */

    .section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .section-label {
        color: #3B82F6;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.7px;
    }

    .section-heading h2 {
        margin: 6px 0;
        color: #0B1120;
        font-size: 25px;
        font-weight: 750;
    }

    .section-heading p {
        margin: 0;
        color: #64748B;
        font-size: 14px;
    }

    .event-count {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 75px;
        height: 70px;
        padding: 8px;
        border: 1px solid #DBEAFE;
        border-radius: 12px;
        background: #EFF6FF;
    }

    .count-number {
        color: #2563EB;
        font-size: 24px;
        font-weight: 800;
        line-height: 1.2;
    }

    .count-label {
        color: #475569;
        font-size: 11px;
        font-weight: 600;
    }

    /* Event cards */

    .event-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .event-card {
        overflow: hidden;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        background: #FFFFFF;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease,
                    border-color 0.2s ease;
    }

    .event-card:hover {
        transform: translateY(-4px);
        border-color: #93C5FD;
        box-shadow: 0 12px 25px rgba(15, 23, 42, 0.09);
    }

    .event-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 15px;
        padding: 21px 23px;
        background: #F8FAFC;
        border-bottom: 1px solid #E2E8F0;
    }

    .date-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 67px;
        min-width: 67px;
        padding: 9px 5px;
        border-radius: 10px;
        background: #0B1120;
        color: #FFFFFF;
    }

    .date-month {
        color: #93C5FD;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .date-day {
        margin: 1px 0;
        color: #FFFFFF;
        font-size: 29px;
        font-weight: 800;
        line-height: 1.2;
    }

    .date-year {
        color: #CBD5E1;
        font-size: 10px;
    }

    .event-type {
        padding: 7px 11px;
        border: 1px solid #BFDBFE;
        border-radius: 20px;
        background: #EFF6FF;
        color: #1D4ED8;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .event-card-body {
        padding: 22px 23px;
    }

    .event-date-text {
        margin: 0 0 9px;
        color: #64748B;
        font-size: 12px;
        font-weight: 600;
    }

    .event-card-body h3 {
        margin: 0 0 15px;
        color: #0B1120;
        font-size: 19px;
        font-weight: 750;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .event-venue {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        color: #64748B;
        font-size: 13px;
        line-height: 1.6;
    }

    .venue-icon {
        color: #3B82F6;
        font-size: 20px;
        font-weight: 800;
        line-height: 1;
    }

    .event-divider {
        height: 1px;
        margin: 20px 0 16px;
        background: #E2E8F0;
    }

    .event-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .event-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #64748B;
        font-size: 12px;
        font-weight: 600;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22C55E;
    }

    .event-view-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 10px 16px;
        border-radius: 8px;
        background: #3B82F6;
        color: #FFFFFF;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.2s ease;
    }

    .event-view-btn:hover {
        background: #2563EB;
        color: #FFFFFF;
    }

    .event-view-btn span {
        font-size: 17px;
    }

    /* Empty state */

    .empty-events {
        padding: 55px 25px;
        border: 1px dashed #CBD5E1;
        border-radius: 14px;
        background: #FFFFFF;
        text-align: center;
    }

    .empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 65px;
        height: 65px;
        margin: 0 auto 17px;
        border-radius: 18px;
        background: #EFF6FF;
        color: #3B82F6;
        font-size: 35px;
    }

    .empty-events h3 {
        margin: 0 0 9px;
        color: #0B1120;
        font-size: 20px;
    }

    .empty-events p {
        max-width: 430px;
        margin: 0 auto;
        color: #64748B;
        font-size: 14px;
        line-height: 1.7;
    }

    /* Pagination */

    .events-pagination {
        margin-top: 25px;
    }

    /* Responsive layout */

    @media (max-width: 800px) {
        .event-grid {
            grid-template-columns: 1fr;
        }

        .events-hero {
            padding: 30px 25px;
        }

        .hero-icon {
            width: 75px;
            height: 75px;
            border-radius: 18px;
            font-size: 43px;
        }
    }

    @media (max-width: 520px) {
        .events-hero {
            padding: 25px 20px;
        }

        .hero-icon {
            display: none;
        }

        .section-heading {
            align-items: flex-start;
        }

        .event-card-top,
        .event-card-body {
            padding-left: 17px;
            padding-right: 17px;
        }

        .event-card-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .event-view-btn {
            width: 100%;
            box-sizing: border-box;
        }
    }
</style>
@endpush
