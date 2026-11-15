
@extends('layouts.app')

@section('title', 'Officer Dashboard')

@section('content')

    <section class="page-heading mb-4">
        <h1>Welcome, {{ auth()->user()->name }}!</h1>
        <p>Officer Dashboard — Event Management System</p>
    </section>

    <section class="dashboard-grid">

        {{-- Student Management --}}
        <a href="{{ route('officer.students.index') }}" class="content-card">
            <h3>
                <i class="bi bi-people-fill me-2"></i>
                Student Management
            </h3>
            <p>View and manage student records.</p>
            <span class="card-link">Open Student Management →</span>
        </a>

        {{-- Event Management --}}
        <a href="{{ route('officer.events.index') }}" class="content-card">
            <h3>
                <i class="bi bi-calendar-event-fill me-2"></i>
                Event Management
            </h3>
            <p>Create, update, and manage SSG events.</p>
            <span class="card-link">Open Event Management →</span>
        </a>

        {{-- Attendance Monitoring --}}
        <div class="content-card">
            <h3>
                <i class="bi bi-clipboard-check me-2"></i>
                Attendance Monitoring
            </h3>
            <p>Monitor student event attendance and check-ins.</p>
            <span class="text-muted small">Coming soon</span>
        </div>

        {{-- Fines and Sanctions --}}
        <div class="content-card">
            <h3>
                <i class="bi bi-cash-coin me-2"></i>
                Fines and Sanctions
            </h3>
            <p>Manage attendance-related fines and sanctions.</p>
            <span class="text-muted small">Coming soon</span>
        </div>

        {{-- Announcements --}}
        <div class="content-card">
            <h3>
                <i class="bi bi-megaphone-fill me-2"></i>
                Announcements
            </h3>
            <p>Publish important SSG announcements.</p>
            <span class="text-muted small">Coming soon</span>
        </div>

        {{-- Reports --}}
        <div class="content-card">
            <h3>
                <i class="bi bi-file-earmark-bar-graph me-2"></i>
                Reports
            </h3>
            <p>View event and attendance reports.</p>
            <span class="text-muted small">Coming soon</span>
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

    .content-card {
        display: block;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 25px;
        text-decoration: none;
        color: inherit;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .content-card h3 {
        color: #3B82F6;
        margin-bottom: 12px;
        font-size: 19px;
        font-weight: 700;
    }

    .content-card p {
        color: #64748B;
        line-height: 1.6;
    }

    a.content-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 22px rgba(15, 23, 42, .10);
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
