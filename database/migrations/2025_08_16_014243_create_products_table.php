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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên sản phẩm
            $table->text('description')->nullable(); // Mô tả sản phẩm
            $table->integer('quantity')->default(0); // Số lượng tồn kho
            $table->decimal('price', 10, 2); // Giá sản phẩm
            $table->string('image')->nullable(); // Đường dẫn hình ảnh
            $table->string('brand')->nullable(); // Thương hiệu
            $table->string('flavor')->nullable(); // Hương vị
            $table->string('volume')->nullable(); // Dung tích (ml, lít)
            $table->string('packaging_type')->nullable(); // Loại bao bì (chai, lon, hộp)
            $table->date('expiry_date')->nullable(); // Ngày hết hạn
            $table->boolean('is_carbonated')->default(false); // Có gas hay không
            $table->text('ingredients')->nullable(); // Thành phần
            $table->string('origin_country')->nullable(); // Xuất xứ
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Khóa ngoại đến bảng categories
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
        Schema::dropIfExists('products');
    }
};
