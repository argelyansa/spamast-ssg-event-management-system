<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('user')
            ->latest()
            ->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    public function show(Announcement $announcement)
    {
        $announcement->load('user');

        return view('announcements.show', compact('announcement'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $validated['user_id'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $this->storeAttachment(
                $request->file('attachment')
            );
        }

        Announcement::create($validated);

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Announcement published successfully.');
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $oldAttachment = $announcement->attachment;

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $this->storeAttachment(
                $request->file('attachment')
            );
        }

        $announcement->update($validated);

        if (
            $request->hasFile('attachment') &&
            $oldAttachment
        ) {
            Storage::disk('public')->delete($oldAttachment);
        }

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->attachment) {
            Storage::disk('public')->delete($announcement->attachment);
        }

        $announcement->delete();

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    private function storeAttachment($file): string
    {
        $originalName = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        $slug = Str::slug($originalName) ?: 'attachment';

        $filename = $slug . '-' . Str::uuid() . '.' . $file->extension();

        return $file->storeAs('announcements', $filename, 'public');
    }
}
