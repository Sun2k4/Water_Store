<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ config('app.name', 'Water Store') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/color-system.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 15px 20px;
            border-radius: var(--radius-medium);
            margin: 5px 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.25);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .stat-card {
            background: var(--color-main);
            border-radius: var(--radius-large);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border: 1px solid rgba(31, 18, 53, 0.1);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .admin-header {
            background: var(--color-main);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(31, 18, 53, 0.1);
        }
        .chart-container {
            background: var(--color-main);
            border-radius: var(--radius-large);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border: 1px solid rgba(31, 18, 53, 0.1);
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }
        .chart-wrapper {
            position: relative;
            height: 300px;
            width: 100%;
        }
        @media (max-width: 768px) {
            .chart-container {
                padding: 15px;
            }
            .chart-wrapper {
                height: 250px;
            }
        }
        .quick-actions {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: var(--radius-large);
            padding: 25px;
            color: white;
        }
        .quick-actions .btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            transition: all 0.3s ease;
        }
        .quick-actions .btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        .activity-feed {
            max-height: 400px;
            overflow-y: auto;
        }
        .activity-item {
            padding: 15px;
            border-left: 3px solid var(--color-highlight);
            margin-bottom: 10px;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 0 var(--radius-medium) var(--radius-medium) 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <div class="p-3">
                        <h4 class="text-white mb-4">
                            <i class="fas fa-tint text-tertiary me-2"></i>Admin Panel
                        </h4>
                        <div class="text-white-50 mb-3">
                            <i class="fas fa-user-circle me-2"></i>Xin chào, {{ Auth::user()->name }}
                        </div>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="/admin/dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="/admin/products">
                            <i class="fas fa-box me-2"></i> Quản lý sản phẩm
                        </a>
                        <a class="nav-link" href="/admin/categories">
                            <i class="fas fa-tags me-2"></i> Quản lý danh mục
                        </a>
                        <a class="nav-link" href="/admin/users">
                            <i class="fas fa-users me-2"></i> Quản lý người dùng
                        </a>
                        <a class="nav-link" href="/admin/orders">
                            <i class="fas fa-shopping-cart me-2"></i> Quản lý đơn hàng
                        </a>
                        <a class="nav-link" href="{{ route('admin.coupons.index') }}">
                            <i class="fas fa-ticket-alt me-2"></i> Quản lý mã giảm giá
                        </a>
                        <a class="nav-link" href="/admin/reports">
                            <i class="fas fa-chart-bar me-2"></i> Báo cáo & Thống kê
                        </a>
                        <hr class="text-white-50">
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="fas fa-home me-2"></i> Về trang chủ
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                                <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="text-headline mb-0">
                            <i class="fas fa-tachometer-alt text-highlight me-2"></i>Dashboard
                        </h2>
                        <div class="text-secondary">
                            <i class="fas fa-calendar-alt me-2"></i>{{ now()->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon" style="background: var(--gradient-water)">
                                        <i class="fas fa-tint"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h3 class="mb-0 text-headline">{{ $totalProducts }}</h3>
                                        <p class="text-secondary mb-0">Sản phẩm nước</p>
                                        <small class="text-success">
                                            <i class="fas fa-arrow-up"></i> +12% so với tháng trước
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon" style="background: var(--gradient-primary)">
                                        <i class="fas fa-tags"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h3 class="mb-0 text-headline">{{ $totalCategories }}</h3>
                                        <p class="text-secondary mb-0">Danh mục</p>
                                        <small class="text-info">
                                            <i class="fas fa-equals"></i> Không thay đổi
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon" style="background: var(--gradient-secondary)">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h3 class="mb-0 text-headline">{{ $totalUsers }}</h3>
                                        <p class="text-secondary mb-0">Khách hàng</p>
                                        <small class="text-success">
                                            <i class="fas fa-arrow-up"></i> +8% so với tháng trước
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon" style="background: linear-gradient(135deg, #f27059 0%, #ff9a3c 100%)">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h3 class="mb-0 text-headline">{{ $totalCoupons }}</h3>
                                        <p class="text-secondary mb-0">Mã giảm giá</p>
                                        <small class="text-warning">
                                            <i class="fas fa-clock"></i> {{ $totalCoupons > 0 ? '3 sắp hết hạn' : 'Chưa có mã nào' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts and Quick Actions Row -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="chart-container">
                                <h5 class="mb-3 text-headline">
                                    <i class="fas fa-chart-line text-highlight me-2"></i>Thống kê bán hàng 7 ngày qua
                                </h5>
                                <div class="chart-wrapper">
                                    <canvas id="salesChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="quick-actions">
                                <h5 class="mb-3">
                                    <i class="fas fa-bolt me-2"></i>Thao tác nhanh
                                </h5>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('products.create') }}" class="btn">
                                        <i class="fas fa-plus me-2"></i>Thêm sản phẩm
                                    </a>
                                    <a href="{{ route('admin.coupons.create') }}" class="btn">
                                        <i class="fas fa-ticket-alt me-2"></i>Tạo mã giảm giá
                                    </a>
                                    <a href="/admin/orders" class="btn">
                                        <i class="fas fa-shopping-cart me-2"></i>Xem đơn hàng
                                    </a>
                                    <a href="/admin/reports" class="btn">
                                        <i class="fas fa-chart-bar me-2"></i>Xem báo cáo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Products and Coupons -->
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0 text-headline">
                                        <i class="fas fa-tint text-highlight me-2"></i>Sản phẩm nước mới nhất
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($recentProducts->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Hình ảnh</th>
                                                        <th>Tên sản phẩm</th>
                                                        <th>Danh mục</th>
                                                        <th>Giá</th>
                                                        <th>Thao tác</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentProducts as $product)
                                                        <tr>
                                                            <td>
                                                                @if($product->image)
                                                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                                                         alt="{{ $product->name }}" 
                                                                         class="rounded" 
                                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                                @else
                                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                                         style="width: 50px; height: 50px;">
                                                                        <i class="fas fa-image text-muted"></i>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td>{{ $product->name }}</td>
                                                            <td>
                                                                <span class="badge bg-primary">{{ $product->category->name }}</span>
                                                            </td>
                                                            <td class="fw-bold text-success">
                                                                {{ number_format($product->price, 0, ',', '.') }}đ
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('products.show', $product) }}" 
                                                                   class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('products.edit', $product) }}" 
                                                                   class="btn btn-sm btn-outline-warning">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Chưa có sản phẩm nào</p>
                                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Thêm sản phẩm đầu tiên
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0 text-headline">
                                        <i class="fas fa-ticket-alt text-warning me-2"></i>Mã giảm giá gần đây
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($recentCoupons->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Mã</th>
                                                        <th>Loại</th>
                                                        <th>Giá trị</th>
                                                        <th>Hạn dùng</th>
                                                        <th>Thao tác</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentCoupons as $coupon)
                                                        <tr>
                                                            <td><span class="badge bg-info">{{ $coupon->code }}</span></td>
                                                            <td>{{ $coupon->type == 'fixed' ? 'Cố định' : 'Phần trăm' }}</td>
                                                            <td class="fw-bold text-success">
                                                                @if($coupon->type == 'fixed')
                                                                    {{ number_format($coupon->value, 0, ',', '.') }}đ
                                                                @else
                                                                    {{ $coupon->value }}%
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($coupon->expires_at)
                                                                    @if($coupon->expires_at->isPast())
                                                                        <span class="text-danger">Hết hạn</span>
                                                                    @else
                                                                        {{ $coupon->expires_at->format('d/m/Y') }}
                                                                    @endif
                                                                @else
                                                                    <span class="text-success">Không giới hạn</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('admin.coupons.show', $coupon) }}" 
                                                                   class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                                                   class="btn btn-sm btn-outline-warning">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-end mt-3">
                                            <a href="{{ route('admin.coupons.create') }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus me-2"></i>Thêm mã giảm giá
                                            </a>
                                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-list me-2"></i>Xem tất cả
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Chưa có mã giảm giá nào</p>
                                            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Thêm mã giảm giá đầu tiên
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sales Chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'],
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: [1200000, 1900000, 800000, 2100000, 1600000, 2400000, 1800000],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2.5,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN', {
                                    style: 'currency',
                                    currency: 'VND'
                                }).format(value);
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                elements: {
                    point: {
                        hoverRadius: 8
                    }
                },
                layout: {
                    padding: {
                        top: 10,
                        bottom: 10
                    }
                }
            }
        });

        // Resize chart on window resize
        window.addEventListener('resize', function() {
            salesChart.resize();
        });
    </script>
</body>
</html>