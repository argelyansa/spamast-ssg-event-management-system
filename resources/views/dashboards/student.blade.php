
@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')


    @include('components.weather')
<section class="page-heading">
        <h1>Welcome, {{ auth()->user()->name }}!</h1>
        <p>Student Dashboard — Event Management System</p>
    </section>

    <section class="dashboard-grid">

        <a href="{{ route('student.events.index') }}" class="content-card dashboard-card">
            <h3>Upcoming Events</h3>
            <p>View upcoming SSG events and activities.</p>
        </a>

        <div class="content-card">
            <h3>Announcements</h3>
            <p>Read the latest SSG announcements.</p>
        </div>

        <div class="content-card">
            <h3>Attendance</h3>
            <p>Check your event attendance records.</p>
        </div>

        <div class="content-card">
            <h3>Fines and Sanctions</h3>
            <p>View your attendance-related fines and sanctions.</p>
        </div>

        <a href="{{ route('profile.edit') }}" class="content-card profile-card">
            <h3>Profile</h3>
            <p>View and update your personal information.</p>
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

    .dashboard-card,
    .profile-card {
        display: block;
        text-decoration: none;
        color: inherit;
        transition: border-color 0.2s;
    }

    .dashboard-card:hover,
    .profile-card:hover {
        border-color: #3B82F6;
    }
</style>
@endpush
