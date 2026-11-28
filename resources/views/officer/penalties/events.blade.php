@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Fines and Sanctions</h2>
            <p class="text-muted mb-0">Select an event to view its student penalties and sanctions.</p>
        </div>

        <a href="{{ route('officer.dashboard') }}" class="btn btn-outline-secondary mt-3 mt-md-0">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">

            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'upcoming' ? 'active' : '' }}"
                       href="{{ route('officer.penalties.events', ['tab' => 'upcoming']) }}">
                        Upcoming Events
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'past' ? 'active' : '' }}"
                       href="{{ route('officer.penalties.events', ['tab' => 'past']) }}">
                        Past Events
                    </a>
                </li>
            </ul>

            <form action="{{ route('officer.penalties.events') }}"
                  method="GET"
                  class="row g-3 align-items-end">

                <input type="hidden" name="tab" value="{{ $activeTab }}">

                <div class="col-md-4">
                    <label for="event_date" class="form-label fw-semibold">Search by Event Date</label>
                    <input type="date"
                           name="event_date"
                           id="event_date"
                           class="form-control"
                           value="{{ $searchDate }}">
                </div>

                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Search
                    </button>

                    <a href="{{ route('officer.penalties.events', ['tab' => $activeTab]) }}"
                       class="btn btn-outline-secondary">
                        Clear
                    </a>
                </div>
            </form>

        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-semibold mb-0">
                {{ $activeTab === 'upcoming' ? 'Upcoming Events' : 'Past Events' }}
            </h5>
        </div>

        <div class="card-body p-0">
            @if($events->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3">#</th>
                                <th>Event Title</th>
                                <th>Venue</th>
                                <th>Event Date</th>
                                <th>Fine Per Missed Scan</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($events as $event)
                                <tr>
                                    <td class="px-3">
                                        {{ $events->firstItem() + $loop->index }}
                                    </td>

                                    <td class="fw-semibold">{{ $event->title }}</td>

                                    <td>{{ $event->venue }}</td>

                                    <td>{{ $event->event_date?->format('M d, Y') ?? 'N/A' }}</td>

                                    <td>₱{{ number_format((float) $event->fine_per_miss, 2) }}</td>

                                    <td class="text-center">
                                        <a href="{{ route('penalties.index', $event) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i> View Penalties
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-3">
                    {{ $events->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x fs-1 text-muted"></i>
                    <h5 class="fw-semibold mt-3">No Events Found</h5>

                    <p class="text-muted mb-0">
                        @if($searchDate)
                            No events found for {{ \Carbon\Carbon::parse($searchDate)->format('F d, Y') }} in this category.
                        @elseif($activeTab === 'upcoming')
                            There are currently no upcoming events.
                        @else
                            There are currently no past events.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
