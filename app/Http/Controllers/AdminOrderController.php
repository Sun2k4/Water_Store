<?php


namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $perPageOptions = [5, 10, 15, 20, 25, 50];
        
        // Validate per_page value
        if (!in_array($perPage, $perPageOptions)) {
            $perPage = 10;
        }
        
        $orders = Order::orderBy('created_at', 'desc')->paginate($perPage);
        
        // Get all orders for statistics (not paginated)
        $allOrders = Order::all();
        
        return view('admin.orders.index', compact('orders', 'allOrders', 'perPageOptions'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->input('status');
        $order->save();
        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}