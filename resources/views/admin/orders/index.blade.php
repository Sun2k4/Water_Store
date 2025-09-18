@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-shopping-cart text-primary me-2"></i>
                        Quản lý đơn hàng
                    </h1>
                    <p class="text-muted mb-0">Theo dõi và quản lý tất cả đơn hàng trong hệ thống</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="/admin/dashboard" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại Dashboard
                    </a>
                    <button class="btn btn-outline-primary" onclick="location.reload()">
                        <i class="fas fa-sync-alt me-1"></i> Làm mới
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng đơn hàng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $allOrders->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Đang xử lý
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $allOrders->where('status', 'đang xử lý')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Đang giao hàng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $allOrders->where('status', 'đang giao hàng')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Đã giao hàng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $allOrders->where('status', 'đã giao hàng')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Danh sách đơn hàng
            </h6>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" style="width: auto;" onchange="filterByStatus(this.value)">
                    <option value="">Tất cả trạng thái</option>
                    <option value="đang xử lý">Đang xử lý</option>
                    <option value="đang giao hàng">Đang giao hàng</option>
                    <option value="đã giao hàng">Đã giao hàng</option>
                    <option value="đã hủy">Đã hủy</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="ordersTable">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">
                                <i class="fas fa-hashtag me-1"></i>Mã đơn
                            </th>
                            <th class="border-0">
                                <i class="fas fa-user me-1"></i>Khách hàng
                            </th>
                            <th class="border-0">
                                <i class="fas fa-calendar me-1"></i>Ngày đặt
                            </th>
                            <th class="border-0">
                                <i class="fas fa-money-bill me-1"></i>Tổng tiền
                            </th>
                            <th class="border-0">
                                <i class="fas fa-info-circle me-1"></i>Trạng thái
                            </th>
                            <th class="border-0">
                                <i class="fas fa-cog me-1"></i>Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="order-row" data-status="{{ $order->status }}">
                            <td>
                                <span class="fw-bold text-primary">#{{ $order->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $order->name }}</div>
                                        <small class="text-muted">{{ $order->phone }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ $order->created_at ? $order->created_at->format('d/m/Y') : '' }}</div>
                                    <small class="text-muted">{{ $order->created_at ? $order->created_at->format('H:i') : '' }}</small>
                                </div>
                            </td>
                            <td>
                                @if($order->coupon_code)
                                    <div>
                                        <del class="text-muted">{{ number_format($order->total_price) }} VNĐ</del>
                                        <span class="badge bg-info">{{ $order->coupon_code }}</span>
                                    </div>
                                    <span class="fw-bold text-success">{{ number_format($order->final_price) }} VNĐ</span>
                                @else
                                    <span class="fw-bold text-success">{{ number_format($order->total_price) }} VNĐ</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge fs-6 px-3 py-2
                                    @if($order->status == 'đang xử lý') text-dark" style="background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%); box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
                                    @elseif($order->status == 'đã xác nhận') text-white" style="background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%); box-shadow: 0 2px 8px rgba(111, 66, 193, 0.3);
                                    @elseif($order->status == 'đang giao hàng') text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);
                                    @elseif($order->status == 'đã giao hàng') text-white" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
                                    @elseif($order->status == 'đã hủy') text-white" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
                                    @endif
                                ">
                                    @if($order->status == 'đang xử lý')
                                        <i class="fas fa-clock me-1"></i>
                                    @elseif($order->status == 'đã xác nhận')
                                        <i class="fas fa-check me-1"></i>
                                    @elseif($order->status == 'đang giao hàng')
                                        <i class="fas fa-truck me-1"></i>
                                    @elseif($order->status == 'đã giao hàng')
                                        <i class="fas fa-check-circle me-1"></i>
                                    @elseif($order->status == 'đã hủy')
                                        <i class="fas fa-times-circle me-1"></i>
                                    @endif
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm" style="width: 140px;" onchange="this.form.submit()">
                                            <option value="đang xử lý" {{ $order->status == 'đang xử lý' ? 'selected' : '' }}>Đang xử lý</option>
                                            <option value="đã xác nhận" {{ $order->status == 'đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                                            <option value="đang giao hàng" {{ $order->status == 'đang giao hàng' ? 'selected' : '' }}>Đang giao hàng</option>
                                            <option value="đã giao hàng" {{ $order->status == 'đã giao hàng' ? 'selected' : '' }}>Đã giao hàng</option>
                                            <option value="đã hủy" {{ $order->status == 'đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                                        </select>
                                    </form>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p class="mb-0">Chưa có đơn hàng nào</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($orders->hasPages())
            <div class="mt-4">
                <x-advanced-pagination :paginator="$orders" :perPageOptions="$perPageOptions" />
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 14px;
}
.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
.table th {
    font-weight: 600;
    color: #5a5c69;
    border-top: none;
}
.order-row:hover {
    background-color: #f8f9fc;
    transition: all 0.2s ease;
}

</style>

<script>
function filterByStatus(status) {
    const rows = document.querySelectorAll('.order-row');
    rows.forEach(row => {
        if (status === '' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endsection