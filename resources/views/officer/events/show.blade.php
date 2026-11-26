
@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0B1120;">Event Details</h2>
            <p class="text-muted mb-0">View event information and attendance sessions.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('officer.events.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Events
            </a>

            <a href="{{ route('penalties.index', $event) }}" class="btn btn-outline-primary">
                <i class="bi bi-cash-coin"></i> View Penalties
            </a>

            @can('update', $event)
                <a href="{{ route('officer.events.edit', $event) }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i> Edit Event
                </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header text-white py-3" style="background-color: #111C32;">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-calendar-event me-2"></i>{{ $event->title }}
            </h5>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Event Title</small>
                    <strong>{{ $event->title }}</strong>
                </div>

                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Venue</small>
                    <strong>{{ $event->venue }}</strong>
                </div>

                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Event Date</small>
                    <strong>{{ $event->event_date->format('F d, Y') }}</strong>
                </div>

                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Day Type</small>
                    <span class="badge bg-primary">
                        {{ Illuminate\Support\Str::headline($event->day_type) }}
                    </span>
                </div>

                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Availability</small>
                    @if($event->availability === 'scheduled')
                        <span class="badge bg-info text-dark">{{ Illuminate\Support\Str::headline($event->availability) }}</span>
                    @else
                        <span class="badge bg-warning text-dark">{{ Illuminate\Support\Str::headline($event->availability) }}</span>
                    @endif
                </div>

                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Fine Per Miss</small>
                    <strong>₱{{ number_format((float) $event->fine_per_miss, 2) }}</strong>
                </div>

                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Created By</small>
                    <strong>{{ $event->creator?->name ?? 'Unknown' }}</strong>
                </div>

                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Created At</small>
                    <strong>{{ $event->created_at->format('F d, Y h:i A') }}</strong>
                </div>

                <div class="col-12">
                    <small class="text-muted d-block mb-1">Description</small>
                    <p class="mb-0">{{ $event->description ?: 'No description provided.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: #0B1120;">Event Sessions</h4>
            <p class="text-muted mb-0">Check-in and check-out schedules for this event.</p>
        </div>

        <span class="badge bg-primary">{{ $event->sessions->count() }} Session(s)</span>
    </div>

    @forelse($event->sessions as $session)
        @php
            $checkInOpen = $session->isCheckInOpen();
            $checkOutOpen = $session->isCheckOutOpen();
        @endphp

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white py-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h5 class="mb-0 fw-bold" style="color: #111C32;">
                        <i class="bi bi-clock-history me-2 text-primary"></i>
                        {{ Illuminate\Support\Str::headline($session->period) }}
                    </h5>

                    <span class="badge bg-secondary">
                        {{ $session->attendances->count() }} Attendance Record(s)
                    </span>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- Attendance Management Button --}}
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('officer.attendance.index', [$event, $session]) }}"
                       class="btn btn-primary btn-sm">
                        <i class="bi bi-people me-1"></i>
                        Manage Attendance
                    </a>
                </div>

                <div class="row g-4">

                    {{-- Check-in --}}
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 h-100" style="background-color: #EFF6FF;">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Check-in Schedule
                            </h6>

                            <p class="mb-1"><small class="text-muted">Start</small></p>
                            <strong>
                                {{ $session->check_in_start
                                    ? \Illuminate\Support\Carbon::parse($session->check_in_start)->format('h:i A')
                                    : 'Not set' }}
                            </strong>

                            <p class="mb-1 mt-3"><small class="text-muted">End</small></p>
                            <strong>
                                {{ $session->check_in_end
                                    ? \Illuminate\Support\Carbon::parse($session->check_in_end)->format('h:i A')
                                    : 'Not set' }}
                            </strong>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <small class="text-muted d-block mb-1">Check-in Status</small>

                                    @if($checkInOpen)
                                        <span class="badge bg-success">Open</span>
                                    @else
                                        <span class="badge bg-danger">Closed</span>
                                    @endif
                                </div>

                                @if($event->availability === 'manual')
                                    <form method="POST"
                                          action="{{ route('officer.sessions.toggle', $session) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="type" value="check_in">

                                        <button type="submit"
                                                class="btn btn-sm {{ $checkInOpen ? 'btn-danger' : 'btn-success' }}">
                                            <i class="bi {{ $checkInOpen ? 'bi-lock' : 'bi-unlock' }} me-1"></i>
                                            {{ $checkInOpen ? 'Close Check-in' : 'Open Check-in' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Check-out --}}
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 h-100" style="background-color: #F0FDF4;">
                            <h6 class="fw-bold text-success mb-3">
                                <i class="bi bi-box-arrow-right me-2"></i>Check-out Schedule
                            </h6>

                            <p class="mb-1"><small class="text-muted">Start</small></p>
                            <strong>
                                {{ $session->check_out_start
                                    ? \Illuminate\Support\Carbon::parse($session->check_out_start)->format('h:i A')
                                    : 'Not set' }}
                            </strong>

                            <p class="mb-1 mt-3"><small class="text-muted">End</small></p>
                            <strong>
                                {{ $session->check_out_end
                                    ? \Illuminate\Support\Carbon::parse($session->check_out_end)->format('h:i A')
                                    : 'Not set' }}
                            </strong>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <small class="text-muted d-block mb-1">Check-out Status</small>

                                    @if($checkOutOpen)
                                        <span class="badge bg-success">Open</span>
                                    @else
                                        <span class="badge bg-danger">Closed</span>
                                    @endif
                                </div>

                                @if($event->availability === 'manual')
                                    <form method="POST"
                                          action="{{ route('officer.sessions.toggle', $session) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="type" value="check_out">

                                        <button type="submit"
                                                class="btn btn-sm {{ $checkOutOpen ? 'btn-danger' : 'btn-success' }}">
                                            <i class="bi {{ $checkOutOpen ? 'bi-lock' : 'bi-unlock' }} me-1"></i>
                                            {{ $checkOutOpen ? 'Close Check-out' : 'Open Check-out' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                <hr>

                <div class="row text-center g-3">
                    <div class="col-6">
                        <div class="border rounded-3 p-3">
                            <h4 class="fw-bold text-primary mb-1">
                                {{ $session->attendances->whereNotNull('check_in_at')->count() }}
                            </h4>
                            <small class="text-muted">Checked In</small>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="border rounded-3 p-3">
                            <h4 class="fw-bold text-success mb-1">
                                {{ $session->attendances->whereNotNull('check_out_at')->count() }}
                            </h4>
                            <small class="text-muted">Checked Out</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-warning">
            No sessions have been created for this event.
        </div>
    @endforelse

</div>
@endsection
