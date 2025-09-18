<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id', 
        'order_id',
        'rating',
        'comment'
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship with Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scope for filtering by rating
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    // Scope for getting reviews with user info
    public function scopeWithUser($query)
    {
        return $query->with('user:id,name');
    }
}
