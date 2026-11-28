<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Support\DashboardStatsCache;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $programs = DB::table('programs')
            ->join('institutes', 'programs.institute_id', '=', 'institutes.id')
            ->select(
                'programs.*',
                'institutes.code as institute_code',
                'institutes.name as institute_name'
            )
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('programs.name', 'like', "%{$search}%")
                        ->orWhere('programs.code', 'like', "%{$search}%")
                        ->orWhere('institutes.code', 'like', "%{$search}%")
                        ->orWhere('institutes.name', 'like', "%{$search}%");
                });
            })
            ->orderBy('programs.name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.programs.index', compact('programs', 'search'));
    }

    public function create()
    {
        $institutes = DB::table('institutes')
            ->orderBy('name')
            ->get();

        return view('admin.programs.create', compact('institutes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'institute_id' => [
                'required',
                'integer',
                'exists:institutes,id',
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:programs,code',
            ],
            'name' => ['required', 'string', 'max:255'],
        ]);

        DB::table('programs')->insert([
            'institute_id' => $validated['institute_id'],
            'code' => $validated['code'],
            'name' => $validated['name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DashboardStatsCache::forgetAdmin();

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Academic program created successfully.');
    }

    public function edit(string $id)
    {
        $program = DB::table('programs')
            ->where('id', $id)
            ->first();

        abort_unless($program, 404);

        $institutes = DB::table('institutes')
            ->orderBy('name')
            ->get();

        return view('admin.programs.edit', compact('program', 'institutes'));
    }

    public function update(Request $request, string $id)
    {
        $program = DB::table('programs')
            ->where('id', $id)
            ->first();

        abort_unless($program, 404);

        $validated = $request->validate([
            'institute_id' => [
                'required',
                'integer',
                'exists:institutes,id',
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('programs', 'code')->ignore($id),
            ],
            'name' => ['required', 'string', 'max:255'],
        ]);

        DB::table('programs')
            ->where('id', $id)
            ->update([
                'institute_id' => $validated['institute_id'],
                'code' => $validated['code'],
                'name' => $validated['name'],
                'updated_at' => now(),
            ]);

        DashboardStatsCache::forgetAdmin();

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Academic program updated successfully.');
    }

    public function destroy(string $id)
    {
        $program = DB::table('programs')
            ->where('id', $id)
            ->first();

        abort_unless($program, 404);

        $hasStudents = DB::table('students')
            ->where('program_id', $id)
            ->exists();

        if ($hasStudents) {
            return redirect()
                ->route('admin.programs.index')
                ->with('error', 'This program cannot be deleted because students are still assigned to it.');
        }

        DB::table('programs')
            ->where('id', $id)
            ->delete();

        DashboardStatsCache::forgetAdmin();

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Academic program deleted successfully.');
    }
}
