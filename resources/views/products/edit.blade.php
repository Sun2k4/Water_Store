@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Chỉnh sửa sản phẩm</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('products.index') }}">Quay lại</a>
            </div>
        </div>
    </div>

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

    <form action="{{ route('products.update',$product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Tên sản phẩm:</strong>
                    <input type="text" name="name" class="form-control" placeholder="Nhập tên sản phẩm" value="{{ old('name', $product->name) }}" required>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Danh mục:</strong>
                    <select name="category_id" class="form-control" required>
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Mô tả:</strong>
                    <textarea class="form-control" style="height:150px" name="description" placeholder="Nhập mô tả sản phẩm">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Số lượng:</strong>
                    <input type="number" name="quantity" class="form-control" placeholder="Nhập số lượng" value="{{ old('quantity', $product->quantity) }}" min="0" required>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Giá (VNĐ):</strong>
                    <input type="number" name="price" class="form-control" placeholder="Nhập giá sản phẩm" value="{{ old('price', $product->price) }}" min="1000" max="99999999.99" step="500" required>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Thương hiệu:</strong>
                    <input type="text" name="brand" class="form-control" placeholder="Nhập thương hiệu" value="{{ old('brand', $product->brand) }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Hương vị:</strong>
                    <input type="text" name="flavor" class="form-control" placeholder="Nhập hương vị" value="{{ old('flavor', $product->flavor) }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Dung tích:</strong>
                    <input type="text" name="volume" class="form-control" placeholder="Ví dụ: 500ml, 1.5L" value="{{ old('volume', $product->volume) }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Loại bao bì:</strong>
                    <select name="packaging_type" class="form-control">
                        <option value="">Chọn loại bao bì</option>
                        <option value="Chai" {{ old('packaging_type', $product->packaging_type) == 'Chai' ? 'selected' : '' }}>Chai</option>
                        <option value="Lon" {{ old('packaging_type', $product->packaging_type) == 'Lon' ? 'selected' : '' }}>Lon</option>
                        <option value="Hộp" {{ old('packaging_type', $product->packaging_type) == 'Hộp' ? 'selected' : '' }}>Hộp</option>
                        <option value="Túi" {{ old('packaging_type', $product->packaging_type) == 'Túi' ? 'selected' : '' }}>Túi</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Ngày hết hạn:</strong>
                    <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $product->expiry_date ? $product->expiry_date->format('Y-m-d') : '') }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Xuất xứ:</strong>
                    <input type="text" name="origin_country" class="form-control" placeholder="Nhập xuất xứ" value="{{ old('origin_country', $product->origin_country) }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Thành phần:</strong>
                    <textarea class="form-control" style="height:100px" name="ingredients" placeholder="Nhập thành phần sản phẩm">{{ old('ingredients', $product->ingredients) }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <strong>Hình ảnh hiện tại:</strong>
                    @if($product->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="100" height="100">
                        </div>
                    @endif
                    <strong>Chọn hình ảnh mới:</strong>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">Để trống nếu không muốn thay đổi hình ảnh</small>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="is_carbonated" class="form-check-input" value="1" {{ old('is_carbonated', $product->is_carbonated) ? 'checked' : '' }}>
                        <label class="form-check-label">Có gas</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label">Hoạt động</label>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
            </div>
        </div>
    </form>
</div>
@endsection