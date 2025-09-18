@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Thêm mã giảm giá mới</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('admin.coupons.index') }}">Quay lại</a>
                <a class="btn btn-secondary" href="{{ route('admin.dashboard') }}">Quay lại Dashboard</a>
            </div>
        </div>
    </div>

    <div class="mt-3">
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Lỗi!</strong> Vui lòng kiểm tra lại thông tin.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Mã giảm giá:</strong>
                    <input type="text" name="code" class="form-control" placeholder="Nhập mã giảm giá" value="{{ old('code') }}" required>
                    <small class="form-text text-muted">Mã giảm giá sẽ được chuyển thành chữ hoa tự động.</small>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Loại giảm giá:</strong>
                    <select name="type" id="type" class="form-control" required>
                        <option value="">-- Chọn loại giảm giá --</option>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                        <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Phần trăm</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Giá trị:</strong>
                    <input type="number" name="value" id="value" class="form-control" placeholder="Nhập giá trị" value="{{ old('value') }}" min="0" step="0.01" required>
                    <small class="form-text text-muted" id="value-help">Nhập số tiền giảm giá (VNĐ) hoặc phần trăm giảm giá (%).</small>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Giá trị đơn hàng tối thiểu:</strong>
                    <input type="number" name="min_order_amount" class="form-control" placeholder="Nhập giá trị đơn hàng tối thiểu" value="{{ old('min_order_amount', 0) }}" min="0" required>
                    <small class="form-text text-muted">Đơn hàng phải có giá trị tối thiểu này mới được áp dụng mã giảm giá (VNĐ).</small>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Giới hạn sử dụng:</strong>
                    <input type="number" name="usage_limit" class="form-control" placeholder="Nhập giới hạn sử dụng" value="{{ old('usage_limit', 1) }}" min="1" required>
                    <small class="form-text text-muted">Số lần mã giảm giá có thể được sử dụng.</small>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Ngày hết hạn:</strong>
                    <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}" required>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-3">
                <button type="submit" class="btn btn-success">Tạo mã giảm giá</button>
            </div>
        </div>
    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const valueHelp = document.getElementById('value-help');
        
        function updateValueHelp() {
            if (typeSelect.value === 'fixed') {
                valueHelp.textContent = 'Nhập số tiền giảm giá (VNĐ).';
            } else if (typeSelect.value === 'percent') {
                valueHelp.textContent = 'Nhập phần trăm giảm giá (%). Giá trị từ 0-100.';
            } else {
                valueHelp.textContent = 'Nhập số tiền giảm giá (VNĐ) hoặc phần trăm giảm giá (%)';
            }
        }
        
        typeSelect.addEventListener('change', updateValueHelp);
        updateValueHelp();
    });
</script>
@endpush
@endsection