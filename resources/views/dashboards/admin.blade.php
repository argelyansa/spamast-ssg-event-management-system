
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    <section class="page-heading">
        <h1>Welcome, {{ auth()->user()->name }}!</h1>
        <p>Administrator Dashboard — Event Management System</p>
    </section>

    <section class="dashboard-grid">

        <a href="{{ route('admin.users.index') }}" class="content-card dashboard-card">
            <h3>User Management</h3>
            <p>Manage student, officer, and adviser accounts.</p>
            <span class="card-link">Manage Users →</span>
        </a>

        <div class="content-card dashboard-card">
            <h3>Institute Management</h3>
            <p>Manage SPAMAST institutes and academic programs.</p>
        </div>

        <div class="content-card dashboard-card">
            <h3>Event Management</h3>
            <p>View and manage SSG events and activities.</p>
        </div>

        <div class="content-card dashboard-card">
            <h3>System Overview</h3>
            <p>Access the main administrative features of the system.</p>
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

    .dashboard-card {
        display: block;
        text-decoration: none;
        color: inherit;
        transition: border-color 0.2s, transform 0.2s;
    }

    .dashboard-card h3 {
        color: #3B82F6;
        margin-bottom: 12px;
    }

    .dashboard-card p {
        color: #64748B;
        line-height: 1.6;
    }

    .dashboard-card:hover {
        border-color: #3B82F6;
        transform: translateY(-2px);
    }

    .card-link {
        display: inline-block;
        margin-top: 15px;
        color: #2563EB;
        font-weight: bold;
    }
</style>
@endpush
