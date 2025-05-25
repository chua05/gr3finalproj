<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ERROR FIXED: Previously, running the seeder caused a
        // "SQLSTATE[23505]: Unique violation: 7 ERROR: duplicate key value violates unique constraint 'users_email_unique'"
        // because the same email (e.g., user@example.com) was being inserted multiple times.
        // Truncating the users table before seeding ensures a clean slate and prevents this unique constraint violation.
        // NOTE: Only use truncate in development, not in production with real user data.
        \App\Models\User::truncate();

        // Create or update admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create or update regular user
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // Create or update test user
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // // Create or update a sample book
        // Book::updateOrCreate(
        //     ['Title' => 'To Kill a Mockingbird'],
        //     [
        //         'Author' => 'Harper Lee',
        //         'Category' => 'Fiction',
        //         'Description' => 'A classic novel about racial injustice in the American South.',
        //     ]
        // );

        // Add more books as needed with updateOrCreate or create
    }
}
