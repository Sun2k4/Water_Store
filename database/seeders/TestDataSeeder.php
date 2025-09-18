<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Tạo user test
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now()
            ]
        );

        // Tạo các danh mục nước
        $categories = [
            ['name' => 'Nước khoáng'],
            ['name' => 'Nước tinh khiết'],
            ['name' => 'Nước có ga'],
            ['name' => 'Nước trái cây'],
            ['name' => 'Nước detox'],
            ['name' => 'Nước ion kiềm']
        ];

        $categoryIds = [];
        foreach ($categories as $catData) {
            $category = Category::firstOrCreate($catData);
            $categoryIds[] = $category->id;
        }

        // Tạo các sản phẩm nước
        $products = [
            // Nước khoáng
            ['name' => 'Aquafina 500ml', 'price' => 8000, 'quantity' => 200, 'category_id' => $categoryIds[0]],
            ['name' => 'Lavie 500ml', 'price' => 7000, 'quantity' => 150, 'category_id' => $categoryIds[0]],
            ['name' => 'Vinh Hao 500ml', 'price' => 6000, 'quantity' => 100, 'category_id' => $categoryIds[0]],
            
            // Nước tinh khiết
            ['name' => 'Bidrico 500ml', 'price' => 5000, 'quantity' => 300, 'category_id' => $categoryIds[1]],
            ['name' => 'Wonder 500ml', 'price' => 4500, 'quantity' => 250, 'category_id' => $categoryIds[1]],
            
            // Nước có ga
            ['name' => 'Coca Cola 330ml', 'price' => 12000, 'quantity' => 100, 'category_id' => $categoryIds[2]],
            ['name' => 'Pepsi 330ml', 'price' => 11000, 'quantity' => 100, 'category_id' => $categoryIds[2]],
            ['name' => 'Sprite 330ml', 'price' => 10000, 'quantity' => 80, 'category_id' => $categoryIds[2]],
            
            // Nước trái cây
            ['name' => 'Nước cam tươi 500ml', 'price' => 25000, 'quantity' => 50, 'category_id' => $categoryIds[3]],
            ['name' => 'Nước dừa tươi 500ml', 'price' => 20000, 'quantity' => 60, 'category_id' => $categoryIds[3]],
            ['name' => 'Nước chanh dây 500ml', 'price' => 18000, 'quantity' => 40, 'category_id' => $categoryIds[3]],
            
            // Nước detox
            ['name' => 'Nước detox chanh mật ong 500ml', 'price' => 30000, 'quantity' => 30, 'category_id' => $categoryIds[4]],
            ['name' => 'Nước detox dưa chuột 500ml', 'price' => 28000, 'quantity' => 25, 'category_id' => $categoryIds[4]],
            
            // Nước ion kiềm
            ['name' => 'Kangaroo ion kiềm 500ml', 'price' => 15000, 'quantity' => 80, 'category_id' => $categoryIds[5]],
            ['name' => 'Panasonic ion kiềm 500ml', 'price' => 18000, 'quantity' => 60, 'category_id' => $categoryIds[5]]
        ];

        $productIds = [];
        foreach ($products as $prodData) {
            $product = Product::firstOrCreate(
                ['name' => $prodData['name']],
                [
                    'description' => 'Nước uống chất lượng cao',
                    'price' => $prodData['price'],
                    'quantity' => $prodData['quantity'],
                    'category_id' => $prodData['category_id']
                ]
            );
            $productIds[] = $product->id;
        }

        // Tạo các đơn hàng test với nhiều loại nước khác nhau
        $orders = [
            // Đơn hàng 1: Nước khoáng và có ga
            [
                'name' => 'Nguyễn Văn A',
                'address' => '123 Đường ABC, Quận 1, TP.HCM',
                'phone' => '0123456789',
                'total_price' => 0, // Sẽ tính sau
                'status' => 'đã xác nhận',
                'payment_method' => 'cod',
                'created_at' => now()->subDays(5),
                'items' => [
                    ['product_id' => $productIds[0], 'quantity' => 10, 'price' => 8000], // Aquafina
                    ['product_id' => $productIds[5], 'quantity' => 5, 'price' => 12000]  // Coca Cola
                ]
            ],
            
            // Đơn hàng 2: Nước trái cây và detox
            [
                'name' => 'Trần Thị B',
                'address' => '456 Đường XYZ, Quận 2, TP.HCM',
                'phone' => '0987654321',
                'total_price' => 0,
                'status' => 'đã xác nhận',
                'payment_method' => 'momo_atm',
                'created_at' => now()->subDays(10),
                'items' => [
                    ['product_id' => $productIds[8], 'quantity' => 3, 'price' => 25000], // Nước cam
                    ['product_id' => $productIds[11], 'quantity' => 2, 'price' => 30000] // Detox chanh
                ]
            ],
            
            // Đơn hàng 3: Nước tinh khiết và ion kiềm
            [
                'name' => 'Lê Văn C',
                'address' => '789 Đường DEF, Quận 3, TP.HCM',
                'phone' => '0369852147',
                'total_price' => 0,
                'status' => 'đã xác nhận',
                'payment_method' => 'cod',
                'created_at' => now()->subDays(15),
                'items' => [
                    ['product_id' => $productIds[3], 'quantity' => 20, 'price' => 5000], // Bidrico
                    ['product_id' => $productIds[13], 'quantity' => 4, 'price' => 18000] // Panasonic ion
                ]
            ],
            
            // Đơn hàng 4: Nước có ga và trái cây
            [
                'name' => 'Phạm Thị D',
                'address' => '321 Đường GHI, Quận 4, TP.HCM',
                'phone' => '0741258963',
                'total_price' => 0,
                'status' => 'đã xác nhận',
                'payment_method' => 'momo_atm',
                'created_at' => now()->subDays(20),
                'items' => [
                    ['product_id' => $productIds[6], 'quantity' => 8, 'price' => 11000], // Pepsi
                    ['product_id' => $productIds[9], 'quantity' => 2, 'price' => 20000]  // Nước dừa
                ]
            ],
            
            // Đơn hàng 5: Nước khoáng và detox
            [
                'name' => 'Hoàng Văn E',
                'address' => '654 Đường JKL, Quận 5, TP.HCM',
                'phone' => '0852369741',
                'total_price' => 0,
                'status' => 'đã xác nhận',
                'payment_method' => 'cod',
                'created_at' => now()->subDays(25),
                'items' => [
                    ['product_id' => $productIds[1], 'quantity' => 15, 'price' => 7000], // Lavie
                    ['product_id' => $productIds[12], 'quantity' => 3, 'price' => 28000] // Detox dưa chuột
                ]
            ]
        ];

        // Tạo đơn hàng và order items
        foreach ($orders as $orderData) {
            $items = $orderData['items'];
            unset($orderData['items']);
            
            // Tính tổng tiền
            $total = 0;
            foreach ($items as $item) {
                $total += $item['quantity'] * $item['price'];
            }
            $orderData['total_price'] = $total;
            $orderData['user_id'] = $user->id; // Thêm user_id
            
            $order = Order::create($orderData);
            
            // Tạo order items
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
        }

        $this->command->info('Test data for water store created successfully!');
        $this->command->info('Created: ' . count($categories) . ' categories, ' . count($products) . ' products, ' . count($orders) . ' orders');
    }
}