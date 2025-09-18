<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product.category')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('wishlist.index', compact('wishlists'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Kiểm tra xem sản phẩm đã có trong wishlist chưa
        $existingWishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingWishlist) {
            return back()->with('error', 'Sản phẩm đã có trong danh sách yêu thích!');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id
        ]);

        return back()->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích!');
    }

    public function remove(Request $request, $id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$wishlist) {
            return back()->with('error', 'Không tìm thấy sản phẩm trong danh sách yêu thích!');
        }

        $wishlist->delete();
        
        return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích!');
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['status' => 'removed', 'message' => 'Đã xóa khỏi yêu thích']);
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ]);
            return response()->json(['status' => 'added', 'message' => 'Đã thêm vào yêu thích']);
        }
    }

    public function getWishlistCount()
    {
        if (Auth::check()) {
            return Wishlist::where('user_id', Auth::id())->count();
        }
        return 0;
    }
}