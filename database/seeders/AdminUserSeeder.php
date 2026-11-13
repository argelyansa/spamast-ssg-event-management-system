<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->firstOrFail();

        $existingAdmin = User::where('role_id', $adminRole->id)->first();

        if ($existingAdmin) {
            $this->command?->info(
                'An Admin account already exists. No new account was created.'
            );

            return;
        }

        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (empty($email) || empty($password)) {
            $this->command?->error(
                'No Admin account exists. Configure ADMIN_EMAIL and ADMIN_PASSWORD in your local .env file first.'
            );

            return;
        }

        User::create([
            'name' => env('ADMIN_NAME', 'System Administrator'),
            'email' => $email,
            'role_id' => $adminRole->id,
            'password' => Hash::make($password),
        ]);

        $this->command?->info('Admin account created successfully.');
    }
}
