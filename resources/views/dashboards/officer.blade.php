@extends('layouts.app')

@section('title', 'Officer Dashboard')

@section('content')

    @include('components.weather')

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
        <a href="{{ route('officer.events.index') }}" class="content-card">
            <h3>
                <i class="bi bi-clipboard-check me-2"></i>
                Attendance Monitoring
            </h3>
            <p>Monitor student event attendance and check-ins.</p>
            <span class="card-link">Select an event to monitor attendance →</span>
        </a>

        {{-- Fines and Sanctions --}}
        <a href="{{ route('officer.events.index') }}" class="content-card">
            <h3>
                <i class="bi bi-cash-coin me-2"></i>
                Fines and Sanctions
            </h3>
            <p>Manage attendance-related fines and sanctions.</p>
            <span class="card-link">Select an event to manage penalties →</span>
        </a>

        {{-- Announcements --}}
        <a href="{{ route('announcements.index') }}" class="content-card">
            <h3>
                <i class="bi bi-megaphone-fill me-2"></i>
                Announcements
            </h3>
            <p>Publish and manage important SSG announcements.</p>
            <span class="card-link">Open Announcements →</span>
        </a>

        {{-- Reports --}}
        <a href="{{ route('reports.index') }}" class="content-card">
            <h3>
                <i class="bi bi-file-earmark-bar-graph me-2"></i>
                Reports
            </h3>
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
            <article class="announcement-card">
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

    .announcement-card,
    .empty-announcements {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 14px;
    }

    .announcement-card h3 {
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .announcement-card h3 a {
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
