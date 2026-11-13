
@extends('layouts.app')

@section('title', 'Add New User')

@section('content')

    <section class="page-heading">
        <h1>Add New User</h1>
        <p>Create an Officer, Adviser, or Student account.</p>
    </section>

    <section class="content-card">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter full name"
                    required
                >
                @error('name')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter email address"
                    required
                >
                @error('email')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="role_id">Account Role</label>
                <select id="role_id" name="role_id" required>
                    <option value="">Select a role</option>

                    @foreach ($roles as $role)
                        <option
                            value="{{ $role->id }}"
                            data-role="{{ $role->name }}"
                            {{ old('role_id') == $role->id ? 'selected' : '' }}
                        >
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div id="student-fields" hidden>
                <h3 class="student-section-title">Student Information</h3>
                <p class="student-section-description">
                    Complete these details when creating a Student account.
                </p>

                <div class="form-group">
                    <label for="institute_id">Institute</label>
                    <select id="institute_id" name="institute_id" disabled>
                        <option value="">Select an institute</option>

                        @foreach ($institutes as $institute)
                            <option
                                value="{{ $institute->id }}"
                                {{ old('institute_id') == $institute->id ? 'selected' : '' }}
                            >
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
                    <select id="program_id" name="program_id" disabled>
                        <option value="">Select a program</option>

                        @foreach ($programs as $program)
                            <option
                                value="{{ $program->id }}"
                                data-institute="{{ $program->institute_id }}"
                                {{ old('program_id') == $program->id ? 'selected' : '' }}
                            >
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
                    <input
                        type="text"
                        id="student_no"
                        name="student_no"
                        value="{{ old('student_no') }}"
                        placeholder="Enter student number"
                        disabled
                    >
                    @error('student_no')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sex">Sex</label>
                    <select id="sex" name="sex" disabled>
                        <option value="">Select sex</option>
                        <option value="Male" {{ old('sex') === 'Male' ? 'selected' : '' }}>
                            Male
                        </option>
                        <option value="Female" {{ old('sex') === 'Female' ? 'selected' : '' }}>
                            Female
                        </option>
                    </select>
                    @error('sex')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="year_level">Year Level</label>
                    <select id="year_level" name="year_level" disabled>
                        <option value="">Select year level</option>

                        @for ($year = 1; $year <= 4; $year++)
                            <option
                                value="{{ $year }}"
                                {{ old('year_level') == $year ? 'selected' : '' }}
                            >
                                {{ $year }}{{ $year === 1 ? 'st' : ($year === 2 ? 'nd' : ($year === 3 ? 'rd' : 'th')) }} Year
                            </option>
                        @endfor
                    </select>
                    @error('year_level')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter password (minimum 8 characters)"
                    required
                >
                @error('password')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Re-enter password"
                    required
                >
            </div>

            <div class="form-actions">
                <button type="submit" class="save-button">
                    Create Account
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

    .student-section-title {
        color: #1E293B;
        margin: 25px 0 5px;
    }

    .student-section-description {
        color: #64748B;
        margin-bottom: 20px;
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

        const studentInputs = [
            instituteSelect,
            programSelect,
            document.getElementById('student_no'),
            document.getElementById('sex'),
            document.getElementById('year_level')
        ];

        function filterPrograms() {
            const selectedInstitute = instituteSelect.value;
            const previousProgram = programSelect.value;

            Array.from(programSelect.options).forEach(function (option) {
                if (!option.value) {
                    option.hidden = false;
                    return;
                }

                option.hidden = option.dataset.institute !== selectedInstitute;
            });

            const selectedOption = programSelect.selectedOptions[0];

            if (
                selectedOption &&
                selectedOption.value &&
                selectedOption.dataset.institute !== selectedInstitute
            ) {
                programSelect.value = '';
            }

            if (!selectedInstitute) {
                programSelect.value = '';
            }
        }

        function updateStudentFields() {
            const selectedRole = roleSelect.selectedOptions[0]?.dataset.role;
            const isStudent = selectedRole === 'Student';

            studentFields.hidden = !isStudent;

            studentInputs.forEach(function (input) {
                input.disabled = !isStudent;
                input.required = isStudent;
            });

            if (isStudent) {
                filterPrograms();
            }
        }

        roleSelect.addEventListener('change', updateStudentFields);
        instituteSelect.addEventListener('change', filterPrograms);

        updateStudentFields();
    });
</script>
@endpush
