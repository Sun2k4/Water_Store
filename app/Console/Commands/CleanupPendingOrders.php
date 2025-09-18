<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CleanupPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cleanup-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hủy các đơn hàng MoMo ATM đang xử lý quá 60 phút và hoàn trả số lượng sản phẩm';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Bắt đầu dọn dẹp đơn hàng MoMo ATM quá hạn...');
        
        // Tìm các đơn hàng MoMo ATM đang xử lý quá 60 phút
        $cutoffTime = Carbon::now()->subMinutes(60);
        
        $pendingOrders = Order::where('payment_method', 'momo_atm')
            ->where('status', 'đang xử lý')
            ->where('created_at', '<', $cutoffTime)
            ->get();
        
        $count = $pendingOrders->count();
        $this->info("Tìm thấy {$count} đơn hàng cần xử lý.");
        
        foreach ($pendingOrders as $order) {
            DB::beginTransaction();
            try {
                // Cập nhật trạng thái đơn hàng thành 'đã hủy'
                $order->status = 'đã hủy';
                $order->save();
                
                // Hoàn trả số lượng sản phẩm
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('quantity', $item->quantity);
                        $this->info("Đã hoàn trả {$item->quantity} sản phẩm {$product->name} vào kho.");
                    }
                }
                
                $this->info("Đã hủy đơn hàng #{$order->id} và hoàn trả sản phẩm.");
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Lỗi khi xử lý đơn hàng #{$order->id}: {$e->getMessage()}");
            }
        }
        
        $this->info('Hoàn thành dọn dẹp đơn hàng.');
        return 0;
    }
}