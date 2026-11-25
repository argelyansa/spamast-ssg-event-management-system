
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Event Management</h2>
            <p class="text-muted mb-0">Create, view, update, and manage SSG events.</p>
        </div>

        <a href="{{ route('officer.events.create') }}" class="btn btn-primary mt-3 mt-md-0">
            <i class="bi bi-plus-circle me-1"></i> Add New Event
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            Please check the form for errors.
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">

            {{-- Upcoming and Past Tabs --}}
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a
                        class="nav-link {{ $activeTab === 'upcoming' ? 'active' : '' }}"
                        href="{{ route('officer.events.index', ['tab' => 'upcoming']) }}"
                    >
                        Upcoming Events
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link {{ $activeTab === 'past' ? 'active' : '' }}"
                        href="{{ route('officer.events.index', ['tab' => 'past']) }}"
                    >
                        Past Events
                    </a>
                </li>
            </ul>

            {{-- Date Search --}}
            <form
                action="{{ route('officer.events.index') }}"
                method="GET"
                class="row g-3 align-items-end"
            >
                <input type="hidden" name="tab" value="{{ $activeTab }}">

                <div class="col-md-4">
                    <label for="event_date" class="form-label fw-semibold">
                        Search by Event Date
                    </label>

                    <input
                        type="date"
                        name="event_date"
                        id="event_date"
                        class="form-control"
                        value="{{ $searchDate }}"
                    >
                </div>

                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Search
                    </button>

                    <a
                        href="{{ route('officer.events.index', ['tab' => $activeTab]) }}"
                        class="btn btn-outline-secondary"
                    >
                        Clear
                    </a>
                </div>
            </form>

        </div>
    </div>

    {{-- Events Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-semibold mb-0">
                {{ $activeTab === 'upcoming' ? 'Upcoming Events List' : 'Past Events List' }}
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
                                <th>Day Type</th>
                                <th>Availability</th>
                                <th>Fine</th>
                                <th>Created By</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($events as $event)
                                <tr>
                                    <td class="px-3">
                                        {{ $events->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <span class="fw-semibold">{{ $event->title }}</span>
                                    </td>

                                    <td>{{ $event->venue }}</td>

                                    <td>
                                        {{ $event->event_date?->format('M d, Y') ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $event->day_type === 'whole_day' ? 'Whole Day' : 'Half Day' }}
                                    </td>

                                    <td>
                                        @if($event->availability === 'scheduled')
                                            <span class="badge bg-success">Scheduled</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Manual</span>
                                        @endif
                                    </td>

                                    <td>
                                        ₱{{ number_format((float) $event->fine_per_miss, 2) }}
                                    </td>

                                    <td>{{ $event->creator?->name ?? 'N/A' }}</td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">

                                            <a
                                                href="{{ route('officer.events.show', $event) }}"
                                                class="btn btn-sm btn-outline-success"
                                            >
                                                View Details
                                            </a>

                                            <a
                                                href="{{ route('officer.events.edit', $event) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                action="{{ route('officer.events.destroy', $event) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this event?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Delete
                                                </button>
                                            </form>

                                        </div>
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
                    <h5 class="fw-semibold">No Events Found</h5>

                    <p class="text-muted mb-3">
                        @if($searchDate)
                            No events found for {{ \Carbon\Carbon::parse($searchDate)->format('F d, Y') }}
                            in this category.
                        @elseif($activeTab === 'upcoming')
                            There are currently no upcoming events.
                        @else
                            There are currently no past events.
                        @endif
                    </p>

                    <a href="{{ route('officer.events.create') }}" class="btn btn-primary">
                        Add New Event
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
