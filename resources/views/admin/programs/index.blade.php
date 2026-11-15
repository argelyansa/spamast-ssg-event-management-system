@extends('layouts.app')

@section('title', 'Program Management')

@section('content')
<div class="page-header">
    <div>
        <h1>Program Management</h1>
        <p>Manage academic programs under each institute.</p>
    </div>

    <a href="{{ route('admin.programs.create') }}" class="btn-primary">
        + Add Program
    </a>
</div>

<div class="content-card">
    <form method="GET" action="{{ route('admin.programs.index') }}" class="search-form">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search program or institute..."
        >
        <button type="submit" class="btn-primary">Search</button>
        <a href="{{ route('admin.programs.index') }}" class="btn-secondary">Clear</a>
    </form>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Program Code</th>
                    <th>Program Name</th>
                    <th>Institute Code</th>
                    <th>Institute Name</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($programs as $program)
                    <tr>
                        <td>{{ $programs->firstItem() + $loop->index }}</td>
                        <td>{{ $program->code }}</td>
                        <td>{{ $program->name }}</td>
                        <td>{{ $program->institute_code }}</td>
                        <td>{{ $program->institute_name }}</td>
                        <td class="action-buttons">
                            <a
                                href="{{ route('admin.programs.edit', $program->id) }}"
                                class="btn-secondary"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('admin.programs.destroy', $program->id) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this program?');"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-message">
                            No programs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $programs->links() }}
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
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
        background: #FFFFFF;
        padding: 24px;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
    }

    .search-form {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-form input {
        flex: 1;
        min-width: 220px;
        padding: 11px 14px;
        border: 1px solid #CBD5E1;
        border-radius: 8px;
    }

    .btn-primary,
    .btn-secondary,
    .btn-danger {
        display: inline-block;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
    }

    .btn-primary {
        background: #3B82F6;
        color: #FFFFFF;
    }

    .btn-secondary {
        background: #E2E8F0;
        color: #1E293B;
    }

    .btn-danger {
        background: #DC2626;
        color: #FFFFFF;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th,
    .data-table td {
        padding: 13px 12px;
        border-bottom: 1px solid #E2E8F0;
        text-align: left;
        white-space: nowrap;
    }

    .data-table th {
        background: #F8FAFC;
        color: #1E293B;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-buttons form {
        margin: 0;
    }

    .empty-message {
        text-align: center !important;
        color: #64748B;
        padding: 30px !important;
    }

    .pagination-wrapper {
        margin-top: 20px;
    }
</style>
@endpush
