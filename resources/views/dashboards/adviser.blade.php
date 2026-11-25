@extends('layouts.app')

@section('title', 'Adviser Dashboard')

@section('content')

    @include('components.weather')

    <section class="page-heading">
        <h1>Welcome, {{ auth()->user()->name }}!</h1>
        <p>Adviser Dashboard — Event Management System</p>
    </section>

    <section class="dashboard-grid">

        <div class="content-card">
            <h3>Events</h3>
            <p>View SSG events and scheduled activities.</p>
        </div>

        <a href="{{ route('announcements.index') }}" class="content-card announcement-card">
            <h3>Announcements</h3>
            <p>Read important SSG announcements.</p>
            <span class="card-link">View Announcements →</span>
        </a>

        <a href="{{ route('adviser.attendance.events') }}"
           class="content-card attendance-card">
            <h3>Attendance Records</h3>
            <p>Review student attendance by institute and program.</p>
        </a>

        <div class="content-card">
            <h3>Fines and Sanctions</h3>
            <p>Review student fines and sanctions.</p>
        </div>

        <a href="{{ route('reports.index') }}" class="content-card report-card">
            <h3>Reports</h3>
            <p>View event and attendance reports.</p>
            <span class="card-link">Open Reports →</span>
        </a>

    </section>

@endsection

@push('styles')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
    }

    .dashboard-grid h3 {
        color: #3B82F6;
        margin-bottom: 12px;
    }

    .dashboard-grid p {
        color: #64748B;
        line-height: 1.6;
    }

    .attendance-card,
    .report-card,
    .announcement-card {
        display: block;
        text-decoration: none;
        cursor: pointer;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 25px;
        color: inherit;
    }

    .attendance-card:hover,
    .report-card:hover,
    .announcement-card:hover {
        text-decoration: none;
        border-color: #3B82F6;
    }

    .card-link {
        display: inline-block;
        color: #2563EB;
        font-size: 14px;
        font-weight: 600;
        margin-top: 8px;
    }
</style>
@endpush
