@extends('layouts.app')

@section('title', 'Adviser Dashboard')

@section('content')

    <section class="page-heading">
        <h1>Welcome, {{ auth()->user()->name }}!</h1>
        <p>Adviser Dashboard — Event Management System</p>
    </section>

    <section class="dashboard-grid">

        <div class="content-card">
            <h3>Events</h3>
            <p>View SSG events and scheduled activities.</p>
        </div>

        <div class="content-card">
            <h3>Announcements</h3>
            <p>Read important SSG announcements.</p>
        </div>

        <div class="content-card">
            <h3>Attendance Records</h3>
            <p>Review student attendance by institute and program.</p>
        </div>

        <div class="content-card">
            <h3>Fines and Sanctions</h3>
            <p>Review student fines and sanctions.</p>
        </div>

        <div class="content-card">
            <h3>Reports</h3>
            <p>View event and attendance reports.</p>
        </div>

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
</style>
@endpush
