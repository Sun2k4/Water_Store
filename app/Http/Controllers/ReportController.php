<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function charts()
    {
        // Đặt trạng thái đơn hàng được coi là thành công (từ đã xác nhận trở đi)
        $successful_status = ['đã xác nhận', 'đang giao hàng', 'đã giao hàng', 'completed'];

        // 1. Doanh thu theo danh mục
        $categoryRevenue = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereIn('orders.status', $successful_status)
            ->select('categories.name', DB::raw('SUM(order_items.price * order_items.quantity) as revenue'))
            ->groupBy('categories.name')
            ->orderByDesc('revenue')
            ->pluck('revenue', 'name');

        $catLabels = $categoryRevenue->keys()->toArray();
        $catRevenue = $categoryRevenue->values()->map(function($val) { return (float)$val; })->toArray();
        
        // Nếu không có dữ liệu, tạo dữ liệu mẫu
        if (empty($catLabels)) {
            $catLabels = ['Không có dữ liệu'];
            $catRevenue = [0];
        }

        // 2. Doanh thu theo 30 ngày gần nhất
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(29);
        $revenueByDate = DB::table('orders')
            ->whereIn('status', $successful_status)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('revenue', 'date');
        
        $revDateLabels = [];
        $revDateData = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $d = $date->format('Y-m-d');
            $revDateLabels[] = $d;
            $revDateData[] = (float)($revenueByDate[$d] ?? 0);
        }
        
        // Nếu không có dữ liệu, tạo dữ liệu mẫu
        if (empty($revDateLabels)) {
            $revDateLabels = [Carbon::now()->format('Y-m-d')];
            $revDateData = [0];
        }

        // 3. Doanh thu theo 12 tháng gần nhất
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();
        $revenueByMonth = DB::table('orders')
             ->whereIn('status', $successful_status)
             ->whereBetween('created_at', [$startMonth, $endMonth])
             ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('SUM(total_price) as revenue'))
             ->groupBy('month')
             ->orderBy('month', 'asc')
             ->pluck('revenue', 'month');

        $revMonthLabels = [];
        $revMonthData = [];
        for ($date = $startMonth->copy(); $date <= $endMonth; $date->addMonth()) {
            $m = $date->format('Y-m');
            $revMonthLabels[] = $m;
            $revMonthData[] = (float)($revenueByMonth[$m] ?? 0);
        }
        
        // Nếu không có dữ liệu, tạo dữ liệu mẫu
        if (empty($revMonthLabels)) {
            $revMonthLabels = [Carbon::now()->format('Y-m')];
            $revMonthData = [0];
        }

        // 4. Doanh thu theo phương thức thanh toán
        $paymentRevenue = DB::table('orders')
            ->whereIn('status', $successful_status)
            ->select('payment_method', DB::raw('SUM(total_price) as revenue'))
            ->groupBy('payment_method')
            ->pluck('revenue', 'payment_method');
            
        $payLabels = $paymentRevenue->keys()->toArray();
        $payRevenue = $paymentRevenue->values()->map(function($val) { return (float)$val; })->toArray();
        
        // Nếu không có dữ liệu, tạo dữ liệu mẫu
        if (empty($payLabels)) {
            $payLabels = ['Không có dữ liệu'];
            $payRevenue = [0];
        }

        return view('admin.reports.charts', compact(
            'catLabels', 'catRevenue',
            'revDateLabels', 'revDateData',
            'revMonthLabels', 'revMonthData',
            'payLabels', 'payRevenue'
        ));
    }
}