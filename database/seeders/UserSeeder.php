<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeder ini membuat user admin untuk testing
     * Gunakan credential ini untuk login ke admin panel
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Optional: Create additional test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@ordertrack.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Users created successfully!');
        $this->command->info('📧 Admin: admin@email.com | Password: password123');
        $this->command->info('📧 Test: test@ordertrack.com | Password: password');
    }
}
