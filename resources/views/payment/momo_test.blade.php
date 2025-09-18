@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Thanh toán MoMo Test</h2>
    
    <div class="card">
        <div class="card-header">Thông tin thanh toán</div>
        <div class="card-body">
            <p>Đơn hàng: #{{ $order->id }}</p>
            <p>Tổng tiền: {{ number_format($order->total_price) }} VNĐ</p>
            <input type="hidden" name="amount" value="{{ $order->total_price }}">
            
            <div class="alert alert-info">
                <p>Đây là trang mô phỏng thanh toán MoMo. Trong môi trường thực tế, bạn sẽ được chuyển hướng đến trang thanh toán của MoMo.</p>
                <p>Thông tin thẻ test:</p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tên</th>
                            <th>Số thẻ</th>
                            <th>Hạn ghi trên thẻ</th>
                            <th>OTP</th>
                            <th>Trường hợp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>NGUYEN VAN A</td>
                            <td>9704 0000 0000 0018</td>
                            <td>03/07</td>
                            <td>OTP</td>
                            <td>Thành công</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>NGUYEN VAN A</td>
                            <td>9704 0000 0000 0026</td>
                            <td>03/07</td>
                            <td>OTP</td>
                            <td>Thẻ khóa</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>NGUYEN VAN A</td>
                            <td>9704 0000 0000 0034</td>
                            <td>03/07</td>
                            <td>OTP</td>
                            <td>Không đủ tiền</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>NGUYEN VAN A</td>
                            <td>9704 0000 0000 0042</td>
                            <td>03/07</td>
                            <td>OTP</td>
                            <td>Hạn mức thẻ</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <form action="{{ route('payment.momo.callback') }}" method="POST">
                @csrf
                <input type="hidden" name="orderId" value="{{ $order->id }}">
                <input type="hidden" name="requestId" value="{{ uniqid() }}">
                <input type="hidden" name="amount" value="{{ $order->total_price }}">
                <input type="hidden" name="orderInfo" value="Thanh toán đơn hàng #{{ $order->id }}">
                
                <div class="form-group mb-3">
                    <label for="resultCode">Kết quả thanh toán:</label>
                    <select class="form-control" id="resultCode" name="resultCode">
                        <option value="0">Thành công (0)</option>
                        <option value="1">Thất bại (1)</option>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="transId">Mã giao dịch:</label>
                    <input type="text" class="form-control" id="transId" name="transId" value="{{ 'MOMO'.time() }}">
                </div>
                
                <button type="submit" class="btn btn-primary">Hoàn tất thanh toán</button>
            </form>
        </div>
    </div>
</div>
@endsection