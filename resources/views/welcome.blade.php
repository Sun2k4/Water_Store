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
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4 animate-fade-in-up">
                        <i class="fas fa-tint me-3"></i>Nước sạch chất lượng cao
                    </h1>
                    <p class="lead mb-4 animate-fade-in-up">
                        Khám phá bộ sưu tập nước uống đa dạng, từ nước tinh khiết đến nước khoáng thiên nhiên. 
                        Đảm bảo sức khỏe cho bạn và gia đình.
                    </p>
                    <div class="d-flex gap-3 mb-4 animate-fade-in-up">
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
                    <div class="animate-fade-in-up">
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-shopping-bag me-2"></i>Mua ngay
                        </a>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-outline btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Đăng ký
                            </a>
                        @endguest
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="animate-fade-in-up">
                        <i class="fas fa-tint fa-10x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <div class="stat-label">Sản phẩm đa dạng</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">10K+</span>
                        <div class="stat-label">Khách hàng tin tưởng</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <div class="stat-label">Hỗ trợ khách hàng</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">100%</span>
                        <div class="stat-label">Chất lượng đảm bảo</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="text-headline mb-3">Tại sao chọn chúng tôi?</h2>
                <p class="text-secondary lead">Những lý do khiến khách hàng tin tưởng và lựa chọn</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card text-center p-4 h-100">
                        <div class="feature-icon primary">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="text-headline mb-3">An toàn tuyệt đối</h5>
                        <p class="text-secondary">Tất cả sản phẩm đều được kiểm định chất lượng và đảm bảo an toàn cho sức khỏe.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card text-center p-4 h-100">
                        <div class="feature-icon secondary">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h5 class="text-headline mb-3">Giao hàng nhanh</h5>
                        <p class="text-secondary">Giao hàng trong vòng 24h với đội ngũ vận chuyển chuyên nghiệp và tận tâm.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card text-center p-4 h-100">
                        <div class="feature-icon tertiary">
                            <i class="fas fa-star"></i>
                        </div>
                        <h5 class="text-headline mb-3">Chất lượng 5 sao</h5>
                        <p class="text-secondary">Sản phẩm được lựa chọn kỹ càng từ các thương hiệu uy tín hàng đầu.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card text-center p-4 h-100">
                        <div class="feature-icon water">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h5 class="text-headline mb-3">Hỗ trợ 24/7</h5>
                        <p class="text-secondary">Đội ngũ chăm sóc khách hàng luôn sẵn sàng hỗ trợ bạn mọi lúc, mọi nơi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-primary py-5">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="text-headline mb-3">Sẵn sàng bắt đầu?</h2>
                    <p class="text-secondary mb-4">Khám phá ngay bộ sưu tập nước uống đa dạng và chất lượng cao của chúng tôi.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Xem sản phẩm
                    </a>
                </div>
            </div>
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