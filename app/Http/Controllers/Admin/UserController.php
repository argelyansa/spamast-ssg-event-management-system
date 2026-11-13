<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'student.program.institute'])
            ->whereHas('role', function ($query) {
                $query->whereIn('name', ['Officer', 'Adviser', 'Student']);
            })
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::whereIn('name', ['Officer', 'Adviser', 'Student'])
            ->orderBy('name')
            ->get();

        $institutes = Institute::orderBy('name')->get();
        $programs = Program::orderBy('name')->get();

        return view('admin.users.create', compact(
            'roles',
            'institutes',
            'programs'
        ));
    }

    public function store(Request $request)
    {
        $studentRoleId = Role::where('name', 'Student')->value('id');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->whereIn('name', [
                    'Officer', 'Adviser', 'Student'
                ]),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'institute_id' => [
                Rule::requiredIf(fn () => (int) $request->role_id === (int) $studentRoleId),
                'nullable',
                'exists:institutes,id',
            ],
            'program_id' => [
                Rule::requiredIf(fn () => (int) $request->role_id === (int) $studentRoleId),
                'nullable',
                Rule::exists('programs', 'id')->where(
                    fn ($query) => $query->where('institute_id', $request->institute_id)
                ),
            ],
            'student_no' => [
                Rule::requiredIf(fn () => (int) $request->role_id === (int) $studentRoleId),
                'nullable',
                'string',
                'max:255',
                'unique:students,student_no',
            ],
            'sex' => [
                Rule::requiredIf(fn () => (int) $request->role_id === (int) $studentRoleId),
                'nullable',
                Rule::in(['Male', 'Female']),
            ],
            'year_level' => [
                Rule::requiredIf(fn () => (int) $request->role_id === (int) $studentRoleId),
                'nullable',
                'integer',
                'between:1,4',
            ],
        ]);

        DB::transaction(function () use ($validated, $studentRoleId) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role_id' => $validated['role_id'],
                'password' => Hash::make($validated['password']),
            ]);

            if ((int) $validated['role_id'] === (int) $studentRoleId) {
                Student::create([
                    'user_id' => $user->id,
                    'program_id' => $validated['program_id'],
                    'student_no' => $validated['student_no'],
                    'sex' => $validated['sex'],
                    'year_level' => $validated['year_level'],
                ]);
            }
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User account created successfully.');
    }

    public function show(User $user)
    {
        abort_unless(
            $user->role && in_array($user->role->name, [
                'Officer', 'Adviser', 'Student'
            ], true),
            404
        );

        $user->load(['role', 'student.program.institute']);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        abort_unless(
            $user->role && in_array($user->role->name, [
                'Officer', 'Adviser', 'Student'
            ], true),
            404
        );

        $user->load('student.program');

        $roles = Role::whereIn('name', ['Officer', 'Adviser', 'Student'])
            ->orderBy('name')
            ->get();

        $institutes = Institute::orderBy('name')->get();
        $programs = Program::orderBy('name')->get();

        return view('admin.users.edit', compact(
            'user',
            'roles',
            'institutes',
            'programs'
        ));
    }

    public function update(Request $request, User $user)
    {
        abort_unless(
            $user->role && in_array($user->role->name, [
                'Officer', 'Adviser', 'Student'
            ], true),
            404
        );

        $studentRoleId = Role::where('name', 'Student')->value('id');
        $isStudentRole = (int) $request->role_id === (int) $studentRoleId;
        $student = $user->student;

        if ($student && !$isStudentRole) {
            return back()
                ->withErrors([
                    'role_id' => 'This account has a Student profile. Its Student role cannot be changed.',
                ])
                ->withInput();
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->whereIn('name', [
                    'Officer', 'Adviser', 'Student'
                ]),
            ],
            'institute_id' => [
                Rule::requiredIf($isStudentRole),
                'nullable',
                'exists:institutes,id',
            ],
            'program_id' => [
                Rule::requiredIf($isStudentRole),
                'nullable',
                Rule::exists('programs', 'id')->where(
                    fn ($query) => $query->where('institute_id', $request->institute_id)
                ),
            ],
            'student_no' => [
                Rule::requiredIf($isStudentRole),
                'nullable',
                'string',
                'max:255',
                Rule::unique('students', 'student_no')->ignore($student?->id),
            ],
            'sex' => [
                Rule::requiredIf($isStudentRole),
                'nullable',
                Rule::in(['Male', 'Female']),
            ],
            'year_level' => [
                Rule::requiredIf($isStudentRole),
                'nullable',
                'integer',
                'between:1,4',
            ],
        ]);

        DB::transaction(function () use ($user, $validated, $isStudentRole) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role_id' => $validated['role_id'],
            ]);

            if ($isStudentRole) {
                Student::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'program_id' => $validated['program_id'],
                        'student_no' => $validated['student_no'],
                        'sex' => $validated['sex'],
                        'year_level' => $validated['year_level'],
                    ]
                );
            }
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User account updated successfully.');
    }

    public function destroy(User $user)
    {
        abort_unless(
            $user->role && in_array($user->role->name, [
                'Officer', 'Adviser', 'Student'
            ], true),
            404
        );

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User account deleted successfully.');
    }
}
