@extends('layouts.app')

@section('title', 'SSG Events')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">SSG Events</h2>
            <p class="text-muted mb-0">View SSG events and scheduled activities.</p>
        </div>

        <a href="{{ route('adviser.dashboard') }}" class="btn btn-outline-secondary">
            Back to Dashboard
        </a>
    </div>

    @forelse ($events as $event)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h4 class="fw-bold">{{ $event->title }}</h4>

                <div class="text-muted mb-3">
                    <span class="me-3">
                        <i class="bi bi-calendar-event me-1"></i>
                        {{ $event->event_date?->format('F d, Y') ?? 'No event date' }}
                    </span>
                    <span>
                        <i class="bi bi-geo-alt me-1"></i>
                        {{ $event->venue ?? 'No venue specified' }}
                    </span>
                </div>

                @if ($event->description)
                    <p>{{ $event->description }}</p>
                @endif

                <h6 class="fw-semibold mt-3">Available Sessions</h6>

                @forelse ($event->sessions as $session)
                    <div class="border rounded p-3 mb-2">
                        <strong>{{ Illuminate\Support\Str::headline($session->period) }}</strong>
                    </div>
                @empty
                    <p class="text-muted mb-0">No sessions are available for this event.</p>
                @endforelse
            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-calendar-x fs-1 text-muted"></i>
                <h5 class="fw-semibold mt-3">No Events Found</h5>
                <p class="text-muted mb-0">There are currently no events to display.</p>
            </div>
        </div>
    @endforelse

    <div class="mt-3">
        {{ $events->links() }}
    </div>

</div>
@endsection
