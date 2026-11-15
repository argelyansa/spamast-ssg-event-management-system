<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use App\Models\Program;
use App\Models\Institute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with(['user', 'program.institute'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery
                        ->where('student_no', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->institute_id, function ($query, $instituteId) {
                $query->whereHas('program', function ($programQuery) use ($instituteId) {
                    $programQuery->where('institute_id', $instituteId);
                });
            })
            ->when($request->program_id, function ($query, $programId) {
                $query->where('program_id', $programId);
            })
            ->orderBy('student_no')
            ->paginate(15)
            ->withQueryString();

        $institutes = Institute::orderBy('name')->get();
        $programs = Program::orderBy('name')->get();

        return view('officer.students.index', compact(
            'students',
            'institutes',
            'programs'
        ));
    }

    public function create()
    {
        $institutes = Institute::orderBy('name')->get();
        $programs = Program::orderBy('name')->get();

        return view('officer.students.create', compact(
            'institutes',
            'programs'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'student_no' => ['required', 'string', 'max:255', 'unique:students,student_no'],
            'program_id' => ['required', 'exists:programs,id'],
            'sex' => ['required', Rule::in(['Male', 'Female'])],
            'year_level' => ['required', 'integer', 'min:1', 'max:4'],
        ]);

        DB::transaction(function () use ($validated) {
            $studentRole = Role::where('name', 'Student')->firstOrFail();

            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'],
                    'role_id' => $studentRole->id,
                    'password' => Hash::make($validated['student_no']),
                ]
            );

            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'program_id' => $validated['program_id'],
                    'student_no' => $validated['student_no'],
                    'sex' => $validated['sex'],
                    'year_level' => $validated['year_level'],
                ]
            );
        });

        return redirect()
            ->route('officer.students.index')
            ->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        $student->load('user');

        $institutes = Institute::orderBy('name')->get();
        $programs = Program::orderBy('name')->get();

        return view('officer.students.edit', compact(
            'student',
            'institutes',
            'programs'
        ));
    }

    public function update(Request $request, Student $student)
    {
        $student->load('user');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($student->user_id),
            ],
            'student_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('students', 'student_no')->ignore($student->id),
            ],
            'program_id' => ['required', 'exists:programs,id'],
            'sex' => ['required', Rule::in(['Male', 'Female'])],
            'year_level' => ['required', 'integer', 'min:1', 'max:4'],
        ]);

        DB::transaction(function () use ($validated, $student) {
            $student->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $student->update([
                'program_id' => $validated['program_id'],
                'student_no' => $validated['student_no'],
                'sex' => $validated['sex'],
                'year_level' => $validated['year_level'],
            ]);
        });

        return redirect()
            ->route('officer.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {
            $student->user()->delete();
        });

        return redirect()
            ->route('officer.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
