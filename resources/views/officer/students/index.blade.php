
@extends('layouts.app')

@section('content')
<style>
    .student-page {
        color: #0B1120;
        width: 100%;
    }

    .student-page .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 26px;
    }

    .student-page h2 {
        margin: 0;
        font-size: 27px;
        font-weight: 700;
    }

    .student-page .subtitle {
        color: #64748B;
        margin-top: 6px;
        margin-bottom: 0;
        font-size: 16px;
    }

    .student-page .primary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #3B82F6;
        color: #FFFFFF;
        padding: 12px 20px;
        border-radius: 9px;
        text-decoration: none;
        font-weight: 600;
        white-space: nowrap;
        transition: background 0.2s;
    }

    .student-page .primary-btn:hover {
        background: #2563EB;
    }

    .student-page .filter-card,
    .student-page .table-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 13px;
        padding: 24px;
        margin-bottom: 22px;
    }

    .student-page .filter-grid {
        display: grid;
        grid-template-columns:
            minmax(180px, 1.2fr)
            minmax(180px, 1.2fr)
            minmax(180px, 1.2fr)
            auto;
        gap: 16px;
        align-items: end;
    }

    .student-page .filter-field {
        min-width: 0;
    }

    .student-page label {
        display: block;
        font-weight: 600;
        margin-bottom: 9px;
        color: #0B1120;
        font-size: 15px;
    }

    .student-page input,
    .student-page select {
        display: block;
        width: 100%;
        min-width: 0;
        height: 49px;
        box-sizing: border-box;
        padding: 11px 13px;
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        background: #FFFFFF;
        color: #0B1120;
        font-size: 15px;
        outline: none;
    }

    .student-page input:focus,
    .student-page select:focus {
        border-color: #3B82F6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    .student-page select {
        cursor: pointer;
    }

    .student-page .filter-help {
        color: #64748B;
        font-size: 12px;
        margin: 7px 0 0;
        line-height: 1.4;
    }

    .student-page .filter-actions {
        display: flex;
        align-items: center;
        gap: 9px;
        white-space: nowrap;
        padding-bottom: 0;
    }

    .student-page .filter-btn,
    .student-page .reset-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 49px;
        box-sizing: border-box;
        padding: 0 17px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }

    .student-page .filter-btn {
        background: #1E293B;
        color: #FFFFFF;
        border: none;
    }

    .student-page .filter-btn:hover {
        background: #334155;
    }

    .student-page .reset-btn {
        background: #E2E8F0;
        color: #0B1120;
    }

    .student-page .reset-btn:hover {
        background: #CBD5E1;
    }

    .student-page .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .student-page table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .student-page th,
    .student-page td {
        padding: 15px 13px;
        text-align: left;
        border-bottom: 1px solid #E2E8F0;
    }

    .student-page th {
        background: #F8FAFC;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .student-page td {
        color: #334155;
        font-size: 14px;
    }

    .student-page tbody tr:hover {
        background: #F8FAFC;
    }

    .student-page .action-link {
        color: #2563EB;
        font-weight: 600;
        text-decoration: none;
        margin-right: 12px;
    }

    .student-page .action-link:hover {
        text-decoration: underline;
    }

    .student-page .delete-btn {
        background: none;
        border: 0;
        color: #DC2626;
        font-weight: 600;
        cursor: pointer;
        padding: 0;
        font-size: 14px;
    }

    .student-page .delete-btn:hover {
        text-decoration: underline;
    }

    .student-page .empty-state {
        text-align: center;
        color: #64748B;
        padding: 42px 20px;
        font-size: 15px;
    }

    .student-page .pagination {
        margin-top: 20px;
    }

    @media (max-width: 1150px) {
        .student-page .filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .student-page .filter-actions {
            grid-column: 1 / -1;
            justify-content: flex-start;
        }
    }

    @media (max-width: 600px) {
        .student-page .page-heading {
            align-items: flex-start;
        }

        .student-page .filter-card,
        .student-page .table-card {
            padding: 17px;
        }

        .student-page .filter-grid {
            grid-template-columns: 1fr;
        }

        .student-page .filter-actions {
            grid-column: auto;
            flex-wrap: wrap;
        }

        .student-page .filter-btn,
        .student-page .reset-btn {
            flex: 1;
        }
    }
