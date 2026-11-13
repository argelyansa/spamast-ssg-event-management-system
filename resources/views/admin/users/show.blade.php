
@extends('layouts.app')

@section('title', 'User Details')

@section('content')

    <section class="page-heading">
        <h1>User Details</h1>
        <p>View the information of this registered account.</p>
    </section>

    <section class="content-card">

        <div class="detail-row">
            <strong>Full Name</strong>
            <span>{{ $user->name }}</span>
        </div>

        <div class="detail-row">
            <strong>Email Address</strong>
            <span>{{ $user->email }}</span>
        </div>

        <div class="detail-row">
            <strong>Account Role</strong>
            <span class="role-badge">
                {{ $user->role->name ?? 'No Role' }}
            </span>
        </div>

        <div class="detail-row">
            <strong>Institute</strong>
            <span>
                {{ $user->student?->program?->institute?->name ?? 'Not assigned' }}
            </span>
        </div>

        <div class="detail-row">
            <strong>Program</strong>
            <span>
                {{ $user->student?->program?->name ?? 'Not assigned' }}
            </span>
        </div>

        <div class="detail-row">
            <strong>Account Created</strong>
            <span>{{ $user->created_at?->format('F d, Y h:i A') }}</span>
        </div>

        <div class="detail-row">
            <strong>Last Updated</strong>
            <span>{{ $user->updated_at?->format('F d, Y h:i A') }}</span>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.users.edit', $user) }}" class="edit-button">
                Edit Account
            </a>

            <a href="{{ route('admin.users.index') }}" class="back-button">
                Back to User List
            </a>
        </div>

    </section>

@endsection

@push('styles')
<style>
    .detail-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 16px 5px;
        border-bottom: 1px solid #E2E8F0;
    }

    .detail-row strong {
        color: #1E293B;
    }

    .detail-row span {
        color: #64748B;
        text-align: right;
    }

    .role-badge {
        background: #DBEAFE;
        color: #1D4ED8 !important;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 25px;
    }

    .edit-button,
    .back-button {
        display: inline-block;
        padding: 11px 18px;
        border-radius: 6px;
        font-weight: bold;
        text-decoration: none;
        font-size: 14px;
    }

    .edit-button {
        background: #3B82F6;
        color: white;
    }

    .back-button {
        background: #E2E8F0;
        color: #1E293B;
    }

    @media (max-width: 600px) {
        .detail-row {
            flex-direction: column;
            gap: 6px;
        }

        .detail-row span {
            text-align: left;
        }
    }
</style>
@endpush
