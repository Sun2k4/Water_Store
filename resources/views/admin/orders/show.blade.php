@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-receipt text-primary me-2"></i>
                        Chi tiết đơn hàng #{{ $order->id }}
                    </h1>
                    <p class="text-muted mb-0">Thông tin chi tiết và quản lý đơn hàng</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <a href="/admin/dashboard" class="btn btn-outline-info">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> In đơn hàng
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Order Information -->
        <div class="col-lg-8">
            <!-- Order Items -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shopping-bag me-2"></i>Sản phẩm đã đặt
                    </h6>
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        {{ $order->items->count() }} sản phẩm
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 py-3 px-4">Sản phẩm</th>
                                    <th class="border-0 py-3 px-4 text-center">Số lượng</th>
                                    <th class="border-0 py-3 px-4 text-end">Đơn giá</th>
                                    <th class="border-0 py-3 px-4 text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="product-image me-3">
                                                @if($item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="rounded" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $item->product->name }}</div>
                                                <small class="text-muted">{{ $item->product->category->name ?? 'Không có danh mục' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="badge bg-secondary fs-6 px-3 py-2">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-end">
                                        <span class="fw-semibold">{{ number_format($item->price) }} VNĐ</span>
                                    </td>
                                    <td class="py-3 px-4 text-end">
                                        <span class="fw-bold text-success">{{ number_format($item->quantity * $item->price) }} VNĐ</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="py-3 px-4 text-end">
                                        <span class="h5 mb-0 text-gray-800">Tổng tiền hàng:</span>
                                    </td>
                                    <td class="py-3 px-4 text-end">
                                        <span class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($order->total_price) }} VNĐ</span>
                                    </td>
                                </tr>
                                @if($order->coupon_code)
                                <tr>
                                    <td colspan="3" class="py-3 px-4 text-end">
                                        <span class="h5 mb-0 text-gray-800">Mã giảm giá:</span>
                                    </td>
                                    <td class="py-3 px-4 text-end">
                                        <span class="h5 mb-0 font-weight-bold text-info">{{ $order->coupon_code }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="py-3 px-4 text-end">
                                        <span class="h5 mb-0 text-gray-800">Giảm giá:</span>
                                    </td>
                                    <td class="py-3 px-4 text-end">
                                        <span class="h5 mb-0 font-weight-bold text-danger">-{{ number_format($order->discount_amount) }} VNĐ</span>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="py-3 px-4 text-end">
                                        <span class="h5 mb-0 text-gray-800">Thành tiền:</span>
                                    </td>
                                    <td class="py-3 px-4 text-end">
                                        <span class="h4 mb-0 font-weight-bold text-primary">{{ number_format($order->final_price ?? $order->total_price) }} VNĐ</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details & Actions -->
        <div class="col-lg-4">
            <!-- Order Status -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Trạng thái đơn hàng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <span class="badge fs-5 px-4 py-3
                            @if($order->status == 'đang xử lý') bg-warning text-dark
                            @elseif($order->status == 'đang giao hàng') bg-info
                            @elseif($order->status == 'đã giao hàng') bg-success
                            @elseif($order->status == 'đã hủy') bg-danger
                            @endif
                        ">
                            @if($order->status == 'đang xử lý')
                                <i class="fas fa-clock me-2"></i>
                            @elseif($order->status == 'đang giao hàng')
                                <i class="fas fa-truck me-2"></i>
                            @elseif($order->status == 'đã giao hàng')
                                <i class="fas fa-check-circle me-2"></i>
                            @elseif($order->status == 'đã hủy')
                                <i class="fas fa-times-circle me-2"></i>
                            @endif
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Cập nhật trạng thái:</label>
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="đang xử lý" {{ $order->status == 'đang xử lý' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="đang giao hàng" {{ $order->status == 'đang giao hàng' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="đã giao hàng" {{ $order->status == 'đã giao hàng' ? 'selected' : '' }}>Đã giao hàng</option>
                                <option value="đã hủy" {{ $order->status == 'đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>Thông tin khách hàng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-user fa-lg"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-5">{{ $order->name }}</div>
                            <small class="text-muted">Khách hàng</small>
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone text-primary me-2"></i>
                                <span>{{ $order->phone }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-map-marker-alt text-primary me-2 mt-1"></i>
                                <span>{{ $order->address }}</span>
                            </div>
                        </div>
                        @if($order->user)
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <span>{{ $order->user->email }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-receipt me-2"></i>Tóm tắt đơn hàng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Mã đơn hàng:</span>
                        <span class="fw-semibold">#{{ $order->id }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Ngày đặt:</span>
                        <span class="fw-semibold">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Số sản phẩm:</span>
                        <span class="fw-semibold">{{ $order->items->count() }}</span>
                    </div>
                    @if($order->payment_method)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Phương thức thanh toán:</span>
                        <span class="fw-semibold">
                            @if($order->payment_method == 'cod')
                                <i class="fas fa-money-bill-wave me-1"></i>COD
                            @elseif($order->payment_method == 'momo_atm')
                                <i class="fas fa-credit-card me-1"></i>MoMo ATM
                            @else
                                {{ ucfirst($order->payment_method) }}
                            @endif
                        </span>
                    </div>
                    @endif
                    @if($order->payment_status)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Trạng thái thanh toán:</span>
                        <span class="badge 
                            @if($order->payment_status == 'completed') bg-success
                            @elseif($order->payment_status == 'pending') bg-warning text-dark
                            @elseif($order->payment_status == 'failed') bg-danger
                            @else bg-secondary
                            @endif
                        ">
                            @if($order->payment_status == 'completed')
                                <i class="fas fa-check me-1"></i>Hoàn thành
                            @elseif($order->payment_status == 'pending')
                                <i class="fas fa-clock me-1"></i>Chờ thanh toán
                            @elseif($order->payment_status == 'failed')
                                <i class="fas fa-times me-1"></i>Thất bại
                            @else
                                {{ ucfirst($order->payment_status) }}
                            @endif
                        </span>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0 text-gray-800">Tổng tiền:</span>
                        <span class="h4 mb-0 font-weight-bold text-primary">{{ number_format($order->total_price) }} VNĐ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 60px;
    height: 60px;
    font-size: 20px;
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
.product-image img {
    border: 1px solid #e3e6f0;
}
@media print {
    .btn, .card-header .d-flex:last-child {
        display: none !important;
    }
    .card {
        box-shadow: none !important;
        border: 1px solid #e3e6f0 !important;
    }
}
</style>
@endsection
