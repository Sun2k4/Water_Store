@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Giỏ hàng của bạn</h2>
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    
    @if($carts->count() > 0)
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($carts as $cart)
                    <tr>
                        <td>{{ $cart->product->name }}</td>
                        <td>{{ number_format($cart->price) }} VNĐ</td>
                        <td>
                            <form action="{{ route('cart.update', $cart) }}" method="POST" class="d-flex align-items-center">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" class="form-control" style="width: 70px;">
                                <button type="submit" class="btn btn-sm btn-outline-primary ms-2">Cập nhật</button>
                            </form>
                        </td>
                        <td>{{ number_format($cart->quantity * $cart->price) }} VNĐ</td>
                        <td>
                            <form action="{{ route('cart.remove', $cart) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                        <td><strong>{{ number_format($total) }} VNĐ</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            
            <!-- Mã giảm giá -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Mã giảm giá</h5>
                        </div>
                        <div class="card-body">
                            @if(session('coupon'))
                                <div class="alert alert-success">
                                    <strong>Mã giảm giá đã áp dụng:</strong> {{ session('coupon.code') }}<br>
                                    <strong>Giảm giá:</strong> 
                                    @if(session('coupon.type') == 'fixed')
                                        {{ number_format(session('coupon.value')) }} VNĐ
                                    @else
                                        {{ session('coupon.value') }}%
                                    @endif
                                </div>
                                <form action="{{ route('cart.remove-coupon') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Hủy mã giảm giá</button>
                                </form>
                            @else
                                <form action="{{ route('cart.apply-coupon') }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="coupon_code" class="form-control" placeholder="Nhập mã giảm giá" required>
                                        <button type="submit" class="btn btn-success">Áp dụng</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Tổng kết đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($total) }} VNĐ</span>
                            </div>
                            @if(session('coupon'))
                                <div class="d-flex justify-content-between text-success">
                                    <span>Giảm giá:</span>
                                    <span>-{{ number_format(session('discount_amount', 0)) }} VNĐ</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Tổng cộng:</strong>
                                    <strong>{{ number_format($total - session('discount_amount', 0)) }} VNĐ</strong>
                                </div>
                            @else
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Tổng cộng:</strong>
                                    <strong>{{ number_format($total) }} VNĐ</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-end mt-3">
                <a href="{{ route('payment.index') }}" class="btn btn-primary">Tiến hành thanh toán</a>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        Giỏ hàng của bạn đang trống. <a href="{{ route('products.index') }}">Tiếp tục mua sắm</a>
    </div>
    @endif
</div>
@endsection