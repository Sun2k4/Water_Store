@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-headline mb-1">
                <i class="fas fa-box text-highlight me-2"></i>Quản lý sản phẩm
            </h2>
            <p class="text-secondary mb-0">Quản lý tất cả sản phẩm nước trong hệ thống</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-arrow-left me-2"></i>Quay lại Dashboard
            </a>
            <a class="btn btn-primary" href="{{ route('products.create') }}">
                <i class="fas fa-plus me-2"></i>Thêm sản phẩm mới
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tìm kiếm sản phẩm</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Nhập tên sản phẩm..." id="searchInput">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Danh mục</label>
                    <select class="form-select" id="categoryFilter">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sắp xếp theo</label>
                    <select class="form-select" id="sortBy">
                        <option value="created_at">Ngày tạo mới nhất</option>
                        <option value="name">Tên A-Z</option>
                        <option value="price_asc">Giá thấp đến cao</option>
                        <option value="price_desc">Giá cao đến thấp</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-outline-primary d-block w-100" onclick="applyFilters()">
                        <i class="fas fa-filter me-2"></i>Lọc
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Danh sách sản phẩm
                <span class="badge bg-primary ms-2">{{ $products->total() }} sản phẩm</span>
            </h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-success" onclick="exportProducts()">
                    <i class="fas fa-download me-1"></i>Xuất Excel
                </button>
                <button class="btn btn-sm btn-outline-info" onclick="bulkActions()">
                    <i class="fas fa-tasks me-1"></i>Thao tác hàng loạt
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th width="80">Hình ảnh</th>
                            <th>Mã SP</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá bán</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th width="200">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input product-checkbox" value="{{ $product->id }}">
                            </td>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="rounded"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">#{{ $product->id }}</span>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->description)
                                        <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($product->category)
                                    <span class="badge bg-primary">{{ $product->category->name }}</span>
                                @else
                                    <span class="badge bg-secondary">Chưa phân loại</span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-success">{{ number_format($product->price, 0, ',', '.') }}đ</strong>
                            </td>
                            <td>
                                <span class="badge bg-success">Đang bán</span>
                            </td>
                            <td>
                                <small>{{ $product->created_at ? $product->created_at->format('d/m/Y') : '' }}</small>
                                <br><small class="text-muted">{{ $product->created_at ? $product->created_at->format('H:i') : '' }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('products.show', $product->id) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product->id) }}" 
                                       class="btn btn-sm btn-outline-warning"
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteProduct({{ $product->id }})"
                                            title="Xóa sản phẩm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có sản phẩm nào</h5>
                                <p class="text-muted">Hãy thêm sản phẩm đầu tiên để bắt đầu bán hàng</p>
                                <a href="{{ route('products.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Thêm sản phẩm đầu tiên
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Hiển thị {{ $products->firstItem() }} đến {{ $products->lastItem() }} 
                trong tổng số {{ $products->total() }} sản phẩm
            </div>
            <x-custom-pagination :paginator="$products" />
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa sản phẩm này không?</p>
                <p class="text-danger"><small>Hành động này không thể hoàn tác!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa sản phẩm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteProduct(productId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/products/${productId}`;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

function applyFilters() {
    // Implement filter functionality
    console.log('Applying filters...');
}

function exportProducts() {
    // Implement export functionality
    console.log('Exporting products...');
}

function bulkActions() {
    // Implement bulk actions
    console.log('Bulk actions...');
}

// Select all checkbox functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});
</script>
@endsection