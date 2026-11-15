
@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0B1120;">
                Attendance Management
            </h2>
            <p class="text-muted mb-0">
                View attendance records for all registered students.
            </p>
        </div>

        <a href="{{ route('officer.events.show', $event) }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Event
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header text-white py-3"
             style="background-color: #111C32;">
            <h5 class="mb-1 fw-semibold">{{ $event->title }}</h5>
            <small>
                {{ ucfirst(str_replace('_', ' ', $session->period)) }} Session
                · {{ $event->event_date->format('F d, Y') }}
            </small>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-6 col-lg-3">
                    <div class="border rounded-3 p-3 h-100">
                        <h3 class="fw-bold mb-1">{{ $totalStudents }}</h3>
                        <small class="text-muted">Total Students</small>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="border rounded-3 p-3 h-100">
                        <h3 class="fw-bold text-success mb-1">
                            {{ $checkedInCount }}
                        </h3>
                        <small class="text-muted">Checked In</small>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="border rounded-3 p-3 h-100">
                        <h3 class="fw-bold text-danger mb-1">
                            {{ $notCheckedInCount }}
                        </h3>
                        <small class="text-muted">Not Yet Checked In</small>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="border rounded-3 p-3 h-100">
                        <h3 class="fw-bold text-primary mb-1">
                            {{ $checkedOutCount }}
                        </h3>
                        <small class="text-muted">Checked Out</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET"
                  action="{{ route('officer.attendance.index', [$event, $session]) }}">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="search" class="form-label fw-semibold">
                            Search Student
                        </label>
                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            placeholder="Student number or student name"
                            value="{{ request('search') }}"
                        >
                    </div>

                    <div class="col-md-3">
                        <label for="institute_id" class="form-label fw-semibold">
                            Institute
                        </label>
                        <select name="institute_id"
                                id="institute_id"
                                class="form-select">
                            <option value="">All Institutes</option>

                            @foreach($institutes as $institute)
                                <option value="{{ $institute->id }}"
                                    @selected(request('institute_id') == $institute->id)>
                                    {{ $institute->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="program_id" class="form-label fw-semibold">
                            Program
                        </label>
                        <select name="program_id"
                                id="program_id"
                                class="form-select">

                            <option value="">All Programs</option>

                            @foreach($programs as $program)
                                <option
                                    value="{{ $program->id }}"
                                    data-institute-id="{{ $program->institute_id }}"
                                    @selected(request('program_id') == $program->id)
                                >
                                    {{ $program->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-12 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>
                            Apply Filters
                        </button>

                        <a href="{{ route('officer.attendance.index', [$event, $session]) }}"
                           class="btn btn-outline-secondary">
                            Clear Filters
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0" style="color: #111C32;">
                Student Attendance List
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">#</th>
                            <th>Student Number</th>
                            <th>Student Name</th>
                            <th>Program</th>
                            <th>Institute</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($students as $student)
                            @php
                                $attendance = $student->attendances->first();
                            @endphp

                            <tr>
                                <td class="px-3">
                                    {{ $students->firstItem() + $loop->index }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $student->student_no }}
                                </td>

                                <td>
                                    {{ $student->user?->name ?? 'Unknown' }}
                                </td>

                                <td>
                                    {{ $student->program?->name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $student->program?->institute?->name ?? 'N/A' }}
                                </td>

                                <td>
                                    @if($attendance?->check_in_at)
                                        <span class="badge bg-success">Checked In</span>
                                        <div class="small text-muted mt-1">
                                            {{ $attendance->check_in_at->format('h:i A') }}
                                        </div>
                                    @else
                                        <span class="badge bg-danger">
                                            Not Yet Checked In
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($attendance?->check_out_at)
                                        <span class="badge bg-primary">Checked Out</span>
                                        <div class="small text-muted mt-1">
                                            {{ $attendance->check_out_at->format('h:i A') }}
                                        </div>
                                    @elseif($attendance?->check_in_at)
                                        <span class="badge bg-warning text-dark">
                                            Not Yet Checked Out
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    No students match your search or filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($students->hasPages())
            <div class="card-footer bg-white">
                {{ $students->links() }}
            </div>
        @endif
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const instituteSelect = document.getElementById('institute_id');
    const programSelect = document.getElementById('program_id');

    if (!instituteSelect || !programSelect) {
        return;
    }

    function filterPrograms() {
        const selectedInstitute = instituteSelect.value;

        Array.from(programSelect.options).forEach(function (option, index) {
            if (index === 0) {
                option.hidden = false;
                option.disabled = false;
                return;
            }

            const matchesInstitute =
                selectedInstitute === '' ||
                option.dataset.instituteId === selectedInstitute;

            option.hidden = !matchesInstitute;
            option.disabled = !matchesInstitute;
        });

        const selectedOption =
            programSelect.options[programSelect.selectedIndex];

        if (selectedOption && selectedOption.disabled) {
            programSelect.value = '';
        }
    }

    instituteSelect.addEventListener('change', function () {
        programSelect.value = '';
        filterPrograms();
    });

    filterPrograms();
});
</script>
@endsection
