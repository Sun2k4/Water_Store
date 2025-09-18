@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Chi tiết mã giảm giá</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('admin.coupons.index') }}">Quay lại</a>
                <a class="btn btn-secondary" href="{{ route('admin.dashboard') }}">Quay lại Dashboard</a>
                <a class="btn btn-success" href="{{ route('admin.coupons.edit', $coupon->id) }}">Chỉnh sửa</a>
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Mã giảm giá:</strong>
                                {{ $coupon->code }}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Loại giảm giá:</strong>
                                @if ($coupon->type == 'fixed')
                                    <span class="badge bg-info">Cố định</span>
                                @else
                                    <span class="badge bg-warning">Phần trăm</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Giá trị:</strong>
                                @if ($coupon->type == 'fixed')
                                    {{ number_format($coupon->value) }} VNĐ
                                @else
                                    {{ $coupon->value }}%
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Giá trị đơn hàng tối thiểu:</strong>
                                {{ number_format($coupon->min_order_amount) }} VNĐ
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Giới hạn sử dụng:</strong>
                                {{ $coupon->usage_limit }}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Đã sử dụng:</strong>
                                {{ $coupon->usage_count }}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Ngày hết hạn:</strong>
                                @if (\Carbon\Carbon::now()->gt($coupon->expires_at))
                                    <span class="text-danger">{{ $coupon->expires_at->format('d/m/Y') }} (Đã hết hạn)</span>
                                @else
                                    <span class="text-success">{{ $coupon->expires_at->format('d/m/Y') }} (Còn hiệu lực)</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Trạng thái:</strong>
                                @if ($coupon->isValid())
                                    <span class="badge bg-success">Có thể sử dụng</span>
                                @else
                                    <span class="badge bg-danger">Không thể sử dụng</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection