<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo tài khoản admin mặc định
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@ecommerce2024.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        echo "Tài khoản admin đã được tạo:\n";
        echo "Email: admin@ecommerce2024.com\n";
        echo "Password: admin123\n";
    }
}
