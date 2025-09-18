<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category')->paginate(12);
        // Chỉ lấy categories có sản phẩm, sắp xếp theo tên và đảm bảo không trùng lặp
        $categories = Category::has('products')
            ->select('id', 'name')
            ->orderBy('name')
            ->distinct()
            ->get();
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:1000|max:99999999.99',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand' => 'nullable|string|max:255',
            'flavor' => 'nullable|string|max:255',
            'volume' => 'nullable|string|max:255',
            'packaging_type' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
            'is_carbonated' => 'boolean',
            'ingredients' => 'nullable|string',
            'origin_country' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'category_id' => 'required|exists:categories,id'
        ]);

        // Xử lý upload hình ảnh
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with(['category', 'reviews.user'])->findOrFail($id);
        
        // Kiểm tra xem user đã đăng nhập và đã mua sản phẩm này chưa
        $canReview = false;
        $userReview = null;
        
        if (auth()->check()) {
            $user = auth()->user();
            
            // Kiểm tra xem user đã mua sản phẩm này chưa
            $hasPurchased = \App\Models\Order::where('user_id', $user->id)
                ->where('status', 'đã giao hàng')
                ->whereHas('items', function ($query) use ($id) {
                    $query->where('product_id', $id);
                })
                ->exists();
            
            if ($hasPurchased) {
                // Kiểm tra xem user đã review sản phẩm này chưa
                $userReview = \App\Models\ProductReview::where('user_id', $user->id)
                    ->where('product_id', $id)
                    ->first();
                
                // Chỉ cho phép review nếu chưa review hoặc muốn chỉnh sửa review
                $canReview = true;
            }
        }
        
        // Lấy thống kê đánh giá
        $averageRating = $product->averageRating();
        $totalReviews = $product->totalReviews();
        $ratingDistribution = $product->ratingDistribution();
        
        return view('products.show', compact(
            'product', 
            'canReview', 
            'userReview', 
            'averageRating', 
            'totalReviews', 
            'ratingDistribution'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:1000|max:99999999.99',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand' => 'nullable|string|max:255',
            'flavor' => 'nullable|string|max:255',
            'volume' => 'nullable|string|max:255',
            'packaging_type' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
            'is_carbonated' => 'boolean',
            'ingredients' => 'nullable|string',
            'origin_country' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'category_id' => 'required|exists:categories,id'
        ]);

        // Xử lý upload hình ảnh mới
        if ($request->hasFile('image')) {
            // Xóa hình ảnh cũ nếu có
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Xóa hình ảnh nếu có
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được xóa thành công!');
    }
}
