@extends('layouts.app')

@section('title', $announcement->title)

@section('content')
<section class="page-heading mb-4">
    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
        ← Back to Announcements
    </a>

    <h1>{{ $announcement->title }}</h1>

    <p class="text-muted">
        Posted by {{ $announcement->user->name ?? 'Unknown' }}
        · {{ $announcement->created_at->format('F d, Y h:i A') }}
    </p>
</section>

<article class="content-card">
    <div class="announcement-body">
        {!! nl2br(e($announcement->body)) !!}
    </div>

    @if($announcement->attachment)
        <hr>
        <h5>Attachment</h5>

        <a href="{{ asset('storage/' . $announcement->attachment) }}"
           target="_blank"
           rel="noopener noreferrer"
           class="btn btn-outline-primary">
            <i class="bi bi-paperclip"></i> Open Attachment
        </a>
    @endif

    @if(auth()->user()->hasRole('Officer'))
        <hr>
        <a href="{{ route('officer.announcements.edit', $announcement) }}"
           class="btn btn-primary">
            Edit Announcement
        </a>
    @endif
</article>
@endsection
