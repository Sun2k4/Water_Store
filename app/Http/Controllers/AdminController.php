<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProducts = \App\Models\Product::count();
        $totalCategories = \App\Models\Category::count();
        $totalUsers = \App\Models\User::count();
        $totalCoupons = \App\Models\Coupon::count();
        $recentProducts = \App\Models\Product::with('category')->orderBy('created_at', 'desc')->take(5)->get();
        $recentCoupons = \App\Models\Coupon::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('totalProducts', 'totalCategories', 'totalUsers', 'totalCoupons', 'recentProducts', 'recentCoupons'));
    }

    public function products()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(10);
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function categories()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function destroyUser($id)
    {
        User::destroy($id);
        return back()->with('success', 'Xóa user thành công!');
    }

    public function changeUserRole(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $user = User::findOrFail($id);
        $user->role = $request->input('role');
        $user->save();
        return back()->with('success', 'Cập nhật vai trò thành công!');
    }
}