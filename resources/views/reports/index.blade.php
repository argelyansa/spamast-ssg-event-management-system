@extends('layouts.app')

@section('title', 'Event Reports')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Event Reports</h2>
        <p class="text-muted mb-0">
            View attendance summaries and student records for SSG events.
        </p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <h5 class="fw-bold mb-3">
            <i class="bi bi-file-earmark-bar-graph me-2"></i>
            Available Event Reports
        </h5>

        @if ($events->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-calendar-x display-4 text-muted"></i>
                <h5 class="mt-3">No Events Found</h5>
                <p class="text-muted mb-0">
                    There are currently no events available for reporting.
                </p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Event</th>
                            <th>Date</th>
                            <th>Venue</th>
                            <th class="text-center">Sessions</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($events as $event)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $event->title }}
                                </td>

                                <td>
                                    {{ $event->event_date?->format('M d, Y') ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $event->venue }}
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-primary">
                                        {{ $event->sessions_count }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('reports.show', $event) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye me-1"></i>
                                        View Report
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
</div>

@endsection
