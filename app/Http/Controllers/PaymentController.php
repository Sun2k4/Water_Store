<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Services\OrderService;

class PaymentController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();
        $total = $carts->sum(function($cart) {
            return $cart->quantity * $cart->price;
        });
        
        // Lấy thông tin coupon từ session nếu có
        $coupon = Session::get('coupon');
        $discountAmount = Session::get('discount_amount', 0);
        $finalTotal = $total - $discountAmount;
        
        return view('payment.index', compact('carts', 'total', 'coupon', 'discountAmount', 'finalTotal'));
    }
    
    public function processCod(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'coupon_code' => 'nullable|string|max:50',
            'discount_amount' => 'nullable|numeric'
        ]);

        try {
            $order = $this->orderService->createOrderFromCart($validatedData, 'cod');
            return redirect()->route('orders.show', $order->id)->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    public function processMomo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'coupon_code' => 'nullable|string|max:50',
            'discount_amount' => 'nullable|numeric'
        ]);

        try {
            DB::beginTransaction();

            // Sử dụng OrderService để tạo đơn hàng với mã giảm giá
            $validatedData = $request->only(['name', 'address', 'phone', 'coupon_code', 'discount_amount']);
            $order = $this->orderService->createOrderFromCart($validatedData, 'momo_atm');
            
            // Lấy tổng tiền sau khi đã áp dụng giảm giá
            $amount = $order->final_price;
            
            // Không cần tạo OrderItem vì đã được xử lý trong OrderService
            /*
            $carts = Cart::where('user_id', Auth::id())->with('product')->get();
            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->price
                ]);
            }
            */
            
            // Commit transaction để lưu đơn hàng trước khi chuyển hướng
            DB::commit();

            // Tích hợp MoMo
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
            $partnerCode = env('MOMO_PARTNER_CODE', 'MOMO'); // Lấy từ file .env
            $accessKey = env('MOMO_ACCESS_KEY', 'F8BBA842ECF85'); // Lấy từ file .env
            $secretKey = env('MOMO_SECRET_KEY', 'K951B6PE1waDMi640xX08PD3vg6EkVlz'); // Lấy từ file .env
            
            // Tạo orderId duy nhất để tránh trùng lặp khi người dùng thử lại
            $orderId = $order->id . '_' . time();
            $orderInfo = "Thanh toán đơn hàng #" . $order->id;
            $amount = $order->total_price;
            $redirectUrl = route('payment.momo.callback');
            $ipnUrl = route('payment.momo.callback');
            $extraData = "";

            $requestId = time() . "";
            // THAY ĐỔI: Chuyển sang `payWithATM` để thanh toán bằng thẻ
            $requestType = "payWithATM";
            
            $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            $data = [
                'partnerCode' => $partnerCode,
                'accessKey' => $accessKey,
                'requestId' => $requestId,
                'amount' => (string)$amount, // MoMo yêu cầu amount là string
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature,
                'lang' => 'vi',
            ];

            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $result = curl_exec($ch);
            curl_close($ch);

            $jsonResult = json_decode($result, true);

            if (isset($jsonResult['payUrl'])) {
                // Redirect sang trang thanh toán MoMo
                return redirect($jsonResult['payUrl']);
            } else {
                // Nếu có lỗi, quay lại và báo lỗi
                DB::rollBack(); // Rollback nếu không thể tạo link thanh toán
                return back()->with('error', 'Không thể kết nối MoMo: ' . ($jsonResult['message'] ?? 'Lỗi không xác định'));
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    public function momoCallback(Request $request)
    {
        // THAY ĐỔI: Lấy orderId gốc từ chuỗi MoMo trả về
        $momoOrderId = $request->input('orderId');
        list($orderId, $timestamp) = explode('_', $momoOrderId);

        // THAY ĐỔI: Kiểm tra `resultCode` thay vì `status`
        $resultCode = $request->input('resultCode');
        
        $order = Order::findOrFail($orderId);
        
        // `resultCode = 0` là thanh toán thành công
        if ($resultCode == 0) {
            $order->update([
                'payment_status' => 'completed',
                'status' => 'đã xác nhận', // Cập nhật trạng thái đơn hàng
                'transaction_id' => $request->input('transId')
            ]);
            
            // Xóa giỏ hàng sau khi đã thanh toán thành công
            Cart::where('user_id', $order->user_id)->delete();
            
            // Xóa thông tin coupon khỏi session sau khi thanh toán thành công
            Session::forget('coupon');
            
            return redirect()->route('orders.show', $order->id)->with('success', 'Thanh toán thành công!');
        } else {
            // Thanh toán thất bại hoặc bị hủy
            $order->update([
                'payment_status' => 'failed',
                'status' => 'đã hủy' // Cập nhật trạng thái đơn hàng
            ]);
            
            return redirect()->route('orders.show', $order->id)->with('error', 'Thanh toán thất bại hoặc đã bị hủy!');
        }
    }
}
