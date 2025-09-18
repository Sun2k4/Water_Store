<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();
        
        // Cập nhật giá sản phẩm trong giỏ hàng theo giá hiện tại
        foreach ($carts as $cart) {
            if ($cart->price != $cart->product->price) {
                $cart->update(['price' => $cart->product->price]);
            }
        }
        
        // Tải lại giỏ hàng sau khi cập nhật giá
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();
        $total = $carts->sum(function($cart) {
            return $cart->quantity * $cart->price;
        });
        
        return view('cart.index', compact('carts', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Kiểm tra sản phẩm có hoạt động không
        if (!$product->is_active) {
            return back()->with('error', 'Sản phẩm này hiện không khả dụng!');
        }
        
        // Kiểm tra sản phẩm có hết hàng không
        if ($product->isOutOfStock()) {
            return back()->with('error', 'Sản phẩm này đã hết hàng!');
        }
        
        // Kiểm tra số lượng sản phẩm có đủ không
        if ($product->quantity < $request->quantity) {
            return back()->with('error', 'Số lượng sản phẩm không đủ! Chỉ còn ' . $product->quantity . ' sản phẩm.');
        }

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($product->quantity < $newQuantity) {
                return back()->with('error', 'Số lượng sản phẩm không đủ!');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);
        }

        return back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $this->authorize('update', $cart);
        $product = $cart->product;
        
        // Kiểm tra sản phẩm có hoạt động không
        if (!$product->is_active) {
            return back()->with('error', 'Sản phẩm này hiện không khả dụng!');
        }
        
        // Kiểm tra sản phẩm có hết hàng không
        if ($product->isOutOfStock()) {
            return back()->with('error', 'Sản phẩm này đã hết hàng!');
        }
        
        if ($product->quantity < $request->quantity) {
            return back()->with('error', 'Số lượng sản phẩm không đủ! Chỉ còn ' . $product->quantity . ' sản phẩm.');
        }

        // Cập nhật cả số lượng và giá sản phẩm
        $cart->update([
            'quantity' => $request->quantity,
            'price' => $cart->product->price // Cập nhật giá theo giá hiện tại của sản phẩm
        ]);
        
        return back()->with('success', 'Đã cập nhật giỏ hàng!');
    }

    public function remove(Cart $cart)
    {
        $this->authorize('delete', $cart);

        $cart->delete();
        
        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    public function getCartCount()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->sum('quantity');
        }
        return 0;
    }
    
    /**
     * Áp dụng mã giảm giá vào giỏ hàng
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);
        
        // Tìm coupon trong database
        $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
        
        if (!$coupon) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giảm giá không tồn tại!'
                ]);
            }
            return back()->with('error', 'Mã giảm giá không tồn tại!');
        }
        
        // Tính tổng giá trị giỏ hàng
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();
        $cartTotal = $carts->sum(function($cart) {
            return $cart->quantity * $cart->price;
        });
        
        // Kiểm tra tính hợp lệ của coupon
        if (!$coupon->isValid($cartTotal)) {
            $message = 'Mã giảm giá không hợp lệ!';
            
            // Kiểm tra lý do cụ thể
            if ($coupon->expires_at && now()->gt($coupon->expires_at)) {
                $message = 'Mã giảm giá đã hết hạn!';
            } elseif ($coupon->usage_count >= $coupon->usage_limit) {
                $message = 'Mã giảm giá đã hết lượt sử dụng!';
            } elseif ($cartTotal < $coupon->min_order_amount) {
                $message = 'Giá trị đơn hàng tối thiểu phải là ' . number_format($coupon->min_order_amount) . ' VNĐ!';
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ]);
            }
            return back()->with('error', $message);
        }
        
        // Tính toán số tiền được giảm
        $discountAmount = $coupon->calculateDiscount($cartTotal);
        
        // Lưu thông tin coupon vào session
        Session::put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'coupon_id' => $coupon->id
        ]);
        
        Session::put('discount_amount', $discountAmount);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Áp dụng mã giảm giá thành công!',
                'discount_amount' => $discountAmount,
                'discount_formatted' => number_format($discountAmount) . ' VNĐ',
                'new_total' => $cartTotal - $discountAmount,
                'new_total_formatted' => number_format($cartTotal - $discountAmount) . ' VNĐ',
                'coupon_type' => $coupon->type,
                'coupon_value' => $coupon->value
            ]);
        }
        
        return back()->with('success', 'Áp dụng mã giảm giá thành công!');
    }
    
    /**
     * Xóa mã giảm giá khỏi session
     *
     * @return \Illuminate\Http\Response
     */
    public function removeCoupon(Request $request)
    {
        Session::forget('coupon');
        Session::forget('discount_amount');
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa mã giảm giá!'
            ]);
        }
        
        return back()->with('success', 'Đã xóa mã giảm giá!');
    }
}