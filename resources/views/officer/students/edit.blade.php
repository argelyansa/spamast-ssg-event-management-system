
@extends('layouts.app')

@section('content')
<style>
    .student-form {
        max-width: 850px;
        margin: auto;
        color: #0B1120;
    }

    .student-form .form-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 25px;
    }

    .student-form h2 {
        margin: 0 0 6px;
        font-size: 25px;
    }

    .student-form .subtitle {
        color: #64748B;
        margin-bottom: 24px;
    }

    .student-form .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .student-form .full-width {
        grid-column: 1 / -1;
    }

    .student-form label {
        display: block;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .student-form input,
    .student-form select {
        width: 100%;
        padding: 11px;
        border: 1px solid #CBD5E1;
        border-radius: 7px;
        background: white;
    }

    .student-form .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 24px;
    }

    .student-form .save-btn {
        background: #3B82F6;
        color: white;
        border: 0;
        padding: 11px 18px;
        border-radius: 7px;
        font-weight: 600;
        cursor: pointer;
    }

    .student-form .cancel-btn {
        background: #E2E8F0;
        color: #0B1120;
        padding: 11px 18px;
        border-radius: 7px;
        text-decoration: none;
    }

    @media (max-width: 600px) {
        .student-form .form-grid {
            grid-template-columns: 1fr;
        }

        .student-form .full-width {
            grid-column: auto;
        }
    }
</style>

<div class="student-form">
    <div class="form-card">
        <h2>Edit Student</h2>
        <p class="subtitle">Update the student's account and academic information.</p>

        <form action="{{ route('officer.students.update', $student) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div>
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $student->user?->name) }}" required>
                </div>

                <div>
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email', $student->user?->email) }}" required>
                </div>

                <div>
                    <label for="student_no">Student ID Number (School ID)</label>
                    <input type="text" id="student_no" name="student_no"
                           placeholder="Enter official school ID number"
                           value="{{ old('student_no', $student->student_no) }}" required>
                </div>

                <div>
                    <label for="sex">Sex</label>
                    <select name="sex" id="sex" required>
                        <option value="">Select sex</option>
                        <option value="Male" {{ old('sex', $student->sex) === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex', $student->sex) === 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div>
                    <label for="institute_id">Institute</label>
                    <select id="institute_id" required>
                        <option value="">Select institute</option>
                        @foreach ($institutes as $institute)
                            <option value="{{ $institute->id }}"
                                {{ old('institute_id', $student->program?->institute_id) == $institute->id ? 'selected' : '' }}>
                                {{ $institute->code }} - {{ $institute->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="program_id">Program</label>
                    <select name="program_id" id="program_id" required>
                        <option value="">Select program</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}"
                                data-institute="{{ $program->institute_id }}"
                                {{ old('program_id', $student->program_id) == $program->id ? 'selected' : '' }}>
                                {{ $program->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="full-width">
                    <label for="year_level">Year Level</label>
                    <select name="year_level" id="year_level" required>
                        <option value="">Select year level</option>
                        @for ($year = 1; $year <= 4; $year++)
                            <option value="{{ $year }}"
                                {{ old('year_level', $student->year_level) == $year ? 'selected' : '' }}>
                                {{ $year }}{{ $year === 1 ? 'st' : ($year === 2 ? 'nd' : ($year === 3 ? 'rd' : 'th')) }} Year
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">Update Student</button>
                <a href="{{ route('officer.students.index') }}" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const instituteSelect = document.getElementById('institute_id');
    const programSelect = document.getElementById('program_id');
    const programs = Array.from(programSelect.options);
    const oldProgram = @json(old('program_id', $student->program_id));

    function filterPrograms() {
        const instituteId = instituteSelect.value;

        programs.forEach(function (option) {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            option.hidden = option.dataset.institute !== instituteId;
        });

        const selectedProgram = programs.find(function (option) {
            return option.value === String(oldProgram);
        });

        if (
            selectedProgram &&
            selectedProgram.dataset.institute === instituteId
        ) {
            programSelect.value = oldProgram;
        } else if (
            programSelect.selectedOptions.length &&
            programSelect.selectedOptions[0].hidden
        ) {
            programSelect.value = '';
        }
    }

    instituteSelect.addEventListener('change', function () {
        programSelect.value = '';
        filterPrograms();
    });

    filterPrograms();
});
</script>
@endsection
