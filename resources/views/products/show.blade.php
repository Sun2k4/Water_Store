@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Chi tiết sản phẩm</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('products.index') }}">Quay lại</a>
                <a class="btn btn-warning" href="{{ route('products.edit', $product->id) }}">Chỉnh sửa</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Thông tin cơ bản</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Tên sản phẩm:</strong></div>
                        <div class="col-sm-8">{{ $product->name }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Danh mục:</strong></div>
                        <div class="col-sm-8">{{ $product->category->name ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Thương hiệu:</strong></div>
                        <div class="col-sm-8">{{ $product->brand ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Hương vị:</strong></div>
                        <div class="col-sm-8">{{ $product->flavor ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Dung tích:</strong></div>
                        <div class="col-sm-8">{{ $product->volume ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Loại bao bì:</strong></div>
                        <div class="col-sm-8">{{ $product->packaging_type ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Xuất xứ:</strong></div>
                        <div class="col-sm-8">{{ $product->origin_country ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Thông tin kinh doanh</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Giá:</strong></div>
                        <div class="col-sm-8"><span class="text-success font-weight-bold">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Số lượng:</strong></div>
                        <div class="col-sm-8">
                            @if($product->isOutOfStock())
                                <span class="badge badge-danger">Hết hàng ({{ $product->quantity }} sản phẩm)</span>
                            @elseif($product->isLowStock())
                                <span class="badge badge-warning">Sắp hết ({{ $product->quantity }} sản phẩm)</span>
                            @else
                                <span class="badge badge-success">{{ $product->quantity }} sản phẩm</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Ngày hết hạn:</strong></div>
                        <div class="col-sm-8">{{ $product->expiry_date ? $product->expiry_date->format('d/m/Y') : 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Có gas:</strong></div>
                        <div class="col-sm-8">
                            @if($product->is_carbonated)
                                <span class="badge badge-info">Có gas</span>
                            @else
                                <span class="badge badge-secondary">Không có gas</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4"><strong>Trạng thái:</strong></div>
                        <div class="col-sm-8">
                            @if($product->is_active)
                                <span class="badge badge-success">Hoạt động</span>
                            @else
                                <span class="badge badge-danger">Không hoạt động</span>
                            @endif
                        </div>
                    </div>
                    
                    @auth
                        @if(!auth()->user()->is_admin)
                            <div class="row mt-3">
                                <div class="col-12">
                                    @if($product->is_active && $product->quantity > 0)
                                        <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <div class="input-group" style="max-width: 150px;">
                                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}" class="form-control">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary" disabled>
                                            <i class="fas fa-times"></i> Hết hàng
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Hình ảnh sản phẩm</h4>
                </div>
                <div class="card-body text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 300px;">
                    @else
                        <div class="text-muted">
                            <i class="fas fa-image fa-3x"></i>
                            <p>Không có hình ảnh</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Mô tả sản phẩm</h4>
                </div>
                <div class="card-body">
                    <p>{{ $product->description ?? 'Không có mô tả' }}</p>
                </div>
            </div>
            
            @if($product->ingredients)
            <div class="card mt-3">
                <div class="card-header">
                    <h4>Thành phần</h4>
                </div>
                <div class="card-body">
                    <p>{{ $product->ingredients }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Thông tin hệ thống</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y H:i:s') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Cập nhật lần cuối:</strong> {{ $product->updated_at->format('d/m/Y H:i:s') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection