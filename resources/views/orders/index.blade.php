@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Lịch sử đơn hàng</h2>
    
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
    
    <div class="card">
        <div class="card-body">
            @if($orders->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái đơn hàng</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</td>
                        <td>
                            @if($order->coupon_code)
                                <del class="text-muted">{{ number_format($order->total_price) }} VNĐ</del><br>
                                <span class="text-success">{{ number_format($order->final_price) }} VNĐ</span>
                                <span class="badge bg-info">Giảm giá</span>
                            @else
                                {{ number_format($order->total_price) }} VNĐ
                            @endif
                        </td>
                        <td>
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
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">Chi tiết</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p>Bạn chưa có đơn hàng nào.</p>
            @endif
        </div>
    </div>
</div>
@endsection