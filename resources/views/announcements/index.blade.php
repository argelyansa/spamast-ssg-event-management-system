@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<div class="announcement-page">

    <section class="announcement-header">
        <div>
            <span class="announcement-eyebrow">SPAMAST SSG • OFFICIAL UPDATES</span>
            <h1>Announcements</h1>
            <p>Stay informed with the latest news, reminders, and updates from your SSG.</p>
        </div>

        <div class="announcement-header-icon">
            <i class="bi bi-megaphone-fill"></i>
        </div>

        @if(auth()->user()->hasRole('Officer'))
            <a href="{{ route('officer.announcements.create') }}"
               class="announcement-create-btn">
                <i class="bi bi-plus-circle"></i>
                Create Announcement
            </a>
        @endif
    </section>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <section class="announcement-content">

        <div class="announcement-section-heading">
            <div>
                <h2>Latest Updates</h2>
                <p>Announcements and important information</p>
            </div>
            <span class="announcement-total">
                {{ $announcements->total() }} Posts
            </span>
        </div>

        @if($announcements->isEmpty())

            <div class="announcement-empty">
                <div class="announcement-empty-icon">
                    <i class="bi bi-megaphone"></i>
                </div>

                <h3>No Announcements Yet</h3>

                <p>
                    There are currently no announcements to display.
                    Please check back later for the latest SSG updates.
                </p>

                <span class="announcement-empty-note">
                    Stay connected. Stay informed.
                </span>
            </div>

        @else

            <div class="announcement-feed">
                @foreach($announcements as $announcement)
                    <article class="announcement-item">

                        <div class="announcement-item-top">
                            <div class="announcement-item-icon">
                                <i class="bi bi-megaphone-fill"></i>
                            </div>

                            <div class="announcement-item-meta">
                                <span class="announcement-label">SSG ANNOUNCEMENT</span>

                                <div class="announcement-date">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $announcement->created_at->format('F d, Y') }}
                                    <span>•</span>
                                    {{ $announcement->created_at->format('h:i A') }}
                                </div>
                            </div>
                        </div>

                        <h3>{{ $announcement->title }}</h3>

                        <p class="announcement-author">
                            <i class="bi bi-person-circle"></i>
                            Posted by {{ $announcement->user->name ?? 'Unknown' }}
                        </p>

                        <div class="announcement-body">
                            {{ \Illuminate\Support\Str::limit($announcement->body, 180) }}
                        </div>

                        @if($announcement->attachment)
                            <div class="announcement-attachment">
                                <i class="bi bi-paperclip"></i>
                                Attachment available
                            </div>
                        @endif

                        <div class="announcement-actions">
                            <a href="{{ route('announcements.show', $announcement) }}"
                               class="announcement-view-btn">
                                Read Announcement
                                <i class="bi bi-arrow-right"></i>
                            </a>

                            @if(auth()->user()->hasRole('Officer'))
                                <a href="{{ route('officer.announcements.edit', $announcement) }}"
                                   class="announcement-edit-btn">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <form action="{{ route('officer.announcements.destroy', $announcement) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="announcement-delete-btn">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            @endif
                        </div>

                    </article>
                @endforeach
            </div>

            <div class="announcement-pagination">
                {{ $announcements->links() }}
            </div>

        @endif

    </section>

</div>

<style>
.announcement-page {
    max-width: 1100px;
    margin: 0 auto;
    padding: 25px 20px 45px;
    color: #172b46;
}

.announcement-header {
    position: relative;
    overflow: hidden;
    background: linear-gradient(120deg, #102b50, #1d4775);
    border-radius: 16px;
    padding: 35px;
    color: white;
    margin-bottom: 28px;
}

.announcement-eyebrow {
    color: #bdd6f4;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.8px;
}

.announcement-header h1 {
    color: white;
    font-size: 30px;
    font-weight: 700;
    margin: 10px 0;
}

.announcement-header p {
    color: #dbeafe;
    font-size: 14px;
    max-width: 550px;
    margin: 0;
    line-height: 1.7;
}

.announcement-header-icon {
    position: absolute;
    right: 45px;
    top: 25px;
    font-size: 75px;
    color: rgba(255,255,255,.16);
}

.announcement-create-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: white;
    color: #17385f;
    padding: 11px 17px;
    border-radius: 8px;
    margin-top: 22px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
}

