@extends('layouts.app')

@section('title', 'Create Announcement')

@section('content')
<section class="page-heading mb-4">
    <h1>Create Announcement</h1>
    <p>Publish an announcement for SSG users.</p>
</section>

<div class="content-card">
    <form action="{{ route('officer.announcements.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Announcement Title</label>
            <input type="text"
                   name="title"
                   id="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}"
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
                      required>{{ old('body') }}</textarea>

            @error('body')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="attachment" class="form-label">Attachment (Optional)</label>
            <input type="file"
                   name="attachment"
                   id="attachment"
                   class="form-control @error('attachment') is-invalid @enderror"
                   accept=".pdf,.jpg,.jpeg,.png,.webp">

            <small class="text-muted">
                Accepted formats: PDF, JPG, JPEG, PNG, WEBP. Maximum size: 5 MB.
            </small>

            @error('attachment')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox"
                   id="show_on_homepage" name="show_on_homepage"
                   value="1" {{ old('show_on_homepage') ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="show_on_homepage">
                Show on Public Homepage
            </label>
            <div class="form-text">
                Enable this to display the announcement on the public SPAMAST SSG homepage.
            </div>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                Publish Announcement
            </button>

            <a href="{{ route('announcements.index') }}"
               class="btn btn-outline-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
