@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Thanh toán</h2>
    
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">Mã giảm giá</div>
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
            
            <div class="card">
                <div class="card-header">Thông tin đơn hàng</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carts as $cart)
                            <tr>
                                <td>{{ $cart->product->name }}</td>
                                <td>{{ $cart->quantity }}</td>
                                <td>{{ number_format($cart->price) }} VNĐ</td>
                                <td>{{ number_format($cart->quantity * $cart->price) }} VNĐ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Tạm tính:</strong></td>
                                <td><strong>{{ number_format($total) }} VNĐ</strong></td>
                            </tr>
                            @if(session('coupon'))
                                <tr>
                                    <td colspan="3" class="text-right text-success"><strong>Giảm giá:</strong></td>
                                    <td><strong class="text-success">-{{ number_format($discountAmount) }} VNĐ</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Thành tiền:</strong></td>
                                    <td><strong>{{ number_format($finalTotal) }} VNĐ</strong></td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                                    <td><strong>{{ number_format($total) }} VNĐ</strong></td>
                                </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Phương thức thanh toán</div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5>Chọn phương thức thanh toán:</h5>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                            <label class="form-check-label" for="cod">
                                Thanh toán khi nhận hàng (COD)
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="momo" value="momo">
                            <label class="form-check-label" for="momo">
                                Thanh toán qua MoMo
                            </label>
                        </div>
                    </div>
                    
                    <form id="cod-form" action="{{ route('payment.process.cod') }}" method="POST">
                        @csrf
                        @if(session('coupon'))
                            <input type="hidden" name="coupon_code" value="{{ session('coupon.code') }}">
                            <input type="hidden" name="discount_amount" value="{{ $discountAmount }}">
                        @endif
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control mb-2" placeholder="Họ tên" value="{{ old('name', Auth::user()->name) }}" required>
                            <input type="text" name="address" class="form-control mb-2" placeholder="Địa chỉ" value="{{ old('address') }}" required>
                            <input type="text" name="phone" class="form-control mb-2" placeholder="Số điện thoại" value="{{ old('phone') }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Đặt hàng</button>
                    </form>
                    
                    <form id="momo-form" action="{{ route('payment.process.momo') }}" method="POST" style="display: none;">
                        @csrf
                        @if(session('coupon'))
                            <input type="hidden" name="coupon_code" value="{{ session('coupon.code') }}">
                            <input type="hidden" name="discount_amount" value="{{ $discountAmount }}">
                        @endif
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control mb-2" placeholder="Họ tên" value="{{ old('name', Auth::user()->name) }}" required>
                            <input type="text" name="address" class="form-control mb-2" placeholder="Địa chỉ" value="{{ old('address') }}" required>
                            <input type="text" name="phone" class="form-control mb-2" placeholder="Số điện thoại" value="{{ old('phone') }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Thanh toán qua MoMo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const codRadio = document.getElementById('cod');
        const momoRadio = document.getElementById('momo');
        const codForm = document.getElementById('cod-form');
        const momoForm = document.getElementById('momo-form');
        
        // Xử lý chuyển đổi phương thức thanh toán
        codRadio.addEventListener('change', function() {
            if (this.checked) {
                codForm.style.display = 'block';
                momoForm.style.display = 'none';
            }
        });
        
        momoRadio.addEventListener('change', function() {
            if (this.checked) {
                codForm.style.display = 'none';
                momoForm.style.display = 'block';
            }
        });
    });
</script>
@endsection