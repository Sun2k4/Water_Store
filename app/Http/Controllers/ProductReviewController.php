<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        // Validate input data
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        // Kiểm tra xem user đã mua sản phẩm này chưa
        $purchasedOrder = Order::where('user_id', $user->id)
            ->where('status', 'đã giao hàng')
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->first();

        if (!$purchasedOrder) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm đã mua.');
        }

        // Kiểm tra xem user đã review sản phẩm này trong đơn hàng này chưa
        $existingReview = ProductReview::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('order_id', $purchasedOrder->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        try {
            // Tạo review mới
            ProductReview::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'order_id' => $purchasedOrder->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại.');
        }
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, ProductReview $review)
    {
        // Kiểm tra quyền sở hữu
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Bạn không có quyền chỉnh sửa đánh giá này.');
        }

        // Validate input data
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        try {
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            return back()->with('success', 'Đánh giá đã được cập nhật!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật đánh giá.');
        }
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(ProductReview $review)
    {
        // Kiểm tra quyền sở hữu
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Bạn không có quyền xóa đánh giá này.');
        }

        try {
            $review->delete();
            return back()->with('success', 'Đánh giá đã được xóa!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi xóa đánh giá.');
        }
    }
}
