@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="text-headline mb-2">
                        <i class="fas fa-tag text-highlight me-2"></i>{{ $category->name }}
                    </h2>
                    <p class="text-secondary mb-0">Chi tiết danh mục sản phẩm</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-secondary">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.categories') }}" class="btn btn-outline">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>

            <!-- Category Info Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-headline mb-3">Thông tin danh mục</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold text-secondary">ID:</td>
                                    <td>{{ $category->id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-secondary">Tên danh mục:</td>
                                    <td class="text-headline">{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-secondary">Số sản phẩm:</td>
                                    <td>
                                        <span class="badge badge-primary fs-6">
                                            {{ $category->products_count ?? 0 }} sản phẩm
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-secondary">Ngày tạo:</td>
                                    <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-secondary">Cập nhật lần cuối:</td>
                                    <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-headline mb-3">Thống kê</h5>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <i class="fas fa-box fa-2x text-highlight mb-2"></i>
                                            <h4 class="text-headline">{{ $category->products_count ?? 0 }}</h4>
                                            <small class="text-secondary">Sản phẩm</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <i class="fas fa-calendar fa-2x text-secondary mb-2"></i>
                                            <h4 class="text-headline">{{ $category->created_at->diffInDays(now()) }}</h4>
                                            <small class="text-secondary">Ngày tuổi</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products in Category -->
            @if($category->products_count > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-headline mb-0">
                            <i class="fas fa-box text-highlight me-2"></i>Sản phẩm trong danh mục
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Danh mục này có {{ $category->products_count }} sản phẩm. 
                            <a href="{{ route('products.index') }}?category={{ $category->id }}" class="text-decoration-none">
                                Xem tất cả sản phẩm
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-box-open fa-3x text-secondary mb-3"></i>
                        <h5 class="text-headline mb-3">Chưa có sản phẩm nào</h5>
                        <p class="text-secondary mb-4">Danh mục này chưa có sản phẩm nào được thêm vào.</p>
                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Thêm sản phẩm đầu tiên
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection