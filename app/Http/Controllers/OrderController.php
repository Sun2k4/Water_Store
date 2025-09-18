<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        // Kiểm tra nếu người dùng là admin thì hiển thị tất cả đơn hàng
        if (Auth::user()->role === 'admin') {
            $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        } else {
            // Người dùng thường chỉ xem được đơn hàng của họ
            $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        }
        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        
        // Kiểm tra quyền truy cập: admin có thể xem tất cả đơn hàng, người dùng chỉ xem đơn của mình
        if ($order->user_id != Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền xem đơn hàng này');
        }
        
        return view('orders.show', compact('order'));
    }
}