@extends('layouts.app')

@section('title', 'Edit Announcement')

@section('content')
<section class="page-heading mb-4">
    <h1>Edit Announcement</h1>
    <p>Update the announcement details.</p>
</section>

<div class="content-card">
    <form action="{{ route('officer.announcements.update', $announcement) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Announcement Title</label>
            <input type="text"
                   name="title"
                   id="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $announcement->title) }}"
                   maxlength="255"
                   required>

            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="body" class="form-label">Announcement Content</label>
            <textarea name="body"
                      id="body"
                      rows="8"
                      class="form-control @error('body') is-invalid @enderror"
                      required>{{ old('body', $announcement->body) }}</textarea>

            @error('body')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        @if($announcement->attachment)
            <div class="mb-3">
                <p class="mb-1">Current attachment:</p>
                <a href="{{ asset('storage/' . $announcement->attachment) }}"
                   target="_blank"
                   rel="noopener noreferrer">
                    View current attachment
                </a>
            </div>
        @endif

        <div class="mb-3">
            <label for="attachment" class="form-label">Replace Attachment (Optional)</label>
            <input type="file"
                   name="attachment"
                   id="attachment"
                   class="form-control @error('attachment') is-invalid @enderror"
                   accept=".pdf,.jpg,.jpeg,.png,.webp">

            <small class="text-muted">
                Accepted formats: PDF, JPG, JPEG, PNG, WEBP. Maximum size: 5 MB.
                Leave empty to keep the current attachment.
            </small>

            @error('attachment')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                Save Changes
            </button>

            <a href="{{ route('announcements.index') }}"
               class="btn btn-outline-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
