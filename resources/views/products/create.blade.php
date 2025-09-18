@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-headline mb-1">
                <i class="fas fa-plus-circle text-success me-2"></i>Thêm sản phẩm mới
            </h2>
            <p class="text-secondary mb-0">Thêm sản phẩm nước mới vào hệ thống</p>
        </div>
        <div>
            <a class="btn btn-outline-secondary" href="{{ route('products.index') }}">
                <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Có lỗi xảy ra!</strong> Vui lòng kiểm tra lại thông tin.
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        
        <!-- Basic Information Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Thông tin cơ bản
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label required">Tên sản phẩm</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" name="name" class="form-control" 
                                   placeholder="Nhập tên sản phẩm" 
                                   value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Danh mục</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                            <select name="category_id" class="form-select" required>
                                <option value="">Chọn danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Mô tả sản phẩm</label>
                        <textarea class="form-control" name="description" rows="4" 
                                  placeholder="Nhập mô tả chi tiết về sản phẩm...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Inventory Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-dollar-sign me-2"></i>Giá cả & Kho hàng
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label required">Giá bán (VNĐ)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                            <input type="number" name="price" class="form-control" 
                                   placeholder="Nhập giá sản phẩm" 
                                   value="{{ old('price') }}" 
                                   min="1000" max="99999999.99" step="500" required>
                            <span class="input-group-text">đ</span>
                        </div>
                        <div class="form-text">Giá tối thiểu: 1,000đ</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Số lượng tồn kho</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                            <input type="number" name="quantity" class="form-control" 
                                   placeholder="Nhập số lượng" 
                                   value="{{ old('quantity', 0) }}" min="0" required>
                            <span class="input-group-text">sản phẩm</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>Chi tiết sản phẩm
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Thương hiệu</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-trademark"></i></span>
                            <input type="text" name="brand" class="form-control" 
                                   placeholder="Nhập thương hiệu" 
                                   value="{{ old('brand') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hương vị</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-leaf"></i></span>
                            <input type="text" name="flavor" class="form-control" 
                                   placeholder="Nhập hương vị" 
                                   value="{{ old('flavor') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Dung tích</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-flask"></i></span>
                            <input type="text" name="volume" class="form-control" 
                                   placeholder="Ví dụ: 500ml, 1.5L" 
                                   value="{{ old('volume') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Loại bao bì</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-box"></i></span>
                            <select name="packaging_type" class="form-select">
                                <option value="">Chọn loại bao bì</option>
                                <option value="Chai" {{ old('packaging_type') == 'Chai' ? 'selected' : '' }}>Chai</option>
                                <option value="Lon" {{ old('packaging_type') == 'Lon' ? 'selected' : '' }}>Lon</option>
                                <option value="Hộp" {{ old('packaging_type') == 'Hộp' ? 'selected' : '' }}>Hộp</option>
                                <option value="Túi" {{ old('packaging_type') == 'Túi' ? 'selected' : '' }}>Túi</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ngày hết hạn</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" name="expiry_date" class="form-control" 
                                   value="{{ old('expiry_date') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Xuất xứ</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-globe"></i></span>
                            <input type="text" name="origin_country" class="form-control" 
                                   placeholder="Nhập xuất xứ" 
                                   value="{{ old('origin_country') }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Thành phần</label>
                        <textarea class="form-control" name="ingredients" rows="3" 
                                  placeholder="Nhập thành phần sản phẩm...">{{ old('ingredients') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image & Settings Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-image me-2"></i>Hình ảnh & Cài đặt
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Hình ảnh sản phẩm</label>
                        <div class="input-group">
                            <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                            <label class="input-group-text" for="imageInput">
                                <i class="fas fa-upload"></i>
                            </label>
                        </div>
                        <div class="form-text">Chấp nhận: JPG, PNG, GIF. Tối đa 2MB</div>
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tùy chọn sản phẩm</label>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="is_carbonated" class="form-check-input" 
                                   value="1" {{ old('is_carbonated') ? 'checked' : '' }} id="carbonated">
                            <label class="form-check-label" for="carbonated">
                                <i class="fas fa-tint me-1"></i>Có gas
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" 
                                   value="1" {{ old('is_active', true) ? 'checked' : '' }} id="active">
                            <label class="form-check-label" for="active">
                                <i class="fas fa-check-circle me-1"></i>Kích hoạt sản phẩm
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Hủy bỏ
                    </a>
                    <div>
                        <button type="button" class="btn btn-outline-primary me-2" onclick="previewProduct()">
                            <i class="fas fa-eye me-2"></i>Xem trước
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Thêm sản phẩm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.required::after {
    content: " *";
    color: red;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
</style>

<script>
// Image preview functionality
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// Preview product function
function previewProduct() {
    const formData = new FormData(document.getElementById('productForm'));
    // Implement preview functionality
    alert('Chức năng xem trước sẽ được phát triển trong phiên bản tiếp theo');
}

// Form validation
document.getElementById('productForm').addEventListener('submit', function(e) {
    const requiredFields = ['name', 'category_id', 'price', 'quantity'];
    let isValid = true;
    
    requiredFields.forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
    }
});
</script>
@endsection