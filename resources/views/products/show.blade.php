@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Chi tiết sản phẩm</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('products.index') }}">Quay lại</a>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a class="btn btn-warning" href="{{ route('products.edit', $product->id) }}">Chỉnh sửa</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Thông báo -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Thông tin cơ bản</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Tên sản phẩm:</strong></div>
                        <div class="col-sm-8">{{ $product->name }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Danh mục:</strong></div>
                        <div class="col-sm-8">{{ $product->category->name ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Thương hiệu:</strong></div>
                        <div class="col-sm-8">{{ $product->brand ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Hương vị:</strong></div>
                        <div class="col-sm-8">{{ $product->flavor ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Dung tích:</strong></div>
                        <div class="col-sm-8">{{ $product->volume ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Loại bao bì:</strong></div>
                        <div class="col-sm-8">{{ $product->packaging_type ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Xuất xứ:</strong></div>
                        <div class="col-sm-8">{{ $product->origin_country ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Thông tin kinh doanh</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Giá:</strong></div>
                        <div class="col-sm-8"><span class="text-success font-weight-bold">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Số lượng:</strong></div>
                        <div class="col-sm-8">
                            @if($product->isOutOfStock())
                                <span class="badge badge-danger">Hết hàng ({{ $product->quantity }} sản phẩm)</span>
                            @elseif($product->isLowStock())
                                <span class="badge badge-warning">Sắp hết ({{ $product->quantity }} sản phẩm)</span>
                            @else
                                <span class="badge badge-success">{{ $product->quantity }} sản phẩm</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Ngày hết hạn:</strong></div>
                        <div class="col-sm-8">{{ $product->expiry_date ? $product->expiry_date->format('d/m/Y') : 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Có gas:</strong></div>
                        <div class="col-sm-8">
                            @if($product->is_carbonated)
                                <span class="badge badge-info">Có gas</span>
                            @else
                                <span class="badge badge-secondary">Không có gas</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Trạng thái:</strong></div>
                        <div class="col-sm-8">
                            @if($product->is_active)
                                <span class="badge badge-success">Hoạt động</span>
                            @else
                                <span class="badge badge-danger">Không hoạt động</span>
                            @endif
                        </div>
                    </div>
                    
                    @auth
                        @if(auth()->user()->role !== 'admin')
                            <div class="row mt-3">
                                <div class="col-12">
                                    @if($product->is_active && $product->quantity > 0)
                                        <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <div class="input-group" style="max-width: 150px;">
                                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}" class="form-control">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary" disabled>
                                            <i class="fas fa-times"></i> Hết hàng
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Hình ảnh sản phẩm</h4>
                </div>
                <div class="card-body text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 300px;">
                    @else
                        <div class="text-muted">
                            <i class="fas fa-image fa-3x"></i>
                            <p>Không có hình ảnh</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Mô tả sản phẩm</h4>
                </div>
                <div class="card-body">
                    <p>{{ $product->description ?? 'Không có mô tả' }}</p>
                </div>
            </div>
            
            @if($product->ingredients)
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Thành phần</h4>
                </div>
                <div class="card-body">
                    <p>{{ $product->ingredients }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Phần đánh giá sản phẩm -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-star text-warning me-2"></i>Đánh giá sản phẩm</h4>
                </div>
                <div class="card-body">
                    <!-- Thống kê đánh giá -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h2 class="text-warning mb-1">
                                    {{ number_format($averageRating, 1) }}
                                    <small class="text-muted">/5</small>
                                </h2>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($averageRating))
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif($i - 0.5 <= $averageRating)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-muted mb-0">{{ $totalReviews }} đánh giá</p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6>Phân phối đánh giá:</h6>
                            @for($i = 5; $i >= 1; $i--)
                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-2">{{ $i }} sao</span>
                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                        <div class="progress-bar bg-warning" 
                                             style="width: {{ $totalReviews > 0 ? round(($ratingDistribution[$i] / $totalReviews) * 100, 2) : 0 }}%;">
                                        </div>
                                    </div>
                                    <span class="text-muted">{{ $ratingDistribution[$i] }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Form đánh giá (chỉ hiển thị nếu user có thể đánh giá) -->
                    @if($canReview)
                        <div class="border-top pt-4 mb-4">
                            <h5>
                                @if($userReview)
                                    Chỉnh sửa đánh giá của bạn
                                @else
                                    Viết đánh giá
                                @endif
                            </h5>
                            <form action="{{ $userReview ? route('reviews.update', $userReview->id) : route('reviews.store') }}" method="POST">
                                @csrf
                                @if($userReview)
                                    @method('PUT')
                                @endif
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="mb-3">
                                    <label class="form-label">Đánh giá của bạn:</label>
                                    <div class="rating-input">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" 
                                                   {{ ($userReview && $userReview->rating == $i) ? 'checked' : '' }} required>
                                            <label for="star{{ $i }}" class="star-label">
                                                <i class="fas fa-star"></i>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Nhận xét (tùy chọn):</label>
                                    <textarea name="comment" id="comment" class="form-control" rows="4" 
                                              placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này...">{{ $userReview ? $userReview->comment : '' }}</textarea>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        {{ $userReview ? 'Cập nhật đánh giá' : 'Gửi đánh giá' }}
                                    </button>
                                    @if($userReview)
                                        <form action="{{ route('reviews.destroy', $userReview->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" 
                                                    onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                                <i class="fas fa-trash me-2"></i>Xóa đánh giá
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </form>
                        </div>
                    @elseif(auth()->check())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Bạn cần mua sản phẩm này để có thể đánh giá.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để đánh giá sản phẩm.
                        </div>
                    @endif

                    <!-- Danh sách đánh giá -->
                    @if($product->reviews->count() > 0)
                        <div class="border-top pt-4">
                            <h5>Tất cả đánh giá ({{ $totalReviews }})</h5>
                            @foreach($product->reviews->sortByDesc('created_at') as $review)
                                <div class="review-item border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong>{{ $review->user->name }}</strong>
                                            <div class="rating-display">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    @if($review->comment)
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Thông tin hệ thống</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y H:i:s') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Cập nhật lần cuối:</strong> {{ $product->updated_at->format('d/m/Y H:i:s') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input .star-label {
    color: #ddd;
    font-size: 1.5rem;
    cursor: pointer;
    transition: color 0.2s;
}

.rating-input input[type="radio"]:checked ~ .star-label,
.rating-input .star-label:hover,
.rating-input .star-label:hover ~ .star-label {
    color: #ffc107;
}

.rating-display {
    font-size: 0.9rem;
}

.review-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}
</style>
@endsection