</style>

<div class="student-page">

    <div class="page-heading">
        <div>
            <h2>Student Management</h2>
            <p class="subtitle">
                Manage student accounts and academic information.
            </p>
        </div>

        <a href="{{ route('officer.students.create') }}" class="primary-btn">
            + Add Student
        </a>
    </div>

    <div class="filter-card">
        <form method="GET" action="{{ route('officer.students.index') }}">
            <div class="filter-grid">

                <div class="filter-field">
                    <label for="search">Search Student</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Name or school ID number"
                    >
                </div>

                <div class="filter-field">
                    <label for="institute_id">Institute</label>
                    <select name="institute_id" id="institute_id">
                        <option value="">All Institutes</option>

                        @foreach ($institutes as $institute)
                            <option
                                value="{{ $institute->id }}"
                                {{ request('institute_id') == $institute->id ? 'selected' : '' }}
                            >
                                {{ $institute->code }} - {{ $institute->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-field">
                    <label for="program_id">Program</label>
                    <select name="program_id" id="program_id">
                        <option value="">All Programs</option>

                        @foreach ($programs as $program)
                            <option
                                value="{{ $program->id }}"
                                data-institute="{{ $program->institute_id }}"
                                {{ request('program_id') == $program->id ? 'selected' : '' }}
                            >
                                {{ $program->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="filter-btn">
                        Search / Filter
                    </button>

                    <a
                        href="{{ route('officer.students.index') }}"
                        class="reset-btn"
                    >
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </div>

    <div class="table-card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student ID Number (School ID)</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Institute</th>
                        <th>Program</th>
                        <th>Year Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td>
                                {{ $students->firstItem() + $loop->index }}
                            </td>

                            <td>{{ $student->student_no }}</td>

                            <td>
                                {{ $student->user?->name ?? 'No user account' }}
                            </td>

                            <td>
                                {{ $student->user?->email ?? '—' }}
                            </td>

                            <td>
                                {{ $student->program?->institute?->code ?? '—' }}
                            </td>

                            <td>
                                {{ $student->program?->name ?? '—' }}
                            </td>

                            <td>
                                @switch((int) $student->year_level)
                                    @case(1)
                                        1st Year
                                        @break
                                    @case(2)
                                        2nd Year
                                        @break
                                    @case(3)
                                        3rd Year
                                        @break
                                    @case(4)
                                        4th Year
                                        @break
                                    @default
                                        {{ $student->year_level }}
                                @endswitch
                            </td>

                            <td>
                                <a
                                    href="{{ route('officer.students.edit', $student) }}"
                                    class="action-link"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('officer.students.destroy', $student) }}"
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this student account?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="delete-btn">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                No students found. Try changing your search or filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $students->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const instituteSelect = document.getElementById('institute_id');
    const programSelect = document.getElementById('program_id');

    const allProgramOptions = Array.from(programSelect.options)
        .filter(option => option.dataset.institute);

    function filterPrograms() {
        const selectedInstitute = instituteSelect.value;
        const previouslySelectedProgram = programSelect.value;

        programSelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'All Programs';

        programSelect.appendChild(placeholder);

        if (selectedInstitute === '') {
            allProgramOptions.forEach(function (option) {
                programSelect.appendChild(option.cloneNode(true));
            });

            return;
        }

        let matchingPrograms = 0;

        allProgramOptions.forEach(function (option) {
            if (option.dataset.institute === selectedInstitute) {
                const newOption = option.cloneNode(true);

                if (newOption.value === previouslySelectedProgram) {
                    newOption.selected = true;
                }

                programSelect.appendChild(newOption);
                matchingPrograms++;
            }
        });

        if (matchingPrograms === 0) {
            placeholder.textContent = 'No programs available';
        }

        const selectedProgramExists = Array.from(programSelect.options)
            .some(option => option.value === previouslySelectedProgram);

        if (!selectedProgramExists) {
            programSelect.value = '';
        }
    }

    instituteSelect.addEventListener('change', filterPrograms);

    filterPrograms();
});
</script>
@endsection
