@extends('layouts.app')

@section('title', 'Officer Dashboard')

@section('content')

    <section class="page-heading">
        <h1>Welcome, {{ auth()->user()->name }}!</h1>
        <p>Officer Dashboard — Event Management System</p>
    </section>

    <section class="dashboard-grid">

        <div class="content-card">
            <h3>Student Management</h3>
            <p>View and manage student records.</p>
        </div>

        <div class="content-card">
            <h3>Event Management</h3>
            <p>Create, update, and manage SSG events.</p>
        </div>

        <div class="content-card">
            <h3>Attendance Monitoring</h3>
            <p>Monitor student event attendance and check-ins.</p>
        </div>

        <div class="content-card">
            <h3>Fines and Sanctions</h3>
            <p>Manage attendance-related fines and sanctions.</p>
        </div>

        <div class="content-card">
            <h3>Announcements</h3>
            <p>Publish important SSG announcements.</p>
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
