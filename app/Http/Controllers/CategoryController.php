<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories')
                        ->with('success', 'Danh mục đã được tạo thành công.');
    }

    public function show(Category $category)
    {
        $category->loadCount('products');
        $category->load(['products' => function($query) {
            $query->where('is_active', true)->take(8);
        }]);
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $category->loadCount('products');
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.categories')
                        ->with('success', 'Danh mục đã được cập nhật thành công.');
    }

    public function destroy(Category $category)
    {
        // Kiểm tra xem danh mục có sản phẩm không
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories')
                            ->with('error', 'Không thể xóa danh mục này vì còn có sản phẩm. Vui lòng xóa hoặc chuyển tất cả sản phẩm sang danh mục khác trước.');
        }

        $category->delete();

        return redirect()->route('admin.categories')
                        ->with('success', 'Danh mục đã được xóa thành công.');
    }
}