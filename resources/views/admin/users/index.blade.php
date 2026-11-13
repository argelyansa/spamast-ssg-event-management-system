
@extends('layouts.app')

@section('title', 'User Management')

@section('content')

    <section class="page-heading">
        <h1>User Management</h1>
        <p>Manage Officer, Adviser, and Student accounts.</p>
    </section>

    <section class="content-card">

        <div class="table-heading">
            <h2>Registered Users</h2>

            <a href="{{ route('admin.users.create') }}" class="add-button">
                + Add New User
            </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $users->firstItem() + $loop->index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge">
                                    {{ $user->role->name ?? 'No Role' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="view-button">
                                        View
                                    </a>

                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="edit-button">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this user account?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="delete-button">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-message">
                                No user accounts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $users->links() }}
        </div>

    </section>

@endsection

@push('styles')
<style>
    .table-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .table-heading h2 {
        color: #0B1120;
        font-size: 20px;
    }

    .add-button,
    .view-button,
    .edit-button {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: bold;
    }

    .add-button {
        background: #3B82F6;
        color: white;
    }

    .view-button {
        background: #E0F2FE;
        color: #075985;
    }

    .edit-button {
        background: #DBEAFE;
        color: #1D4ED8;
    }

    .delete-button {
        background: #FEE2E2;
        color: #991B1B;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: bold;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 750px;
    }

    th, td {
        padding: 13px 12px;
        text-align: left;
        border-bottom: 1px solid #E2E8F0;
        font-size: 14px;
    }

    th {
        background: #F1F5F9;
        color: #1E293B;
    }

    td {
        color: #475569;
    }

    .role-badge {
        background: #DBEAFE;
        color: #1D4ED8;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .empty-message {
        text-align: center;
        padding: 30px;
        color: #64748B;
    }

    .pagination-container {
        margin-top: 20px;
    }
</style>
@endpush
