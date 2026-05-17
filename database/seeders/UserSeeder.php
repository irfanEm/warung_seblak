<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan role admin dibuat
        $role = Role::firstOrCreate(['name' => 'admin']);

        $user = User::firstOrCreate(
            ['email' => 'admin@seblakibun.com'],
            [
                'name' => 'Admin Seblak',
                'password' => Hash::make('password'),
            ]
        );

        if (!$user->hasRole('admin')) {
            $user->assignRole($role);
        }
    }
}
