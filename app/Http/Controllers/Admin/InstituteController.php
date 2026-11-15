<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InstituteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $institutes = DB::table('institutes')
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.institutes.index', compact('institutes', 'search'));
    }

    public function create()
    {
        return view('admin.institutes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:institutes,code'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        DB::table('institutes')->insert([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.institutes.index')
            ->with('success', 'Institute created successfully.');
    }

    public function edit(string $id)
    {
        $institute = DB::table('institutes')->where('id', $id)->first();

        abort_unless($institute, 404);

        return view('admin.institutes.edit', compact('institute'));
    }

    public function update(Request $request, string $id)
    {
        $institute = DB::table('institutes')->where('id', $id)->first();

        abort_unless($institute, 404);

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('institutes', 'code')->ignore($id),
            ],
            'name' => ['required', 'string', 'max:255'],
        ]);

        DB::table('institutes')
            ->where('id', $id)
            ->update([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.institutes.index')
            ->with('success', 'Institute updated successfully.');
    }

    public function destroy(string $id)
    {
        $institute = DB::table('institutes')->where('id', $id)->first();

        abort_unless($institute, 404);

        $hasPrograms = DB::table('programs')
            ->where('institute_id', $id)
            ->exists();

        if ($hasPrograms) {
            return redirect()
                ->route('admin.institutes.index')
                ->with('error', 'This institute cannot be deleted because it still has academic programs.');
        }

        DB::table('institutes')->where('id', $id)->delete();

        return redirect()
            ->route('admin.institutes.index')
            ->with('success', 'Institute deleted successfully.');
    }
}
