
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Attendance Records</h2>
            <p class="text-muted mb-0">
                Select an event and session to review student attendance.
            </p>
        </div>

        <a href="{{ route('adviser.dashboard') }}" class="btn btn-secondary">
            Back to Dashboard
        </a>
    </div>

    @forelse ($events as $event)
        <div class="card mb-4">
            <div class="card-body">
                <h4>{{ $event->title }}</h4>

                <p class="text-muted mb-3">
                    {{ $event->event_date?->format('F d, Y') ?? 'No event date' }}
                </p>

                @if ($event->description)
                    <p>{{ $event->description }}</p>
                @endif

                <h6 class="mt-3">Available Sessions</h6>

                @forelse ($event->sessions as $session)
                    <div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2">
                        <div>
                            <strong>{{ $session->period }}</strong>
                        </div>

                        <a
                            href="{{ route('adviser.attendance.index', [$event, $session]) }}"
                            class="btn btn-primary btn-sm"
                        >
                            View Attendance
                        </a>
                    </div>
                @empty
                    <p class="text-muted mb-0">
                        No sessions are available for this event.
                    </p>
                @endforelse
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            No events have been created yet.
        </div>
    @endforelse

    <div class="mt-3">
        {{ $events->links() }}
    </div>
</div>
@endsection
