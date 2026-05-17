<?php
// database/seeders/UserSeeder.php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        $users = [
            ['name' => 'Admin', 'email' => 'admin@seblak.com', 'role' => 'Admin'],
            ['name' => 'Kasir', 'email' => 'kasir@seblak.com', 'role' => 'Kasir'],
            ['name' => 'Dapur', 'email' => 'dapur@seblak.com', 'role' => 'Dapur'],
            ['name' => 'Driver', 'email' => 'driver@seblak.com', 'role' => 'Driver'],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                ['name' => $userData['name'], 'password' => $defaultPassword]
            );

            if (!$user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }
        }
    }
}
