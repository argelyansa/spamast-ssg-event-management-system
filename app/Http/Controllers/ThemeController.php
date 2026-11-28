<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class ThemeController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme' => [
                'required',
                'string',
                Rule::in([
                    'navy',
                    'light',
                    'dark',
                    'forest',
                    'pink',
                    'rose',
                    'lavender',
                    'teal',
                ]),
            ],
        ]);

        $request->user()->update([
            'theme' => $validated['theme'],
        ]);

        return back()->with(
            'success',
            'Your theme has been updated successfully.'
        );
    }
}