@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Quản lý mã giảm giá</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('admin.coupons.create') }}">
                    <i class="fas fa-plus"></i> Thêm mã giảm giá mới
                </a>
                <a class="btn btn-primary" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-arrow-left"></i> Quay lại Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="mt-3">
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Mã giảm giá</th>
                <th>Loại</th>
                <th>Giá trị</th>
                <th>Đơn hàng tối thiểu</th>
                <th>Hạn sử dụng</th>
                <th width="280px">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($coupons as $coupon)
            <tr>
                <td>#{{ $coupon->id }}</td>
                <td>{{ $coupon->code }}</td>
                <td>
                    @if ($coupon->type == 'fixed')
                        <span class="badge bg-info">Cố định</span>
                    @else
                        <span class="badge bg-warning">Phần trăm</span>
                    @endif
                </td>
                <td>
                    @if ($coupon->type == 'fixed')
                        {{ number_format($coupon->value) }} VNĐ
                    @else
                        {{ $coupon->value }}%
                    @endif
                </td>
                <td>{{ number_format($coupon->min_order_amount) }} VNĐ</td>
                <td>
                    @if (\Carbon\Carbon::now()->gt($coupon->expires_at))
                        <span class="text-danger">{{ $coupon->expires_at->format('d/m/Y') }}</span>
                    @else
                        <span class="text-success">{{ $coupon->expires_at->format('d/m/Y') }}</span>
                    @endif
                </td>
                <td>
                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST">
                        <a class="btn btn-info" href="{{ route('admin.coupons.show', $coupon->id) }}">Xem</a>
                        <a class="btn btn-primary" href="{{ route('admin.coupons.edit', $coupon->id) }}">Sửa</a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Không có mã giảm giá nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($coupons->hasPages())
        <div class="mt-4">
            {{ $coupons->links() }}
        </div>
    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection