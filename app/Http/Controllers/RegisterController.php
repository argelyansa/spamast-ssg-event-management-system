
<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /**
     * Display the registration page.
     */
    public function showRegistrationForm()
    {
        return view('auth.register', [
            'roles' => Role::whereIn('name', [
                'Officer',
                'Adviser',
                'Student',
            ])->get(),
        ]);
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id')
                    ->whereIn('name', ['Officer', 'Adviser', 'Student']),
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
        ]);

        return redirect('/login')
            ->with('success', 'Account registered successfully. You may now log in.');
    }
}
