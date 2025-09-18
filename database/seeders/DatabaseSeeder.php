<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void

    {
        User::firstOrCreate(
            ['email' => 'tuananhnguyen141104@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('1'),
                'role' => 'admin',
                'email_verified_at' => now(), // Tự động verify email cho admin
            ]
        );

        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,
            TestDataSeeder::class,
        ]);
    }
}
