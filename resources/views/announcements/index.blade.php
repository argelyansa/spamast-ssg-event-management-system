@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<section class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1>Announcements</h1>
            <p>View the latest SSG announcements and updates.</p>
        </div>

        @if(auth()->user()->hasRole('Officer'))
            <a href="{{ route('officer.announcements.create') }}" class="btn btn-primary">
                Create Announcement
            </a>
        @endif
    </div>
</section>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($announcements->isEmpty())
    <div class="content-card">
        <h3>No Announcements Yet</h3>
        <p>There are currently no announcements to display.</p>
    </div>
@else
    <div class="announcement-list">
        @foreach($announcements as $announcement)
            <article class="content-card announcement-card">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h3>{{ $announcement->title }}</h3>
                        <p class="text-muted small mb-2">
                            Posted by {{ $announcement->user->name ?? 'Unknown' }}
                            · {{ $announcement->created_at->format('F d, Y h:i A') }}
                        </p>
                    </div>
                </div>

                <p>{{ \Illuminate\Support\Str::limit($announcement->body, 120) }}</p>

                @if($announcement->attachment)
                    <p class="small">
                        <i class="bi bi-paperclip"></i> Attachment available
                    </p>
                @endif

                <div class="d-flex flex-wrap gap-2 mt-3">
                    <a href="{{ route('announcements.show', $announcement) }}"
                       class="btn btn-outline-primary btn-sm">
                        View Announcement
                    </a>

                    @if(auth()->user()->hasRole('Officer'))
                        <a href="{{ route('officer.announcements.edit', $announcement) }}"
                           class="btn btn-outline-secondary btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('officer.announcements.destroy', $announcement) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                Delete
                            </button>
                        </form>
                    @endif
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $announcements->links() }}
    </div>
@endif
@endsection
