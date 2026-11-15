
@extends('layouts.app')

@section('title', 'Event Details')

@section('content')

<div class="student-event-details">

    {{-- Event Header --}}
    <section class="event-hero">
        <span class="hero-label">SPAMAST SSG STUDENT PORTAL</span>

        <h1>{{ $event->title }}</h1>

        <p>Event information and your personal attendance record.</p>

        <a href="{{ route('student.events.index') }}" class="back-events-btn">
            <span>←</span> Back to Events
        </a>
    </section>

    {{-- Notifications --}}
    @if (session('success'))
        <div class="attendance-alert success-alert">
            <strong>✓</strong>
            {{ session('success') }}
        </div>
    @endif

    @if (session('info'))
        <div class="attendance-alert info-alert">
            <strong>ⓘ</strong>
            {{ session('info') }}
        </div>
    @endif

    {{-- Event Information --}}
    <section class="event-information">
        <div class="section-heading">
            <div>
                <span class="section-label">EVENT OVERVIEW</span>
                <h2>Event Information</h2>
            </div>

            <span class="event-type-badge">
                {{ $event->day_type === 'whole_day' ? 'WHOLE DAY' : 'HALF DAY' }}
            </span>
        </div>

        <div class="information-grid">

            <div class="information-item">
                <span class="information-icon">▦</span>
                <div>
                    <span class="information-label">EVENT DATE</span>
                    <strong>{{ $event->event_date->format('F d, Y') }}</strong>
                </div>
            </div>

            <div class="information-item">
                <span class="information-icon">⌖</span>
                <div>
                    <span class="information-label">VENUE</span>
                    <strong>{{ $event->venue }}</strong>
                </div>
            </div>

        </div>

        <div class="description-box">
            <span class="information-label">DESCRIPTION</span>
            <p>{{ $event->description ?: 'No description provided.' }}</p>
        </div>
    </section>

    {{-- Attendance --}}
    @if (!$student)

        <div class="unlinked-student">
            <h3>Student Account Not Linked</h3>
            <p>
                Your account is not linked to a student record.
                Please contact the SSG Officer for assistance.
            </p>
        </div>

    @else

        <section class="attendance-section">

            <div class="section-heading attendance-heading">
                <div>
                    <span class="section-label">PARTICIPATION RECORD</span>
                    <h2>My Attendance</h2>
                    <p>Monitor your check-in and check-out records for each session.</p>
                </div>
            </div>

            <div class="session-grid">

                @forelse ($event->sessions as $session)

                    @php
                        $attendance = $session->attendances->first();
                        $checkInOpen = $session->isCheckInOpen();
                        $checkOutOpen = $session->isCheckOutOpen();
                    @endphp

                    <article class="session-card">

                        <div class="session-header">
                            <div>
                                <span class="session-label">ATTENDANCE SESSION</span>
                                <h3>
                                    {{ ucfirst(str_replace('_', ' ', $session->period)) }}
                                    Session
                                </h3>
                            </div>

                            <span class="session-icon">◷</span>
                        </div>

                        <div class="attendance-records">

                            {{-- Check-in status --}}
                            <div class="record-item">
                                <div class="record-information">
                                    <span class="record-icon">↘</span>
                                    <div>
                                        <strong>Check-in</strong>
                                        <p>
                                            {{ $attendance?->check_in_at
                                                ? $attendance->check_in_at->format('M d, Y h:i A')
                                                : 'Not yet checked in' }}
                                        </p>
                                    </div>
                                </div>

                                @if ($attendance?->check_in_at)
                                    <span class="record-badge recorded">Recorded</span>
                                @elseif ($checkInOpen)
                                    <span class="record-badge open">Open</span>
                                @else
                                    <span class="record-badge closed">Closed</span>
                                @endif
                            </div>

                            {{-- Check-out status --}}
                            <div class="record-item">
                                <div class="record-information">
                                    <span class="record-icon">↗</span>
                                    <div>
                                        <strong>Check-out</strong>
                                        <p>
                                            {{ $attendance?->check_out_at
                                                ? $attendance->check_out_at->format('M d, Y h:i A')
                                                : 'Not yet checked out' }}
                                        </p>
                                    </div>
                                </div>

                                @if ($attendance?->check_out_at)
                                    <span class="record-badge recorded">Recorded</span>
                                @elseif ($checkOutOpen && $attendance?->check_in_at)
                                    <span class="record-badge open">Open</span>
                                @else
                                    <span class="record-badge closed">Closed</span>
                                @endif
                            </div>

                        </div>

                        {{-- Attendance Actions --}}
                        <div class="session-actions">

                            @if ($checkInOpen && (!$attendance || !$attendance->check_in_at))

                                <form method="POST"
                                      action="{{ route('student.attendance.check-in', $session) }}">
                                    @csrf
                                    <button type="submit" class="attendance-button">
                                        Check In <span>→</span>
                                    </button>
                                </form>

                            @elseif ($attendance?->check_in_at)

                                <p class="action-message completed">
                                    ✓ Your check-in has been recorded.
                                </p>

                            @else

                                <p class="action-message unavailable">
                                    Check-in is currently closed.
                                </p>

                            @endif

                            @if ($checkOutOpen && $attendance?->check_in_at && !$attendance?->check_out_at)

                                <form method="POST"
                                      action="{{ route('student.attendance.check-out', $session) }}">
                                    @csrf
                                    <button type="submit" class="attendance-button checkout-button">
                                        Check Out <span>→</span>
                                    </button>
                                </form>

                            @elseif ($attendance?->check_out_at)

                                <p class="action-message completed">
                                    ✓ Your check-out has been recorded.
                                </p>

                            @else

                                <p class="action-message unavailable">
                                    Check-out is currently unavailable.
                                </p>

                            @endif

                        </div>

                    </article>

                @empty

                    <div class="empty-sessions">
                        <h3>No Sessions Available</h3>
                        <p>There are currently no attendance sessions for this event.</p>
                    </div>

                @endforelse

            </div>

        </section>

    @endif

