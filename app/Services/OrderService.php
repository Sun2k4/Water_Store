<?php


namespace App\Services;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderService
{
    /**
     * Tạo đơn hàng từ giỏ hàng
     *
     * @param array $validatedData
     * @param string $paymentMethod
     * @return Order
     * @throws \Exception
     */
    public function createOrderFromCart(array $validatedData, string $paymentMethod)
    {
        return DB::transaction(function () use ($validatedData, $paymentMethod) {
            $carts = Cart::where('user_id', Auth::id())->with('product')->get();

            if ($carts->isEmpty()) {
                throw new \Exception('Giỏ hàng trống!');
            }

            $total = $carts->sum(function ($cart) {
                return $cart->quantity * $cart->price;
            });
            
            // Xử lý mã giảm giá nếu có
            $discountAmount = 0;
            $couponCode = null;
            $finalPrice = $total;
            
            if (isset($validatedData['coupon_code']) && !empty($validatedData['coupon_code'])) {
                $coupon = Coupon::where('code', $validatedData['coupon_code'])->first();
                
                if ($coupon && $coupon->isValid($total)) {
                    $discountAmount = $coupon->calculateDiscount($total);
                    $couponCode = $coupon->code;
                    $finalPrice = $total - $discountAmount;
                    
                    // Tăng số lần sử dụng của coupon
                    $coupon->incrementUsage();
                    
                    // Xóa coupon khỏi session sau khi đã sử dụng
                    Session::forget('coupon');
                }
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'name' => $validatedData['name'] ?? Auth::user()->name,
                'address' => $validatedData['address'],
                'phone' => $validatedData['phone'],
                'total_price' => $total,
                'coupon_code' => $couponCode,
                'discount_amount' => $discountAmount,
                'final_price' => $finalPrice,
                'status' => 'đang xử lý',
                'payment_method' => $paymentMethod
            ]);

            foreach ($carts as $cart) {
                // Truy vấn lại sản phẩm với lock để tránh race condition
                $product = \App\Models\Product::where('id', $cart->product_id)->lockForUpdate()->first();

                // Kiểm tra số lượng sản phẩm còn lại
                if ($product->quantity < $cart->quantity) {
                    throw new \Exception("Sản phẩm {$product->name} không đủ số lượng!");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->price
                ]);

                // Cập nhật số lượng sản phẩm
                $product->decrement('quantity', $cart->quantity);
            }

            // Xóa giỏ hàng
            Cart::where('user_id', Auth::id())->delete();

            return $order;
        });
    }
}