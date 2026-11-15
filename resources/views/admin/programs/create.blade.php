@extends('layouts.app')

@section('title', 'Add Program')

@section('content')
<div class="page-header">
    <div>
        <h1>Add New Program</h1>
        <p>Create an academic program under an institute.</p>
    </div>
</div>

<div class="content-card">
    <form action="{{ route('admin.programs.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="institute_id">Institute</label>
            <select id="institute_id" name="institute_id" required>
                <option value="">Select Institute</option>
                @foreach ($institutes as $institute)
                    <option value="{{ $institute->id }}"
                        {{ old('institute_id') == $institute->id ? 'selected' : '' }}>
                        {{ $institute->code }} - {{ $institute->name }}
                    </option>
                @endforeach
            </select>
            @error('institute_id')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="code">Program Code</label>
            <input
                type="text"
                id="code"
                name="code"
                value="{{ old('code') }}"
                placeholder="Example: BSIT"
                maxlength="50"
                required
            >
            @error('code')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="name">Program Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Enter program name"
                maxlength="255"
                required
            >
            @error('name')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Save Program</button>
            <a href="{{ route('admin.programs.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .page-header {
        margin-bottom: 24px;
    }

    .page-header h1 {
        color: #0B1120;
        margin-bottom: 6px;
    }

    .page-header p {
        color: #64748B;
        margin: 0;
    }

    .content-card {
        max-width: 700px;
        background: #FFFFFF;
        padding: 28px;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #1E293B;
        font-weight: 600;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        background: #FFFFFF;
        color: #0B1120;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #3B82F6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    .error-message {
        display: block;
        color: #DC2626;
        margin-top: 6px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .btn-primary,
    .btn-secondary {
        display: inline-block;
        padding: 11px 17px;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        cursor: pointer;
        font-weight: 600;
    }

    .btn-primary {
        background: #3B82F6;
        color: #FFFFFF;
    }

    .btn-secondary {
        background: #E2E8F0;
        color: #1E293B;
    }
</style>
@endpush