</div>

@endsection

@push('styles')
<style>
    .student-event-details {
        width: 100%;
        color: #0B1120;
    }

    /* Event Hero */

    .event-hero {
        padding: 35px 40px;
        margin-bottom: 28px;
        border-radius: 16px;
        background: linear-gradient(120deg, #0B1120, #172B4D);
        color: #FFFFFF;
    }

    .hero-label {
        display: inline-block;
        margin-bottom: 13px;
        color: #93C5FD;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.8px;
    }

    .event-hero h1 {
        margin: 0 0 12px;
        color: #FFFFFF;
        font-size: clamp(25px, 4vw, 35px);
        font-weight: 750;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .event-hero p {
        margin: 0 0 23px;
        color: #CBD5E1;
        font-size: 14px;
        line-height: 1.7;
    }

    .back-events-btn {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 10px 16px;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 8px;
        background: rgba(255,255,255,0.08);
        color: #FFFFFF;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.2s;
    }

    .back-events-btn:hover {
        background: rgba(255,255,255,0.17);
        color: #FFFFFF;
    }

    /* Notifications */

    .attendance-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 17px;
        margin-bottom: 20px;
        border-radius: 9px;
        font-size: 14px;
    }

    .success-alert {
        border: 1px solid #BBF7D0;
        background: #F0FDF4;
        color: #166534;
    }

    .info-alert {
        border: 1px solid #BFDBFE;
        background: #EFF6FF;
        color: #1D4ED8;
    }

    /* General Section Heading */

    .event-information,
    .attendance-section {
        margin-bottom: 30px;
    }

    .section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 20px;
    }

    .section-label {
        color: #3B82F6;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.6px;
    }

    .section-heading h2 {
        margin: 6px 0;
        color: #0B1120;
        font-size: 23px;
        font-weight: 750;
    }

    .section-heading p {
        margin: 0;
        color: #64748B;
        font-size: 13px;
        line-height: 1.6;
    }

    .event-type-badge {
        padding: 8px 12px;
        border: 1px solid #BFDBFE;
        border-radius: 20px;
        background: #EFF6FF;
        color: #1D4ED8;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    /* Event Information */

    .event-information {
        padding: 25px;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        background: #FFFFFF;
        box-shadow: 0 3px 12px rgba(15,23,42,0.04);
    }

    .information-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 15px;
    }

    .information-item {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
        padding: 16px;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        background: #F8FAFC;
    }

    .information-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #EFF6FF;
        color: #2563EB;
        font-size: 22px;
    }

    .information-item > div {
        min-width: 0;
    }

    .information-label {
        display: block;
        margin-bottom: 5px;
        color: #64748B;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .information-item strong {
        display: block;
        color: #0B1120;
        font-size: 13px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .description-box {
        padding: 17px;
        margin-top: 15px;
        border-radius: 10px;
        background: #F8FAFC;
    }

    .description-box p {
        margin: 7px 0 0;
        color: #475569;
        font-size: 14px;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }

    /* Attendance Session Cards */

    .session-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .session-card {
        min-width: 0;
        padding: 22px;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        background: #FFFFFF;
        box-shadow: 0 3px 12px rgba(15,23,42,0.04);
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .session-card:hover {
        border-color: #93C5FD;
        box-shadow: 0 8px 20px rgba(15,23,42,0.07);
    }

    .session-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 20px;
    }

    .session-label {
        color: #64748B;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.2px;
    }

    .session-header h3 {
        margin: 6px 0 0;
        color: #0B1120;
        font-size: 19px;
        font-weight: 750;
    }

    .session-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        border-radius: 12px;
        background: #EFF6FF;
        color: #2563EB;
        font-size: 25px;
    }

    /* Attendance Records */

    .attendance-records {
        border-top: 1px solid #E2E8F0;
    }

    .record-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 0;
        border-bottom: 1px solid #E2E8F0;
    }

    .record-information {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
    }

    .record-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        border-radius: 9px;
        background: #F1F5F9;
        color: #3B82F6;
        font-size: 20px;
        font-weight: 800;
    }

    .record-information strong {
        display: block;
        color: #0B1120;
        font-size: 13px;
    }

    .record-information p {
        margin: 4px 0 0;
        color: #64748B;
        font-size: 11px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .record-badge {
        flex-shrink: 0;
        padding: 6px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
    }

    .recorded {
        background: #DCFCE7;
        color: #166534;
    }

    .open {
        background: #DBEAFE;
        color: #1D4ED8;
    }

    .closed {
        background: #F1F5F9;
        color: #64748B;
    }

    /* Attendance Buttons */

    .session-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 18px;
    }

    .session-actions form {
        width: 100%;
    }

    .attendance-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 12px 16px;
        border: none;
        border-radius: 8px;
        background: #3B82F6;
        color: #FFFFFF;
        font-size: 13px;
        font-weight: 750;
        cursor: pointer;
        transition: background 0.2s;
    }

    .attendance-button:hover {
        background: #2563EB;
    }

    .checkout-button {
        background: #0F766E;
    }

    .checkout-button:hover {
        background: #115E59;
    }

    .attendance-button span {
        font-size: 17px;
    }

    .action-message {
        margin: 0;
        font-size: 12px;
        line-height: 1.6;
    }

    .completed {
        color: #15803D;
        font-weight: 700;
    }

    .unavailable {
        color: #64748B;
    }

    /* Empty and unlinked states */

    .empty-sessions,
    .unlinked-student {
        grid-column: 1 / -1;
        padding: 30px;
        border: 1px dashed #CBD5E1;
        border-radius: 12px;
        background: #FFFFFF;
        text-align: center;
    }

    .empty-sessions h3,
    .unlinked-student h3 {
        margin: 0 0 8px;
        color: #0B1120;
        font-size: 18px;
    }

    .empty-sessions p,
    .unlinked-student p {
        margin: 0;
        color: #64748B;
        font-size: 13px;
        line-height: 1.7;
    }

    /* Responsive */

    @media (max-width: 850px) {
        .session-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .event-hero {
            padding: 27px 22px;
        }

        .event-information {
            padding: 19px;
        }

        .information-grid {
            grid-template-columns: 1fr;
        }

        .section-heading {
            align-items: flex-start;
        }

        .session-card {
            padding: 18px;
        }

        .record-item {
            align-items: flex-start;
        }
    }
</style>
@endpush
