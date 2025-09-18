<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Water Store') }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/color-system.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        
        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0px);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .animate-pulse {
            animation: pulse 2s infinite;
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        /* Delay animations */
        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }
        .delay-4 { animation-delay: 0.8s; }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 50%, #0288d1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiB2aWV3Qm94PSIwIDAgMTI4MCAxNDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjEpIj48cGF0aCBkPSJNMTI4MCAwTDY0MCA3MCAwIDB2MTQwbDY0MC03MCAxMjgwIDcwVjB6Ii8+PC9nPjwvc3ZnPg==');
            background-size: 100% 100px;
            background-repeat: no-repeat;
            background-position: bottom;
            opacity: 0.5;
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        /* Feature Cards */
        .feature-card {
            background: var(--color-main);
            border-radius: var(--radius-large);
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(31, 18, 53, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-strong);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1);
        }
        
        .feature-icon.primary { background: var(--gradient-primary); color: var(--color-button-text); }
        .feature-icon.secondary { background: var(--gradient-secondary); color: var(--color-main); }
        .feature-icon.tertiary { background: linear-gradient(135deg, var(--color-tertiary) 0%, #f9c74f 100%); color: var(--color-headline); }
        .feature-icon.water { background: var(--gradient-water); color: var(--color-main); }
        
        /* Stats Section */
        .stats-section {
            background: var(--gradient-secondary);
            color: var(--color-main);
            position: relative;
            overflow: hidden;
        }
        
        .stats-section::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiB2aWV3Qm94PSIwIDAgMTI4MCAxNDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjEpIj48cGF0aCBkPSJNMTI4MCAwTDY0MCA3MCAwIDB2MTQwbDY0MC03MCAxMjgwIDcwVjB6Ii8+PC9nPjwvc3ZnPg==');
            background-size: 100% 70px;
            background-repeat: no-repeat;
            background-position: top;
            opacity: 0.3;
            z-index: 1;
        }
        
        .stat-item {
            text-align: center;
            padding: 2rem 1rem;
            position: relative;
            z-index: 2;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--color-tertiary);
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1.1rem;
            font-weight: 500;
            margin-top: 0.5rem;
        }
        
        /* Navbar */
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .navbar-brand i {
            font-size: 1.5rem;
            color: var(--color-highlight);
        }
        
        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--color-highlight);
        }
        
        /* Buttons */
        .btn {
            border-radius: var(--radius-medium);
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            color: var(--color-button-text);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid var(--color-main);
            color: var(--color-main);
        }
        
        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
        }
        
        /* Additional styles for improved welcome page */
        .text-gradient {
            background: linear-gradient(45deg, #ffffff, #e3f2fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .feature-icon-small {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .feature-highlight {
            transition: transform 0.3s ease;
        }
        
        .feature-highlight:hover {
            transform: translateY(-5px);
        }
        
        .water-animation {
            position: relative;
            display: inline-block;
        }
        
        .water-drops {
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
        }
        
        .water-drops i {
            position: absolute;
            animation: float 4s ease-in-out infinite;
        }
        
        .water-drops i:nth-child(2) {
            animation-delay: 1s;
        }
        
        .water-drops i:nth-child(3) {
            animation-delay: 2s;
        }
        
        .min-vh-100 {
            min-height: 100vh;
        }
        
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        }
        
        .product-card .card-img-top {
            transition: transform 0.3s ease;
        }
        
        .product-card:hover .card-img-top {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('products.index') }}">
                <i class="fas fa-tint text-highlight"></i> 
                <span class="text-headline">{{ config('app.name', 'Water Store') }}</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.index') }}">
                                <i class="fas fa-home me-1"></i>Trang chủ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                <i class="fas fa-shopping-cart me-1"></i>Giỏ hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('wishlist.index') }}">
                                <i class="fas fa-heart me-1"></i>Yêu thích
                            </a>
                        </li>
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i>Admin
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Đăng ký
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-white">
        <div class="container">
            <div class="row align-items-center" style="min-height: 80vh;">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="display-3 fw-bold mb-3 animate-fade-in-up">
                            <i class="fas fa-tint me-2 text-info"></i>
                            <span class="text-gradient">Water Store</span>
                        </h1>
                        <p class="lead mb-4 animate-fade-in-up delay-1">
                            Nước sạch chất lượng cao cho sức khỏe gia đình bạn
                        </p>
                        
                        <div class="d-flex flex-wrap gap-3 mb-4 animate-fade-in-up delay-2">
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-shield-alt me-1"></i>An toàn 100%
                            </span>
                            <span class="badge bg-warning text-dark px-3 py-2">
                                <i class="fas fa-truck-fast me-1"></i>Giao hàng nhanh
                            </span>
                            <span class="badge bg-info px-3 py-2">
                                <i class="fas fa-star me-1"></i>Chất lượng 5⭐
                            </span>
                        </div>
                        
                        <div class="animate-fade-in-up delay-3">
                            @auth
                                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg me-3">
                                    <i class="fas fa-shopping-bag me-2"></i>Mua ngay
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-3">
                                    <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Đăng ký
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="hero-image animate-fade-in-up delay-2">
                        <i class="fas fa-tint fa-10x text-info opacity-75 animate-float"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="h3 mb-2">
                    <i class="fas fa-star text-warning me-2"></i>Sản phẩm nổi bật
                </h2>
            </div>
            
            <div class="row g-3">
                @php
                    $featuredProducts = \App\Models\Product::with('category')
                        ->where('is_active', true)
                        ->inRandomOrder()
                        ->take(3)
                        ->get();
                @endphp
                
                @foreach($featuredProducts as $product)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm border-0 product-card">
                        <div class="position-relative overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     class="card-img-top" 
                                     alt="{{ $product->name }}"
                                     style="height: 180px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center text-white" 
                                     style="height: 180px;">
                                    <i class="fas fa-tint fa-2x opacity-50"></i>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h6 class="card-title mb-2">{{ $product->name }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 text-primary fw-bold mb-0">
                                    {{ number_format($product->price, 0, ',', '.') }}đ
                                </span>
                                <a href="{{ route('products.show', $product) }}" 
                                   class="btn btn-primary btn-sm">
                                    Xem
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    Xem tất cả
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section py-3 bg-primary text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <div class="stat-label">Sản phẩm</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">10K+</span>
                        <div class="stat-label">Khách hàng</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <div class="stat-label">Hỗ trợ</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">100%</span>
                        <div class="stat-label">Chất lượng</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-4">
        <div class="container">
            <div class="text-center mb-4">
                <h3 class="mb-2">Tại sao chọn chúng tôi?</h3>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card text-center p-3">
                        <div class="feature-icon primary mb-2">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h6 class="mb-2">An toàn</h6>
                        <small class="text-secondary">Sản phẩm được kiểm định chất lượng</small>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card text-center p-3">
                        <div class="feature-icon secondary mb-2">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h6 class="mb-2">Giao nhanh</h6>
                        <small class="text-secondary">Giao hàng trong vòng 24h</small>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card text-center p-3">
                        <div class="feature-icon tertiary mb-2">
                            <i class="fas fa-star"></i>
                        </div>
                        <h6 class="mb-2">Chất lượng</h6>
                        <small class="text-secondary">Thương hiệu uy tín hàng đầu</small>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card text-center p-3">
                        <div class="feature-icon water mb-2">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h6 class="mb-2">Hỗ trợ 24/7</h6>
                        <small class="text-secondary">Chăm sóc khách hàng tận tâm</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-primary text-white py-4">
        <div class="container text-center">
            <h4 class="mb-3">Sẵn sàng bắt đầu?</h4>
            <a href="{{ route('products.index') }}" class="btn btn-light">
                <i class="fas fa-shopping-bag me-2"></i>Xem sản phẩm
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-secondary text-light py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">
                        <i class="fas fa-tint text-highlight me-2"></i>
                        <strong>{{ config('app.name', 'Water Store') }}</strong> - Nước sạch cho cuộc sống khỏe mạnh
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; 2024 {{ config('app.name', 'Water Store') }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>