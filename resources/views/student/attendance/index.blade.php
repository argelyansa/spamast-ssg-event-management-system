@extends('layouts.app')

@section('title', 'My Attendance')

@section('content')
<div class="attendance-page">

    <div class="attendance-heading">
        <div>
            <h1>My Attendance</h1>
            <p>Review your event attendance history and check-in/check-out records.</p>
        </div>

        <div class="attendance-count">
            {{ $attendances->total() }} Records
        </div>
    </div>

    <div class="attendance-list">
        @forelse ($attendances as $attendance)
            <article class="attendance-card">

                <div class="attendance-card-heading">
                    <div>
                        <h2>
                            {{ $attendance->eventSession?->event?->title ?? 'Event' }}
                        </h2>

                        <p class="attendance-meta">
                            {{ $attendance->eventSession?->event?->event_date?->format('F d, Y') ?? 'Date unavailable' }}
                            <span>·</span>
                            {{ ucfirst($attendance->eventSession?->period ?? 'Session unavailable') }} Session
                        </p>
                    </div>

                    <span class="attendance-status {{ $attendance->check_in_at && $attendance->check_out_at ? 'completed' : 'incomplete' }}">
                        {{ $attendance->check_in_at && $attendance->check_out_at ? 'Completed' : 'Incomplete' }}
                    </span>
                </div>

                <div class="attendance-divider"></div>

                <div class="attendance-times">
                    <div class="attendance-time-item">
                        <span class="time-label">CHECK-IN</span>
                        <strong>
                            {{ $attendance->check_in_at?->format('h:i A') ?? 'Not recorded' }}
                        </strong>
                        <small>
                            {{ $attendance->check_in_at?->format('M d, Y') ?? '' }}
                        </small>
                    </div>

                    <div class="attendance-time-item">
                        <span class="time-label">CHECK-OUT</span>
                        <strong>
                            {{ $attendance->check_out_at?->format('h:i A') ?? 'Not recorded' }}
                        </strong>
                        <small>
                            {{ $attendance->check_out_at?->format('M d, Y') ?? '' }}
                        </small>
                    </div>
                </div>

            </article>
        @empty
            <div class="attendance-empty">
                <h2>No Attendance Records Yet</h2>
                <p>Your event attendance records will appear here after you check in.</p>
            </div>
        @endforelse
    </div>

    <div class="attendance-pagination">
        {{ $attendances->links() }}
    </div>

    <a href="{{ route('student.dashboard') }}" class="attendance-back">
        ← Back to Dashboard
    </a>

</div>

<style>
    .attendance-page {
        max-width: 1050px;
        margin: 0 auto;
        padding: 30px 24px 50px;
        color: #0F172A;
    }

    .attendance-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 26px;
    }

    .attendance-heading h1 {
        font-size: 30px;
        font-weight: 700;
        color: #0F172A;
        margin: 0 0 8px;
    }

    .attendance-heading p {
        color: #64748B;
        margin: 0;
        line-height: 1.6;
    }

    .attendance-count {
        background: #DBEAFE;
        color: #1D4ED8;
        padding: 9px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .attendance-list {
        display: grid;
        gap: 20px;
    }

    .attendance-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .attendance-card:hover {
        border-color: #BFDBFE;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.07);
    }

    .attendance-card-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
    }

    .attendance-card h2 {
        color: #1E293B;
        font-size: 19px;
        font-weight: 700;
        margin: 0 0 9px;
    }

    .attendance-meta {
        color: #64748B;
        font-size: 14px;
        margin: 0;
    }

    .attendance-meta span {
        margin: 0 5px;
    }

    .attendance-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .attendance-status.completed {
        color: #15803D;
        background: #DCFCE7;
    }

    .attendance-status.incomplete {
        color: #B45309;
        background: #FEF3C7;
    }

    .attendance-divider {
        height: 1px;
        background: #E2E8F0;
        margin: 22px 0;
    }

    .attendance-times {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .attendance-time-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .time-label {
        color: #64748B;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.8px;
    }

    .attendance-time-item strong {
        color: #0F172A;
        font-size: 22px;
        font-weight: 700;
    }

    .attendance-time-item small {
        color: #64748B;
        font-size: 12px;
    }

    .attendance-empty {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 40px 24px;
        text-align: center;
    }

    .attendance-empty h2 {
        color: #1E293B;
        font-size: 20px;
    }

    .attendance-empty p {
        color: #64748B;
    }

    .attendance-pagination {
        margin-top: 22px;
    }

    .attendance-back {
        display: inline-block;
        margin-top: 24px;
        color: #2563EB;
        font-weight: 600;
        text-decoration: none;
    }

    .attendance-back:hover {
        color: #1D4ED8;
        text-decoration: underline;
    }

    @media (max-width: 600px) {
        .attendance-page {
            padding: 22px 15px 35px;
        }

        .attendance-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .attendance-card {
            padding: 18px;
        }

        .attendance-card-heading {
            flex-direction: column;
        }

        .attendance-times {
            gap: 12px;
        }

        .attendance-time-item strong {
            font-size: 19px;
        }
    }
</style>
@endsection
