<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Nước ngọt có ga'],
            ['name' => 'Nước ngọt không ga'],
            ['name' => 'Nước trái cây'],
            ['name' => 'Nước khoáng'],
            ['name' => 'Nước tăng lực'],
            ['name' => 'Trà và cà phê'],
            ['name' => 'Nước uống thể thao'],
            ['name' => 'Nước tinh khiết'],
            ['name' => 'Nước ion kiềm'],
            ['name' => 'Nước detox'],
            ['name' => 'Nước dừa'],
            ['name' => 'Nước ép tươi'],
            ['name' => 'Nước lọc'],
            ['name' => 'Nước suối'],
            ['name' => 'Nước vitamin'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
