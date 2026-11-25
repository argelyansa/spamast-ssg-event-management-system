
@extends('layouts.app')

@section('title', 'Event Attendance Report')

@push('styles')
<style>
    @media print {
        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        html,
        body {
            background: #fff !important;
            font-size: 11px !important;
            color: #000 !important;
        }

        .no-print,
        .app-header,
        .app-footer {
            display: none !important;
        }

        .page-container {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
            border-radius: 5px !important;
            margin-bottom: 12px !important;
            break-inside: avoid;
        }

        .card-body {
            padding: 12px !important;
        }

        .row {
            display: flex !important;
            flex-wrap: nowrap !important;
        }

        .row > [class*="col-"] {
            flex: 1 1 0 !important;
            width: 33.333% !important;
        }

        .table-responsive {
            overflow: visible !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 9px !important;
        }

        th,
        td {
            padding: 5px !important;
            border: 1px solid #bbb !important;
            color: #000 !important;
            vertical-align: middle !important;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        tr {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .student-records {
            break-before: page;
            page-break-before: always;
        }

        .student-records .card {
            break-inside: auto;
        }

        .student-records h5 {
            break-after: avoid;
            page-break-after: avoid;
        }

        .badge {
            border: 1px solid #999 !important;
            color: #000 !important;
            background: #fff !important;
        }

        h2,
        h3,
        h5 {
            color: #000 !important;
        }
    }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 no-print">
    <div>
        <h2 class="fw-bold mb-1">Event Attendance Report</h2>
        <p class="text-muted mb-0">Detailed attendance and penalty summary.</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>

        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer me-1"></i> Print Report
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h3 class="fw-bold">{{ $event->title }}</h3>

        <div class="row g-3 mt-2">
            <div class="col-md-4">
                <span class="text-muted">Event Date</span>
                <div class="fw-semibold">
                    {{ $event->event_date?->format('F d, Y') ?? 'N/A' }}
                </div>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Venue</span>
                <div class="fw-semibold">{{ $event->venue }}</div>
            </div>

            <div class="col-md-4">
                <span class="text-muted">Day Type</span>
                <div class="fw-semibold">{{ ucfirst($event->day_type) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted">Total Students</div>
                <h2 class="fw-bold text-primary">
                    {{ $grandTotals->total_students ?? 0 }}
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted">Male Students</div>
                <h2 class="fw-bold">
                    {{ $grandTotals->male_count ?? 0 }}
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted">Female Students</div>
                <h2 class="fw-bold">
                    {{ $grandTotals->female_count ?? 0 }}
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">
            <i class="bi bi-bar-chart me-2"></i>
            Attendance Summary by Institute and Program
        </h5>

        @if ($programSummaries->isEmpty())
            <p class="text-muted text-center py-4 mb-0">
                No checked-in attendance records found for this event.
            </p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Institute</th>
                            <th>Program</th>
                            <th class="text-center">Male</th>
                            <th class="text-center">Female</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($programSummaries as $summary)
                            <tr>
                                <td>
                                    <strong>{{ $summary->institute_code }}</strong>
                                    <div class="small text-muted">
                                        {{ $summary->institute_name }}
                                    </div>
                                </td>

                                <td>
                                    <strong>{{ $summary->program_code }}</strong>
                                    <div class="small text-muted">
                                        {{ $summary->program_name }}
                                    </div>
                                </td>

                                <td class="text-center">
                                    {{ $summary->male_count }}
                                </td>

                                <td class="text-center">
                                    {{ $summary->female_count }}
                                </td>

                                <td class="text-center fw-bold">
                                    {{ $summary->total_students }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="2">Grand Total</td>
                            <td class="text-center">
                                {{ $grandTotals->male_count ?? 0 }}
                            </td>
                            <td class="text-center">
                                {{ $grandTotals->female_count ?? 0 }}
                            </td>
                            <td class="text-center">
                                {{ $grandTotals->total_students ?? 0 }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Individual student records start on a new printed page --}}
<div class="card border-0 shadow-sm student-records">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">
            <i class="bi bi-people me-2"></i>
            Individual Student Attendance Records
        </h5>

        @if ($studentRecords->isEmpty())
            <p class="text-muted text-center py-4 mb-0">
                No student attendance records found.
            </p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Student No.</th>
                            <th>Student Name</th>
                            <th>Program</th>
                            <th class="text-center">Sessions Attended</th>
                            <th class="text-center">Missed Scans</th>
                            <th>Fine</th>
                            <th>Sanction</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($studentRecords as $student)
                            <tr>
                                <td>{{ $student->student_no }}</td>
                                <td>{{ $student->student_name }}</td>
                                <td>{{ $student->program_code }}</td>

                                <td class="text-center">
                                    {{ $student->attended_sessions }}
                                </td>

                                <td class="text-center">
                                    {{ $student->missed_scans ?? 0 }}
                                </td>

                                <td>
                                    ₱{{ number_format((float) ($student->amount ?? 0), 2) }}
                                </td>

                                <td>{{ $student->sanction ?? 'None' }}</td>

                                <td>
                                    @if ($student->status)
                                        <span class="badge
                                            {{ $student->status === 'paid' ? 'bg-success' :
                                                ($student->status === 'served' ? 'bg-info text-dark' : 'bg-warning text-dark') }}">
                                            {{ ucfirst($student->status) }}
                                        </span>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
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
