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

        <a href="{{ route('announcements.index') }}" class="content-card announcement-card">
            <h3>Announcements</h3>
            <p>Read the latest SSG announcements.</p>
            <span class="card-link">View Announcements →</span>
        </a>

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

    <section class="latest-announcements">
        <div class="announcements-heading">
            <div>
                <h2>Latest Announcements</h2>
                <p>Stay updated with the latest SSG announcements.</p>
            </div>

            <a href="{{ route('announcements.index') }}" class="view-all-link">
                View All →
            </a>
        </div>

        <div class="announcements-list">
            @forelse ($latestAnnouncements as $announcement)
                <article class="announcement-card-item">
                    <h3>
                        <a href="{{ route('announcements.show', $announcement) }}">
                            {{ $announcement->title }}
                        </a>
                    </h3>

                    <p class="announcement-meta">
                        Posted by {{ $announcement->user->name ?? 'Unknown' }}
                        ·
                        {{ $announcement->created_at->format('F d, Y h:i A') }}
                    </p>

                    <p class="announcement-preview">
                        {{ \Illuminate\Support\Str::limit($announcement->body, 120) }}
                    </p>

                    <a href="{{ route('announcements.show', $announcement) }}"
                       class="read-announcement-link">
                        Read Announcement →
                    </a>
                </article>
            @empty
                <div class="empty-announcements">
                    <p>No announcements available yet.</p>
                </div>
            @endforelse
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

    .dashboard-card,
    .profile-card,
    .announcement-card {
        display: block;
        text-decoration: none;
        color: inherit;
        transition: border-color 0.2s;
    }

    .dashboard-card:hover,
    .profile-card:hover,
    .announcement-card:hover {
        border-color: #3B82F6;
    }

    .card-link {
        display: inline-block;
        color: #2563EB;
        font-size: 14px;
        font-weight: 600;
        margin-top: 8px;
    }

    .latest-announcements {
        margin-top: 35px;
    }

    .announcements-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .announcements-heading h2 {
        color: #1E293B;
        margin-bottom: 6px;
    }

    .announcements-heading p {
        color: #64748B;
        margin: 0;
    }

    .view-all-link,
    .read-announcement-link {
        color: #2563EB;
        font-weight: 600;
        text-decoration: none;
    }

    .announcement-card-item {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
    }

    .announcement-card-item h3 {
        margin: 0 0 8px;
    }

    .announcement-card-item h3 a {
        color: #2563EB;
        text-decoration: none;
    }

    .announcement-meta {
        color: #64748B;
        font-size: 13px;
        margin-bottom: 10px;
    }

    .announcement-preview {
        color: #475569;
        line-height: 1.6;
        margin-bottom: 12px;
    }

    .empty-announcements {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 20px;
        color: #64748B;
    }

    @media (max-width: 600px) {
        .announcements-heading {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
@endpush
