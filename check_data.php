<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CATEGORIES ===" . PHP_EOL;
foreach(App\Models\Category::all() as $cat) {
    echo $cat->id . " - " . $cat->name . PHP_EOL;
}

echo PHP_EOL . "=== PRODUCTS IN DETOX CATEGORY ===" . PHP_EOL;
$detoxCat = App\Models\Category::where('name', 'like', '%detox%')->first();
if($detoxCat) {
    echo "Detox category ID: " . $detoxCat->id . PHP_EOL;
    $products = App\Models\Product::where('category_id', $detoxCat->id)->get();
    echo "Total products in detox: " . $products->count() . PHP_EOL;
    foreach($products as $prod) {
        echo $prod->id . " - " . $prod->name . " (category_id: " . $prod->category_id . ", active: " . ($prod->is_active ? 'yes' : 'no') . ")" . PHP_EOL;
    }
} else {
    echo "No detox category found" . PHP_EOL;
}

echo PHP_EOL . "=== ALL PRODUCTS WITH CATEGORY INFO ===" . PHP_EOL;
$products = App\Models\Product::with('category')->get();
foreach($products as $prod) {
    echo $prod->id . " - " . $prod->name . " (category: " . ($prod->category ? $prod->category->name : 'No category') . ", category_id: " . $prod->category_id . ", active: " . ($prod->is_active ? 'yes' : 'no') . ")" . PHP_EOL;
}