.announcement-create-btn:hover {
    background: #eaf2fc;
    color: #102b50;
}

.announcement-content {
    background: white;
    border: 1px solid #e3eaf2;
    border-radius: 15px;
    padding: 25px;
}

.announcement-section-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 22px;
}

.announcement-section-heading h2 {
    font-size: 20px;
    font-weight: 700;
    margin: 0 0 5px;
}

.announcement-section-heading p {
    color: #718096;
    font-size: 13px;
    margin: 0;
}

.announcement-total {
    background: #edf3fa;
    color: #244d79;
    border-radius: 20px;
    padding: 8px 13px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.announcement-empty {
    text-align: center;
    padding: 55px 20px;
    border: 1px dashed #cbd8e7;
    border-radius: 13px;
    background: #f8fafd;
}

.announcement-empty-icon {
    width: 70px;
    height: 70px;
    display: grid;
    place-items: center;
    margin: 0 auto 18px;
    background: #eaf2fc;
    color: #244d79;
    border-radius: 50%;
    font-size: 30px;
}

.announcement-empty h3 {
    color: #17385f;
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 9px;
}

.announcement-empty p {
    color: #718096;
    font-size: 14px;
    max-width: 430px;
    line-height: 1.7;
    margin: 0 auto 14px;
}

.announcement-empty-note {
    color: #244d79;
    font-size: 12px;
    font-weight: 600;
}

.announcement-feed {
    display: grid;
    gap: 18px;
}

.announcement-item {
    border: 1px solid #e4eaf1;
    border-radius: 13px;
    padding: 23px;
    transition: box-shadow .2s, border-color .2s;
}

.announcement-item:hover {
    border-color: #b8cce3;
    box-shadow: 0 5px 16px rgba(20,45,75,.06);
}

.announcement-item-top {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 17px;
}

.announcement-item-icon {
    width: 43px;
    height: 43px;
    display: grid;
    place-items: center;
    border-radius: 11px;
    background: #eaf2fc;
    color: #244d79;
    font-size: 19px;
}

.announcement-label {
    display: block;
    color: #244d79;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 5px;
}

.announcement-date {
    color: #718096;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.announcement-item h3 {
    color: #17385f;
    font-size: 19px;
    font-weight: 700;
    margin: 0 0 8px;
}

.announcement-author {
    color: #718096;
    font-size: 12px;
    margin-bottom: 13px;
}

.announcement-body {
    color: #475569;
    font-size: 14px;
    line-height: 1.8;
    white-space: pre-line;
}

.announcement-attachment {
    display: inline-flex;
    gap: 7px;
    align-items: center;
    margin-top: 14px;
    padding: 7px 11px;
    background: #f0f5fb;
    color: #244d79;
    border-radius: 7px;
    font-size: 12px;
}

.announcement-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    border-top: 1px solid #e9eef4;
    padding-top: 16px;
    margin-top: 18px;
}

.announcement-actions form {
    margin: 0;
}

.announcement-view-btn,
.announcement-edit-btn,
.announcement-delete-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 13px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
}

.announcement-view-btn {
    background: #173f6b;
    color: white;
}

.announcement-view-btn:hover {
    background: #102b50;
    color: white;
}

.announcement-edit-btn {
    background: #edf3fa;
    color: #244d79;
}

.announcement-edit-btn:hover {
    background: #dce8f5;
}

.announcement-delete-btn {
    background: #fff0f0;
    color: #b42323;
    border: none;
}

.announcement-delete-btn:hover {
    background: #ffe0e0;
}

.announcement-pagination {
    margin-top: 24px;
}

@media (max-width: 600px) {
    .announcement-page {
        padding: 15px 12px 30px;
    }

    .announcement-header {
        padding: 25px 20px;
    }

    .announcement-header h1 {
        font-size: 25px;
    }

    .announcement-header-icon {
        right: 20px;
        top: 20px;
        font-size: 48px;
    }

    .announcement-content {
        padding: 17px 13px;
    }

    .announcement-section-heading {
        align-items: flex-start;
    }

    .announcement-item {
        padding: 17px;
    }

    .announcement-item h3 {
        font-size: 17px;
    }
}
</style>
@endsection
