@extends('layouts.app')

@section('title', 'Adviser Dashboard')

@section('content')

    @include('components.weather')

    <section class="page-heading mb-4">
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

    {{-- Latest Announcements --}}
    <section class="latest-announcements mt-5">
        <div class="section-heading">
            <div>
                <h2>Latest Announcements</h2>
                <p>Stay updated with the latest SSG announcements.</p>
            </div>

            <a href="{{ route('announcements.index') }}" class="view-all-link">
                View All →
            </a>
        </div>

        @forelse ($latestAnnouncements as $announcement)
            <article class="announcement-card-item">
                <h3>
                    <a href="{{ route('announcements.show', $announcement) }}">
                        {{ $announcement->title }}
                    </a>
                </h3>

                <p class="announcement-meta">
                    Posted by {{ $announcement->user->name ?? 'Unknown' }}
                    · {{ $announcement->created_at->format('F d, Y h:i A') }}
                </p>

                <p class="announcement-body">
                    {{ \Illuminate\Support\Str::limit($announcement->body, 120) }}
                </p>

                <a href="{{ route('announcements.show', $announcement) }}"
                   class="read-more-link">
                    Read Announcement →
                </a>
            </article>
        @empty
            <div class="empty-announcements">
                <p>No announcements have been published yet.</p>
            </div>
        @endforelse
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

    .latest-announcements {
        margin-bottom: 30px;
    }

    .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .section-heading h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 5px;
    }

    .section-heading p,
    .announcement-meta {
        color: #64748B;
        font-size: 14px;
    }

    .view-all-link,
    .read-more-link {
        color: #2563EB;
        font-weight: 600;
        text-decoration: none;
    }

    .announcement-card-item,
    .empty-announcements {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 14px;
    }

    .announcement-card-item h3 {
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .announcement-card-item h3 a {
        color: #2563EB;
        text-decoration: none;
    }

    .announcement-body {
        color: #475569;
        line-height: 1.6;
        margin-bottom: 10px;
    }

    .empty-announcements p {
        color: #64748B;
        margin: 0;
    }

    @media (max-width: 576px) {
        .section-heading {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
@endpush
