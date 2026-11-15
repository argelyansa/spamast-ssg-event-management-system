
@extends('layouts.app')

@section('title', 'Institute Management')

@section('content')
    <section class="page-heading">
        <h1>Institute Management</h1>
        <p>Manage SPAMAST institutes and their academic information.</p>
    </section>

    <div class="content-card">
        <div class="toolbar">
            <form action="{{ route('admin.institutes.index') }}" method="GET" class="search-form">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search institute name or code..."
                >
                <button type="submit" class="btn btn-primary">Search</button>

                @if ($search)
                    <a href="{{ route('admin.institutes.index') }}" class="btn btn-secondary">Clear</a>
                @endif
            </form>

            <a href="{{ route('admin.institutes.create') }}" class="btn btn-primary">
                + Add Institute
            </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Institute Code</th>
                        <th>Institute Name</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($institutes as $institute)
                        <tr>
                            <td>{{ $institutes->firstItem() + $loop->index }}</td>
                            <td>{{ $institute->code }}</td>
                            <td>{{ $institute->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($institute->created_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.institutes.edit', $institute->id) }}"
                                       class="btn btn-small btn-edit">Edit</a>

                                    <form
                                        action="{{ route('admin.institutes.destroy', $institute->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this institute?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-small btn-delete">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-message">
                                No institutes found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $institutes->links() }}
        </div>
    </div>
@endsection

@push('styles')
<style>
    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .search-form {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .search-form input {
        min-width: 230px;
        padding: 10px 12px;
        border: 1px solid #CBD5E1;
        border-radius: 6px;
    }

    .btn {
        display: inline-block;
        border: none;
        border-radius: 6px;
        padding: 10px 14px;
        text-decoration: none;
        cursor: pointer;
        font-size: 13px;
        font-weight: bold;
    }

    .btn-primary {
        background: #3B82F6;
        color: white;
    }

    .btn-secondary {
        background: #64748B;
        color: white;
    }

    .btn-edit {
        background: #DBEAFE;
        color: #1D4ED8;
    }

    .btn-delete {
        background: #FEE2E2;
        color: #B91C1C;
    }

    .btn-small {
        padding: 7px 10px;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 650px;
    }

    th, td {
        padding: 13px 12px;
        border-bottom: 1px solid #E2E8F0;
        text-align: left;
        font-size: 14px;
    }

    th {
        background: #F1F5F9;
        color: #0B1120;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .action-buttons form {
        margin: 0;
    }

    .empty-message {
        text-align: center;
        color: #64748B;
        padding: 25px;
    }

    .pagination {
        margin-top: 20px;
    }

    .pagination nav {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }
</style>
@endpush
