@extends('layouts.app')

@section('title', 'Báo cáo và Thống kê')

@section('content')
<style>
    .chart-wrap {
        min-height: 360px;
        width: 100% !important;
        height: 360px !important;
    }
    .admin-nav {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 0;
        margin-bottom: 2rem;
    }
</style>

<!-- Admin Navigation -->
<div class="admin-nav">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Admin Panel</h4>
            <nav class="nav">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('admin.products') }}">Sản phẩm</a>
                <a class="nav-link" href="{{ route('admin.categories') }}">Danh mục</a>
                <a class="nav-link" href="{{ route('admin.users') }}">Người dùng</a>
                <a class="nav-link" href="{{ route('admin.orders.index') }}">Đơn hàng</a>
                <a class="nav-link active" href="{{ route('admin.reports') }}">Báo cáo</a>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Báo cáo doanh thu</h2>
        <a class="btn btn-outline-secondary" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-arrow-left me-2"></i>Quay lại Dashboard
        </a>
    </div>
    

    <div class="row">
        {{-- Doanh thu theo Danh mục --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Doanh thu theo danh mục</div>
                <div class="card-body chart-wrap">
                    <canvas id="categoryRevenueChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Doanh thu 30 ngày --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Doanh thu theo ngày (30 ngày)</div>
                <div class="card-body chart-wrap">
                    <canvas id="revenueByDateChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Doanh thu 12 tháng --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Doanh thu theo tháng (12 tháng)</div>
                <div class="card-body chart-wrap">
                    <canvas id="revenueByMonthChart"></canvas>
                </div>
            </div>
        </div>
        
        {{-- Doanh thu theo Phương thức thanh toán --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header">Tỉ lệ theo phương thức thanh toán</div>
                <div class="card-body chart-wrap">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts') {{-- Hoặc @push('scripts') tùy theo cấu trúc layout của bạn --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Lấy dữ liệu từ Controller đã được chuyển sang JSON
        const catLabels = @json($catLabels ?? []);
        const catRevenue = @json($catRevenue ?? []);
        const revDateLabels = @json($revDateLabels ?? []);
        const revDateData = @json($revDateData ?? []);
        const revMonthLabels = @json($revMonthLabels ?? []);
        const revMonthData = @json($revMonthData ?? []);
        const payLabels = @json($payLabels ?? []);
        const payRevenue = @json($payRevenue ?? []);

        const options = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        // 1. Biểu đồ Doanh thu theo Danh mục (Bar)
        if (catLabels.length > 0 && catLabels[0] !== 'Không có dữ liệu') {
            new Chart(document.getElementById('categoryRevenueChart'), {
                type: 'bar',
                data: {
                    labels: catLabels,
                    datasets: [{
                        label: 'Doanh thu (VND)',
                        data: catRevenue,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                    }]
                },
                options: options
            });
        } else {
            document.getElementById('categoryRevenueChart').innerHTML = '<div class="text-center p-4"><p class="text-muted">Chưa có dữ liệu doanh thu theo danh mục</p></div>';
        }

        // 2. Biểu đồ Doanh thu theo Ngày (Line)
        if (revDateLabels.length > 0) {
            new Chart(document.getElementById('revenueByDateChart'), {
                type: 'line',
                data: {
                    labels: revDateLabels,
                    datasets: [{
                        label: 'Doanh thu (VND)',
                        data: revDateData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: options
            });
        } else {
            document.getElementById('revenueByDateChart').innerHTML = '<div class="text-center p-4"><p class="text-muted">Chưa có dữ liệu doanh thu theo ngày</p></div>';
        }
        
        // 3. Biểu đồ Doanh thu theo Tháng (Bar)
        if (revMonthLabels.length > 0) {
            new Chart(document.getElementById('revenueByMonthChart'), {
                type: 'bar',
                data: {
                    labels: revMonthLabels,
                    datasets: [{
                        label: 'Doanh thu (VND)',
                        data: revMonthData,
                        backgroundColor: 'rgba(255, 159, 64, 0.6)'
                    }]
                },
                options: options
            });
        } else {
            document.getElementById('revenueByMonthChart').innerHTML = '<div class="text-center p-4"><p class="text-muted">Chưa có dữ liệu doanh thu theo tháng</p></div>';
        }

        // 4. Biểu đồ Phương thức thanh toán (Pie)
        if (payLabels.length > 0 && payLabels[0] !== 'Không có dữ liệu') {
            new Chart(document.getElementById('paymentMethodChart'), {
                type: 'pie',
                data: {
                    labels: payLabels,
                    datasets: [{
                        label: 'Doanh thu (VND)',
                        data: payRevenue,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        } else {
            document.getElementById('paymentMethodChart').innerHTML = '<div class="text-center p-4"><p class="text-muted">Chưa có dữ liệu phương thức thanh toán</p></div>';
        }
    });
</script>
@endsection