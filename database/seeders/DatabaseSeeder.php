<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks (for MySQL)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing data (optional - only if you want fresh data each time)
        User::truncate();
        // Book::truncate(); // Uncomment when you're ready to seed books

        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create regular user
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // Create test user
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // Create sample books (uncomment when ready)
        // Book::firstOrCreate(
        //     ['title' => 'To Kill a Mockingbird'],
        //     [
        //         'author' => 'Harper Lee',
        //         'category' => 'Fiction',
        //         'description' => 'A classic novel about racial injustice in the American South.',
        //         'published_at' => now()->subYears(60),
        //     ]
        // );

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}