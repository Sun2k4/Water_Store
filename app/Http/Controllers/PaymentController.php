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

            // Tích hợp MoMo với xử lý lỗi tốt hơn
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
            $partnerCode = env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529');
            $accessKey = env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j');
            $secretKey = env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa');
            
            // Tạo orderId duy nhất để tránh trùng lặp khi người dùng thử lại
            $orderId = $order->id . '_' . time();
            $orderInfo = "Thanh toán đơn hàng #" . $order->id;
            $amount = $order->final_price; // Sử dụng final_price thay vì total_price
            $redirectUrl = route('payment.momo.callback');
            $ipnUrl = route('payment.momo.callback');
            $extraData = "";

            $requestId = time() . "";
            $requestType = "payWithATM";
            
            $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            $data = [
                'partnerCode' => $partnerCode,
                'accessKey' => $accessKey,
                'requestId' => $requestId,
                'amount' => (string)$amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature,
                'lang' => 'vi',
            ];

            // Cải thiện cURL với timeout và error handling
            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            // Log request để debug
            \Log::info('MoMo Request', [
                'endpoint' => $endpoint,
                'data' => $data,
                'signature_raw' => $rawHash
            ]);
            
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // Log response để debug
            \Log::info('MoMo Response', [
                'http_code' => $httpCode,
                'response' => $result,
                'curl_error' => $curlError
            ]);

            if ($curlError) {
                throw new \Exception('Lỗi kết nối MoMo: ' . $curlError);
            }

            if ($httpCode !== 200) {
                throw new \Exception('MoMo API trả về lỗi HTTP: ' . $httpCode);
            }

            $jsonResult = json_decode($result, true);

            if (isset($jsonResult['payUrl'])) {
                // Redirect sang trang thanh toán MoMo
                return redirect($jsonResult['payUrl']);
            } else {
                // Log chi tiết lỗi để debug
                \Log::error('MoMo API Error', [
                    'response' => $jsonResult,
                    'data_sent' => $data,
                    'order_id' => $order->id
                ]);
                
                throw new \Exception('Không thể tạo link thanh toán MoMo: ' . ($jsonResult['message'] ?? 'Lỗi không xác định'));
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
