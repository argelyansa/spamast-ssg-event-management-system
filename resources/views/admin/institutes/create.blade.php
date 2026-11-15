
@extends('layouts.app')

@section('title', 'Add Institute')

@section('content')
    <section class="page-heading">
        <h1>Add New Institute</h1>
        <p>Register a new SPAMAST institute.</p>
    </section>

    <div class="content-card">
        <form action="{{ route('admin.institutes.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="code">Institute Code</label>
                <input
                    type="text"
                    id="code"
                    name="code"
                    value="{{ old('code') }}"
                    placeholder="Example: IARS"
                    required
                >
                @error('code')
                    <small class="error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Institute Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter institute name"
                    required
                >
                @error('name')
                    <small class="error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Save Institute</button>
                <a href="{{ route('admin.institutes.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('styles')
<style>
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #1E293B;
    }

    .form-group input {
        width: 100%;
        max-width: 550px;
        padding: 12px;
        border: 1px solid #CBD5E1;
        border-radius: 6px;
        font-size: 14px;
    }

    .error {
        display: block;
        color: #DC2626;
        margin-top: 6px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .btn-primary, .btn-cancel {
        padding: 11px 17px;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        cursor: pointer;
        font-weight: bold;
        font-size: 14px;
    }

    .btn-primary {
        background: #3B82F6;
        color: white;
    }

    .btn-cancel {
        background: #E2E8F0;
        color: #1E293B;
    }
</style>
@endpush
