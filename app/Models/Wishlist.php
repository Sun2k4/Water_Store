<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id'
    ];

    // Một wishlist item thuộc về một user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Một wishlist item liên kết với một sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
