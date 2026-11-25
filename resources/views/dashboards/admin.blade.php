@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    @include('components.weather')
<div class="dashboard-header">
    <h1>Admin Dashboard</h1>
    <p>Welcome to the SPAMAST SSG Event Management System.</p>
</div>

<div class="dashboard-grid">

    <a href="{{ route('admin.users.index') }}" class="content-card dashboard-card">
        <div class="card-icon">👥</div>
        <h3>User Management</h3>
        <p>Manage Officer, Adviser, and Student accounts.</p>
        <span class="card-link">Manage Users →</span>
    </a>

    <a href="{{ route('admin.institutes.index') }}" class="content-card dashboard-card">
        <div class="card-icon">🏛️</div>
        <h3>Institute Management</h3>
        <p>Manage SPAMAST institutes.</p>
        <span class="card-link">Manage Institutes →</span>
    </a>

    <a href="{{ route('admin.programs.index') }}" class="content-card dashboard-card">
        <div class="card-icon">🎓</div>
        <h3>Program Management</h3>
        <p>Manage academic programs under each institute.</p>
        <span class="card-link">Manage Programs →</span>
    </a>

    <div class="content-card dashboard-card disabled-card">
        <div class="card-icon">📅</div>
        <h3>Event Management</h3>
        <p>Event management features will be available soon.</p>
        <span class="card-link">Coming Soon</span>
    </div>

    <div class="content-card dashboard-card disabled-card">
        <div class="card-icon">📊</div>
        <h3>System Overview</h3>
        <p>View system information and activity.</p>
        <span class="card-link">Coming Soon</span>
    </div>

</div>
@endsection

@push('styles')
<style>
    .dashboard-header {
        margin-bottom: 28px;
    }

    .dashboard-header h1 {
        color: #0B1120;
        margin-bottom: 8px;
    }

    .dashboard-header p {
        color: #64748B;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
    }

    .dashboard-card {
        display: block;
        background: #FFFFFF;
        padding: 25px;
        border-radius: 14px;
        text-decoration: none;
        color: #0B1120;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    a.dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.12);
    }

    .card-icon {
        font-size: 32px;
        margin-bottom: 14px;
    }

    .dashboard-card h3 {
        margin-bottom: 8px;
        color: #0B1120;
    }

    .dashboard-card p {
        color: #64748B;
        line-height: 1.5;
    }

    .card-link {
        display: inline-block;
        margin-top: 12px;
        color: #3B82F6;
        font-weight: 600;
    }

    .disabled-card {
        opacity: 0.75;
    }

    @media (max-width: 600px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
