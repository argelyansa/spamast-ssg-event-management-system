
@extends('layouts.app')

@section('content')
<style>
    .student-form {
        max-width: 850px;
        margin: 30px auto;
        color: #0B1120;
    }

    .student-form .form-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
    }

    .student-form h2 {
        margin: 0 0 6px;
        font-size: 26px;
        font-weight: 700;
    }

    .student-form .subtitle {
        color: #64748B;
        margin-bottom: 28px;
    }

    .student-form .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .student-form .full-width {
        grid-column: 1 / -1;
    }

    .student-form label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .student-form input,
    .student-form select {
        display: block;
        width: 100%;
        min-width: 0;
        min-height: 48px;
        box-sizing: border-box;
        padding: 11px 13px;
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        background-color: #FFFFFF;
        color: #0B1120;
        font-size: 15px;
        outline: none;
    }

    .student-form input:focus,
    .student-form select:focus {
        border-color: #3B82F6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    .student-form select {
        cursor: pointer;
    }

    .student-form select:disabled {
        background-color: #F1F5F9;
        color: #94A3B8;
        cursor: not-allowed;
    }

    .student-form .help {
        color: #64748B;
        font-size: 13px;
        margin: 6px 0 0;
    }

    .student-form .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 28px;
    }

    .student-form .save-btn {
        background: #3B82F6;
        color: #FFFFFF;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
    }

    .student-form .save-btn:hover {
        background: #2563EB;
    }

    .student-form .cancel-btn {
        display: inline-flex;
        align-items: center;
        background: #E2E8F0;
        color: #0B1120;
        padding: 12px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
    }

    .student-form .cancel-btn:hover {
        background: #CBD5E1;
    }

    .student-form .error-box {
        background: #FEF2F2;
        border: 1px solid #FECACA;
        color: #B91C1C;
        padding: 14px 18px;
        border-radius: 8px;
        margin-bottom: 22px;
    }

    .student-form .error-box ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    @media (max-width: 600px) {
        .student-form {
            margin: 15px;
        }

        .student-form .form-card {
            padding: 20px;
        }

        .student-form .form-grid {
            grid-template-columns: 1fr;
        }

        .student-form .full-width {
            grid-column: auto;
        }

        .student-form .form-actions {
            flex-direction: column;
        }

        .student-form .save-btn,
        .student-form .cancel-btn {
            justify-content: center;
            text-align: center;
        }
    }
</style>

<div class="student-form">
    <div class="form-card">

        <h2>Add New Student</h2>
        <p class="subtitle">
            Enter the student's account and academic information.
        </p>

        @if ($errors->any())
            <div class="error-box">
                <strong>Please correct the following errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('officer.students.store') }}" method="POST">
            @csrf

            <div class="form-grid">

                <div>
                    <label for="name">Full Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter student's full name"
                        required
                    >
                </div>

                <div>
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter student's email"
                        required
                    >
                </div>

                <div>
                    <label for="student_no">Student ID Number (School ID)</label>
                    <input
                        type="text"
                        id="student_no"
                        name="student_no"
                        value="{{ old('student_no') }}"
                        placeholder="Enter official school ID number"
                        required
                    >
                    <p class="help">
                        Enter the student's official SPAMAST student ID number.
                        This will also be used as the temporary password.
                    </p>
                </div>

                <div>
                    <label for="sex">Sex</label>
                    <select name="sex" id="sex" required>
                        <option value="">Select sex</option>
                        <option value="Male" {{ old('sex') === 'Male' ? 'selected' : '' }}>
                            Male
                        </option>
                        <option value="Female" {{ old('sex') === 'Female' ? 'selected' : '' }}>
                            Female
                        </option>
                    </select>
                </div>

                <div>
                    <label for="institute_id">Institute</label>
                    <select id="institute_id" required>
                        <option value="">Select institute</option>

                        @foreach ($institutes as $institute)
                            <option
                                value="{{ $institute->id }}"
                                {{ old('institute_id') == $institute->id ? 'selected' : '' }}
                            >
                                {{ $institute->code }} - {{ $institute->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="program_id">Program</label>
                    <select
                        name="program_id"
                        id="program_id"
                        required
                        disabled
                    >
                        <option value="">Select an institute first</option>

                        @foreach ($programs as $program)
                            <option
                                value="{{ $program->id }}"
                                data-institute="{{ $program->institute_id }}"
                                {{ old('program_id') == $program->id ? 'selected' : '' }}
                            >
                                {{ $program->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="help">
                        Only programs belonging to the selected institute will appear.
                    </p>
                </div>

                <div class="full-width">
                    <label for="year_level">Year Level</label>
                    <select name="year_level" id="year_level" required>
                        <option value="">Select year level</option>
                        <option value="1" {{ old('year_level') == '1' ? 'selected' : '' }}>
                            1st Year
                        </option>
                        <option value="2" {{ old('year_level') == '2' ? 'selected' : '' }}>
                            2nd Year
                        </option>
                        <option value="3" {{ old('year_level') == '3' ? 'selected' : '' }}>
                            3rd Year
                        </option>
                        <option value="4" {{ old('year_level') == '4' ? 'selected' : '' }}>
                            4th Year
                        </option>
                    </select>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">
                    Save Student
                </button>

                <a href="{{ route('officer.students.index') }}" class="cancel-btn">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const instituteSelect = document.getElementById('institute_id');
    const programSelect = document.getElementById('program_id');

    const programOptions = Array.from(programSelect.options)
        .filter(option => option.dataset.institute);

    const oldProgram = @json(old('program_id'));

    function filterPrograms() {
        const selectedInstitute = instituteSelect.value;

        programSelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';

        if (!selectedInstitute) {
            placeholder.textContent = 'Select an institute first';
            programSelect.appendChild(placeholder);
            programSelect.disabled = true;
            return;
        }

        placeholder.textContent = 'Select program';
        programSelect.appendChild(placeholder);

        let matchingPrograms = 0;

        programOptions.forEach(function (option) {
            if (option.dataset.institute === selectedInstitute) {
                const newOption = option.cloneNode(true);

                if (newOption.value === String(oldProgram)) {
                    newOption.selected = true;
                }

                programSelect.appendChild(newOption);
                matchingPrograms++;
            }
        });

        programSelect.disabled = matchingPrograms === 0;

        if (matchingPrograms === 0) {
            placeholder.textContent = 'No programs available';
        }
    }

    instituteSelect.addEventListener('change', function () {
        filterPrograms();
    });

    filterPrograms();
});
</script>
@endsection
