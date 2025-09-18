<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'usage_limit',
        'usage_count',
        'expires_at',
        'is_active'
    ];
    
    protected $casts = [
        'expires_at' => 'datetime',
    ];
    
    /**
     * Kiểm tra xem coupon có hợp lệ không
     *
     * @param float $orderAmount Tổng giá trị đơn hàng
     * @return bool
     */
    public function isValid($orderAmount = 0)
    {
        // Kiểm tra trạng thái hoạt động
        if (!$this->is_active) {
            return false;
        }
        
        // Kiểm tra hạn sử dụng
        if ($this->expires_at && Carbon::now()->gt($this->expires_at)) {
            return false;
        }
        
        // Kiểm tra số lần sử dụng
        if ($this->usage_count >= $this->usage_limit) {
            return false;
        }
        
        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($orderAmount < $this->min_order_amount) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Tính toán số tiền được giảm
     *
     * @param float $orderAmount Tổng giá trị đơn hàng
     * @return float
     */
    public function calculateDiscount($orderAmount)
    {
        if (!$this->isValid($orderAmount)) {
            return 0;
        }
        
        if ($this->type === 'fixed') {
            return $this->value;
        }
        
        if ($this->type === 'percent') {
            return ($orderAmount * $this->value) / 100;
        }
        
        return 0;
    }
    
    /**
     * Tăng số lần sử dụng của coupon
     *
     * @return bool
     */
    public function incrementUsage()
    {
        $this->usage_count += 1;
        return $this->save();
    }
}
