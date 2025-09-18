
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Chi tiết đơn hàng #{{ $order->id }}</h2>
    
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
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
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
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price) }} VNĐ</td>
                                <td>{{ number_format($item->quantity * $item->price) }} VNĐ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tổng tiền hàng:</strong></td>
                                <td><strong>{{ number_format($order->total_price) }} VNĐ</strong></td>
                            </tr>
                            @if($order->coupon_code)
                            <tr>
                                <td colspan="3" class="text-end"><strong>Mã giảm giá:</strong></td>
                                <td><strong>{{ $order->coupon_code }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Giảm giá:</strong></td>
                                <td><strong>-{{ number_format($order->discount_amount) }} VNĐ</strong></td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="3" class="text-end"><strong>Thành tiền:</strong></td>
                                <td><strong>{{ number_format($order->final_price) }} VNĐ</strong></td>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Thông tin thanh toán</div>
                <div class="card-body">
                    <p><strong>Mã đơn hàng:</strong> #{{ $order->id }}</p>
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</p>
                    <p><strong>Họ tên:</strong> {{ $order->name }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                    <p>
                        <strong>Trạng thái đơn hàng:</strong> 
                        @if($order->status == 'đang xử lý')
                            <span class="badge bg-info">Đang xử lý</span>
                        @elseif($order->status == 'đang giao hàng')
                            <span class="badge bg-primary">Đang giao hàng</span>
                        @elseif($order->status == 'đã giao hàng')
                            <span class="badge bg-success">Đã giao hàng</span>
                        @elseif($order->status == 'đã hủy')
                            <span class="badge bg-danger">Đã hủy</span>
                        @else
                            <span class="badge bg-secondary">{{ $order->status }}</span>
                        @endif
                    </p>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection