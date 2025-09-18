<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'price',
        'image',
        'brand',
        'flavor',
        'volume',
        'packaging_type',
        'expiry_date',
        'is_carbonated',
        'ingredients',
        'origin_country',
        'is_active',
        'category_id'
    ];

    protected $casts = [
        'is_carbonated' => 'boolean',
        'is_active' => 'boolean',
        'expiry_date' => 'date',
        'price' => 'decimal:2'
    ];

    /**
     * Quan hệ belongsTo với Category
     * Một sản phẩm thuộc về một danh mục
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Quan hệ hasMany với ProductReview
     * Một sản phẩm có thể có nhiều đánh giá
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Lấy điểm đánh giá trung bình của sản phẩm
     */
    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Lấy tổng số lượt đánh giá
     */
    public function totalReviews()
    {
        return $this->reviews()->count();
    }

    /**
     * Lấy phân phối đánh giá theo sao (1-5 sao)
     */
    public function ratingDistribution()
    {
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = $this->reviews()->where('rating', $i)->count();
        }
        return $distribution;
    }
    
    /**
     * Kiểm tra xem sản phẩm đã hết hàng chưa
     * @return bool
     */
    public function isOutOfStock()
    {
        return $this->quantity <= 0;
    }
    
    /**
     * Kiểm tra xem sản phẩm có sắp hết hàng không (dưới 5 sản phẩm)
     * @return bool
     */
    public function isLowStock()
    {
        return $this->quantity > 0 && $this->quantity <= 5;
    }
}
