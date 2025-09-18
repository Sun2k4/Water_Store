@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Danh sách yêu thích</h2>
    
    @if($wishlists->count() > 0)
        <div class="row">
            @foreach($wishlists as $wishlist)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            @if($wishlist->product->image)
                                <img src="{{ asset('storage/' . $wishlist->product->image) }}" class="card-img-top" alt="{{ $wishlist->product->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            <!-- Nút xóa khỏi yêu thích -->
                            <form action="{{ route('wishlist.remove', $wishlist->id) }}" method="POST" class="position-absolute" style="top: 10px; right: 10px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm rounded-circle" title="Xóa khỏi yêu thích">
                                    <i class="fas fa-heart-broken"></i>
                                </button>
                            </form>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $wishlist->product->name }}</h5>
                            <p class="card-text text-muted">{{ $wishlist->product->category->name }}</p>
                            <p class="card-text">{{ Str::limit($wishlist->product->description, 100) }}</p>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 text-primary mb-0">{{ number_format($wishlist->product->price) }} VND</span>
                                    <small class="text-muted">Còn: {{ $wishlist->product->quantity }}</small>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    @if($wishlist->product->quantity > 0)
                                        <form action="{{ route('cart.add') }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $wishlist->product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fas fa-times"></i> Hết hàng
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('products.show', $wishlist->product->id) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Thống kê -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">Tổng sản phẩm yêu thích</h5>
                        <h3 class="text-primary">{{ $wishlists->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">Tổng giá trị</h5>
                        <h3 class="text-success">{{ number_format($wishlists->sum(function($w) { return $w->product->price; })) }} VND</h3>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-heart fa-5x text-muted mb-4"></i>
            <h4 class="text-muted">Danh sách yêu thích trống</h4>
            <p class="text-muted">Hãy thêm sản phẩm vào danh sách yêu thích để xem lại sau!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i> Mua sắm ngay
            </a>
        </div>
    @endif
</div>
@endsection
