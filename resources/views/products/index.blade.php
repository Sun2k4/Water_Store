@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-water text-white py-5 mb-5 rounded-large">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-tint me-3"></i>Nước sạch chất lượng cao
                </h1>
                <p class="lead mb-4">Khám phá bộ sưu tập nước uống đa dạng, từ nước tinh khiết đến nước khoáng thiên nhiên</p>
                <div class="d-flex gap-3">
                    <span class="badge bg-light text-dark fs-6 px-3 py-2">
                        <i class="fas fa-shield-alt me-2"></i>An toàn tuyệt đối
                    </span>
                    <span class="badge bg-light text-dark fs-6 px-3 py-2">
                        <i class="fas fa-truck me-2"></i>Giao hàng nhanh
                    </span>
                    <span class="badge bg-light text-dark fs-6 px-3 py-2">
                        <i class="fas fa-star me-2"></i>Chất lượng 5 sao
                    </span>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-tint fa-10x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Header Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-headline mb-2">
                    <i class="fas fa-shopping-bag text-highlight me-2"></i>Danh sách sản phẩm
                </h2>
                <p class="text-secondary mb-0">Tìm kiếm và lựa chọn sản phẩm nước uống phù hợp</p>
            </div>
            @auth
                @if(Auth::user()->role === 'admin')
                    <a class="btn btn-primary" href="{{ route('products.create') }}">
                        <i class="fas fa-plus me-2"></i>Thêm sản phẩm mới
                    </a>
                @endif
            @endauth
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card shadow-soft mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fas fa-search text-highlight me-2"></i>Tìm kiếm sản phẩm
                </label>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Nhập tên sản phẩm..." id="searchInput">
                    <button class="btn btn-outline" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    <i class="fas fa-filter text-highlight me-2"></i>Lọc theo danh mục
                </label>
                <select class="form-select" id="categoryFilter">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Products Grid -->
<div class="row" id="productsContainer">
    @forelse ($products as $product)
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4 product-item animate-fade-in-up" data-category="{{ $product->category_id }}" data-name="{{ strtolower($product->name) }}">
            <div class="card product-card h-100">
                <div class="position-relative">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="product-image" alt="{{ $product->name }}">
                    @else
                        <div class="product-image d-flex align-items-center justify-content-center">
                            <i class="fas fa-tint fa-4x text-highlight opacity-50"></i>
                        </div>
                    @endif
                    
                    @if(!$product->is_active)
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge badge-primary">Không hoạt động</span>
                        </div>
                    @elseif($product->isOutOfStock())
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge badge-danger">Hết hàng</span>
                        </div>
                    @elseif($product->isLowStock())
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge badge-warning">Sắp hết</span>
                        </div>
                    @endif
                    
                    <!-- Wishlist button for non-admin users -->
                    @auth
                        @if(Auth::user()->role !== 'admin')
                            <div class="position-absolute top-0 start-0 m-2">
                                <button type="button" class="wishlist-btn" data-product-id="{{ $product->id }}">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        @endif
                    @endauth
                </div>
                
                <div class="product-info">
                    <h5 class="product-title">{{ $product->name }}</h5>
                    <p class="text-secondary small mb-2">
                        <i class="fas fa-tag me-1"></i>{{ $product->category->name ?? 'N/A' }}
                        @if($product->brand)
                            <span class="ms-2">
                                <i class="fas fa-industry me-1"></i>{{ $product->brand }}
                            </span>
                        @endif
                    </p>
                    <p class="text-secondary small flex-grow-1 mb-3">{{ Str::limit($product->description, 80) }}</p>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="product-price mb-0">{{ number_format($product->price, 0, ',', '.') }} VNĐ</h5>
                            <small class="text-secondary">
                                <i class="fas fa-box me-1"></i>Còn: {{ $product->quantity }}
                            </small>
                        </div>
                        
                        <div class="product-actions">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline btn-sm flex-grow-1">
                                <i class="fas fa-eye me-1"></i>Xem chi tiết
                            </a>
                            
                            @auth
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    @if($product->is_active && $product->quantity > 0)
                                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-outline btn-sm" disabled>
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @endif
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-cart-plus"></i>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <div class="card shadow-soft">
                    <div class="card-body py-5">
                        <i class="fas fa-tint fa-5x text-highlight mb-4 opacity-50"></i>
                        <h4 class="text-headline mb-3">Chưa có sản phẩm nào</h4>
                        <p class="text-secondary mb-4">Hiện tại chưa có sản phẩm nước nào được thêm vào hệ thống.</p>
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('products.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Thêm sản phẩm đầu tiên
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($products->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Thông tin hiển thị -->
                <div class="pagination-info">
                    <small class="text-muted">
                        Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} 
                        trong tổng số {{ $products->total() }} sản phẩm
                    </small>
                </div>
                
                <!-- Phân trang -->
                <x-custom-pagination :paginator="$products" />
            </div>
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        filterProducts();
    });
    
    document.getElementById('categoryFilter').addEventListener('change', function() {
        filterProducts();
    });
    
    function filterProducts() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const categoryFilter = document.getElementById('categoryFilter').value;
        const products = document.querySelectorAll('.product-item');
        
        products.forEach(function(product) {
            const productName = product.getAttribute('data-name');
            const productCategory = product.getAttribute('data-category');
            
            const matchesSearch = productName.includes(searchTerm);
            const matchesCategory = categoryFilter === '' || productCategory === categoryFilter;
            
            if (matchesSearch && matchesCategory) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });
    }

    // Wishlist functionality
    document.addEventListener('DOMContentLoaded', function() {
        const wishlistButtons = document.querySelectorAll('.wishlist-btn');
        
        wishlistButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const icon = this.querySelector('i');
                
                fetch('{{ route("wishlist.toggle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'added') {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        this.classList.remove('btn-outline-danger');
                        this.classList.add('btn-danger');
                        showAlert('success', data.message);
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        this.classList.remove('btn-danger');
                        this.classList.add('btn-outline-danger');
                        showAlert('info', data.message);
                    }
                    
                    // Cập nhật số lượng wishlist
                    updateWishlistCount();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Có lỗi xảy ra!');
                });
            });
        });
    });

    function updateWishlistCount() {
        fetch('{{ route("wishlist.count") }}')
            .then(response => response.text())
            .then(count => {
                document.getElementById('wishlist-count').textContent = count;
            });
    }

    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '9999';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
</script>
@endsection