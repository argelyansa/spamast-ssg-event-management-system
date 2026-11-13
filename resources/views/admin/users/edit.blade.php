
@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<section class="page-heading">
    <h1>Edit User Account</h1>
    <p>Update the account information for {{ $user->name }}.</p>
</section>

<section class="content-card">

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name"
                value="{{ old('name', $user->name) }}" required>
            @error('name')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email"
                value="{{ old('email', $user->email) }}" required>
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="role_id">Account Role</label>
            <select id="role_id" name="role_id" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            @error('role_id')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div id="student-fields" style="display:none;">

            <h3 class="student-heading">Student Academic Information</h3>

            <div class="form-group">
                <label for="institute_id">Institute</label>
                <select id="institute_id" name="institute_id">
                    <option value="">Select Institute</option>
                    @foreach ($institutes as $institute)
                        <option value="{{ $institute->id }}"
                            {{ old('institute_id', $user->student?->program?->institute_id) == $institute->id ? 'selected' : '' }}>
                            {{ $institute->name }}
                        </option>
                    @endforeach
                </select>
                @error('institute_id')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="program_id">Program</label>
                <select id="program_id" name="program_id">
                    <option value="">Select Program</option>
                    @foreach ($programs as $program)
                        <option
                            value="{{ $program->id }}"
                            data-institute="{{ $program->institute_id }}"
                            {{ old('program_id', $user->student?->program_id) == $program->id ? 'selected' : '' }}>
                            {{ $program->code }} - {{ $program->name }}
                        </option>
                    @endforeach
                </select>
                @error('program_id')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="student_no">Student Number</label>
                <input type="text" id="student_no" name="student_no"
                    value="{{ old('student_no', $user->student?->student_no) }}">
                @error('student_no')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="sex">Sex</label>
                <select id="sex" name="sex">
                    <option value="">Select Sex</option>
                    <option value="Male"
                        {{ old('sex', $user->student?->sex) == 'Male' ? 'selected' : '' }}>
                        Male
                    </option>
                    <option value="Female"
                        {{ old('sex', $user->student?->sex) == 'Female' ? 'selected' : '' }}>
                        Female
                    </option>
                </select>
                @error('sex')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="year_level">Year Level</label>
                <select id="year_level" name="year_level">
                    <option value="">Select Year Level</option>
                    @foreach ([1, 2, 3, 4] as $year)
                        <option value="{{ $year }}"
                            {{ old('year_level', $user->student?->year_level) == $year ? 'selected' : '' }}>
                            Year {{ $year }}
                        </option>
                    @endforeach
                </select>
                @error('year_level')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="save-button">
                Save Changes
            </button>

            <a href="{{ route('admin.users.index') }}" class="cancel-button">
                Cancel
            </a>
        </div>

    </form>

</section>

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

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #CBD5E1;
        border-radius: 6px;
        font-size: 14px;
        background: white;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #3B82F6;
    }

    .field-error {
        display: block;
        color: #DC2626;
        font-size: 13px;
        margin-top: 6px;
    }

    .student-heading {
        color: #3B82F6;
        margin: 25px 0 18px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 25px;
    }

    .save-button,
    .cancel-button {
        padding: 11px 18px;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        text-decoration: none;
        cursor: pointer;
        font-size: 14px;
    }

    .save-button {
        background: #3B82F6;
        color: white;
    }

    .cancel-button {
        background: #E2E8F0;
        color: #1E293B;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role_id');
    const studentFields = document.getElementById('student-fields');
    const instituteSelect = document.getElementById('institute_id');
    const programSelect = document.getElementById('program_id');

    const studentRoleId = @json($roles->firstWhere('name', 'Student')?->id);

    function filterPrograms() {
        const selectedInstitute = instituteSelect.value;
        const currentProgram = programSelect.value;

        Array.from(programSelect.options).forEach(option => {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            option.hidden = option.dataset.institute !== selectedInstitute;
        });

        if (
            currentProgram &&
            programSelect.selectedOptions[0]?.hidden
        ) {
            programSelect.value = '';
        }
    }

    function toggleStudentFields() {
        const isStudent = roleSelect.value === String(studentRoleId);

        studentFields.style.display = isStudent ? 'block' : 'none';

        studentFields.querySelectorAll('input, select').forEach(field => {
            field.disabled = !isStudent;
            field.required = isStudent;
        });

        if (isStudent) {
            filterPrograms();
        }
    }

    instituteSelect.addEventListener('change', filterPrograms);
    roleSelect.addEventListener('change', toggleStudentFields);

    toggleStudentFields();
});
</script>
@endpush
