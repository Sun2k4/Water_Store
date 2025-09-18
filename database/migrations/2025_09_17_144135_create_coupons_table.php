<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã coupon (ví dụ: 'SUMMER2025')
            $table->enum('type', ['fixed', 'percent']); // Loại giảm giá: cố định hoặc phần trăm
            $table->decimal('value', 10, 2); // Giá trị giảm giá
            $table->decimal('min_order_amount', 10, 2)->default(0); // Số tiền đơn hàng tối thiểu
            $table->integer('usage_limit')->default(1); // Tổng số lần mã có thể được sử dụng
            $table->integer('usage_count')->default(0); // Số lần mã đã được sử dụng
            $table->timestamp('expires_at')->nullable(); // Thời gian hết hạn
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
