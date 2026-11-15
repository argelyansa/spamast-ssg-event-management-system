@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0B1120;">
                Penalties and Sanctions
            </h2>
            <p class="text-muted mb-0">
                {{ $event->title }}
                · {{ $event->event_date?->format('F d, Y') ?? 'No event date' }}
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            @if(auth()->user()->hasRole('Officer'))
                <a href="{{ route('officer.events.show', $event) }}"
                   class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Back to Event
                </a>
            @else
                <a href="{{ route('adviser.attendance.events') }}"
                   class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Back to Attendance
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @can('manage-ssg')
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Generate Event Penalties</h5>
                    <p class="text-muted mb-0">
                        Calculate missed attendance scans and update student penalties.
                    </p>
                </div>

                <form method="POST"
                      action="{{ route('officer.penalties.generate', $event) }}"
                      onsubmit="return confirm('Generate or update penalties for all students for this event?');">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-calculator me-1"></i>
                        Generate Penalties
                    </button>
                </form>
            </div>
        </div>
    @endcan

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="fw-bold mb-1">{{ $totalPenalties }}</h3>
                    <small class="text-muted">Students with Penalties</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="fw-bold text-danger mb-1">
                        ₱{{ number_format((float) $totalAmount, 2) }}
                    </h3>
                    <small class="text-muted">Total Penalty Amount</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('penalties.index', $event) }}">
                <div class="row g-3">

                    <div class="col-md-4">
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
                        <select name="institute_id" id="institute_id" class="form-select">
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
                        <select name="program_id" id="program_id" class="form-select">
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

                    <div class="col-md-2">
                        <label for="status" class="form-label fw-semibold">
                            Status
                        </label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="unpaid" @selected(request('status') === 'unpaid')>
                                Unpaid
                            </option>
                            <option value="paid" @selected(request('status') === 'paid')>
                                Paid
                            </option>
                            <option value="served" @selected(request('status') === 'served')>
                                Served
                            </option>
                        </select>
                    </div>

                    <div class="col-12 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>
                            Apply Filters
                        </button>

                        <a href="{{ route('penalties.index', $event) }}"
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
                Student Penalty Records
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
                            <th>Missed Scans</th>
                            <th>Amount</th>
                            <th>Sanction</th>
                            <th>Status</th>
                            @can('manage-ssg')
                                <th>Actions</th>
                            @endcan
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($penalties as $penalty)
                            @php
                                $student = $penalty->student;
                                $formId = 'penalty-form-' . $penalty->id;
                            @endphp

                            <tr>
                                <td class="px-3">
                                    {{ $penalties->firstItem() + $loop->index }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $student?->student_no ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $student?->user?->name ?? 'Unknown' }}
                                </td>

                                <td>
                                    {{ $student?->program?->name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $student?->program?->institute?->name ?? 'N/A' }}
                                </td>

                                <td>
                                    <span class="badge bg-danger">
                                        {{ $penalty->missed_scans }}
                                    </span>
                                </td>

                                @can('manage-ssg')
                                    <td style="min-width: 130px;">
                                        <input
                                            type="number"
                                            name="amount"
                                            form="{{ $formId }}"
                                            class="form-control form-control-sm"
                                            min="0"
                                            max="999999.99"
                                            step="0.01"
                                            value="{{ number_format((float) $penalty->amount, 2, '.', '') }}"
                                            required
                                        >
                                    </td>

                                    <td style="min-width: 160px;">
                                        <input
                                            type="text"
                                            name="sanction"
                                            form="{{ $formId }}"
                                            class="form-control form-control-sm"
                                            maxlength="255"
                                            value="{{ $penalty->sanction }}"
                                            placeholder="Optional sanction"
                                        >
                                    </td>

                                    <td style="min-width: 120px;">
                                        <select
                                            name="status"
                                            form="{{ $formId }}"
                                            class="form-select form-select-sm"
                                            required
                                        >
                                            <option value="unpaid" @selected($penalty->status === 'unpaid')>
                                                Unpaid
                                            </option>
                                            <option value="paid" @selected($penalty->status === 'paid')>
                                                Paid
                                            </option>
                                            <option value="served" @selected($penalty->status === 'served')>
                                                Served
                                            </option>
                                        </select>
                                    </td>

                                    <td style="min-width: 150px;">
                                        <form id="{{ $formId }}"
                                              method="POST"
                                              action="{{ route('officer.penalties.update', [$event, $penalty]) }}">
                                            @csrf
                                            @method('PUT')
                                        </form>

                                        <div class="d-flex flex-column gap-2">
                                            <button
                                                type="submit"
                                                form="{{ $formId }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="bi bi-save me-1"></i>
                                                Save
                                            </button>

                                            <form method="POST"
                                                  action="{{ route('officer.penalties.destroy', [$event, $penalty]) }}"
                                                  onsubmit="return confirm('Delete this penalty record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                                    <i class="bi bi-trash me-1"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @else
                                    <td>
                                        ₱{{ number_format((float) $penalty->amount, 2) }}
                                    </td>

                                    <td>
                                        {{ $penalty->sanction ?: '—' }}
                                    </td>

                                    <td>
                                        @if($penalty->status === 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($penalty->status === 'served')
                                            <span class="badge bg-primary">Served</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Unpaid</span>
                                        @endif
                                    </td>
                                @endcan
                            </tr>
                        @empty
                            <tr>
                                <td colspan="@can('manage-ssg') 10 @else 9 @endcan"
                                    class="text-center py-4 text-muted">
                                    No penalty records found for this event.
                                    @can('manage-ssg')
                                        Generate penalties after attendance records are available.
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($penalties->hasPages())
            <div class="card-footer bg-white">
                {{ $penalties->links() }}
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
