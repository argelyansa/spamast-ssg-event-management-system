
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Adviser Attendance Records</h2>
            <p class="text-muted mb-0">
                Review student attendance for this event session.
            </p>
        </div>

        <a href="{{ route('adviser.attendance.events') }}" class="btn btn-secondary">
            Back to Events
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h4>{{ $event->title }}</h4>
            <p class="mb-1">
                <strong>Session:</strong> {{ $session->period }}
            </p>
            <p class="mb-0">
                <strong>Event Date:</strong>
                {{ $event->event_date?->format('F d, Y') ?? 'No event date' }}
            </p>
        </div>
    </div>

    {{-- Attendance Summary --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted">Total Students</h6>
                    <h3>{{ $totalStudents }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted">Checked In</h6>
                    <h3 class="text-success">{{ $checkedInCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted">Not Yet Checked In</h6>
                    <h3 class="text-danger">{{ $notCheckedInCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted">Checked Out</h6>
                    <h3 class="text-primary">{{ $checkedOutCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Search and Filters --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET"
                  action="{{ route('adviser.attendance.index', [$event, $session]) }}">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">
                            Search Student
                        </label>
                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            placeholder="Student number or name"
                            value="{{ request('search') }}"
                        >
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="institute_id" class="form-label">
                            Institute
                        </label>
                        <select
                            name="institute_id"
                            id="institute_id"
                            class="form-select"
                        >
                            <option value="">All Institutes</option>

                            @foreach ($institutes as $institute)
                                <option
                                    value="{{ $institute->id }}"
                                    {{ request('institute_id') == $institute->id ? 'selected' : '' }}
                                >
                                    {{ $institute->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="program_id" class="form-label">
                            Program
                        </label>
                        <select
                            name="program_id"
                            id="program_id"
                            class="form-select"
                        >
                            <option value="">All Programs</option>

                            @foreach ($programs as $program)
                                <option
                                    value="{{ $program->id }}"
                                    data-institute-id="{{ $program->institute_id }}"
                                    {{ request('program_id') == $program->id ? 'selected' : '' }}
                                >
                                    {{ $program->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            Filter
                        </button>

                        {{-- Clear institute and program but preserve search --}}
                        <a
                            href="{{ route('adviser.attendance.index', [$event, $session]) . (request('search') ? '?search=' . urlencode(request('search')) : '') }}"
                            class="btn btn-outline-secondary"
                        >
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Attendance Table --}}
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Student Attendance List</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Student Number</th>
                            <th>Student Name</th>
                            <th>Program</th>
                            <th>Institute</th>
                            <th>Check In</th>
                            <th>Check In Time</th>
                            <th>Check Out</th>
                            <th>Check Out Time</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($students as $student)
                            @php
                                $attendance = $student->attendances->first();
                            @endphp

                            <tr>
                                <td>{{ $student->student_no }}</td>

                                <td>
                                    {{ $student->user->name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $student->program->name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $student->program->institute->name ?? 'N/A' }}
                                </td>

                                <td>
                                    @if ($attendance && $attendance->check_in_at)
                                        <span class="badge bg-success">
                                            Present
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Absent
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $attendance?->check_in_at?->format('M d, Y h:i A') ?? '—' }}
                                </td>

                                <td>
                                    @if ($attendance && $attendance->check_out_at)
                                        <span class="badge bg-success">
                                            Completed
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Not Yet
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $attendance?->check_out_at?->format('M d, Y h:i A') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    No students found matching your filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Program filter based on selected institute --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const instituteSelect = document.getElementById('institute_id');
    const programSelect = document.getElementById('program_id');

    function filterPrograms() {
        const selectedInstitute = instituteSelect.value;

        Array.from(programSelect.options).forEach(function (option) {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            const matchesInstitute =
                !selectedInstitute ||
                option.dataset.instituteId === selectedInstitute;

            option.hidden = !matchesInstitute;

            if (!matchesInstitute && option.selected) {
                programSelect.value = '';
            }
        });
    }

    instituteSelect.addEventListener('change', filterPrograms);

    filterPrograms();
});
</script>
@endsection
