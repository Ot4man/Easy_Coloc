<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create Admin User
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@easy.com',
            'password' => Hash::make('admin123'),
            'role_id' => $adminRole->id,
        ]);

        // Create Test User
        User::factory()->create([
            'name' => 'membre',
            'email' => 'membre@easy.com',
            'password' => Hash::make('membre123'),
            'role_id' => $userRole->id,
        ]);
    }
}
