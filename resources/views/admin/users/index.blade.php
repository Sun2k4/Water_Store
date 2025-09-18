@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-users text-primary me-2"></i>
                        Quản lý người dùng
                    </h1>
                    <p class="text-muted mb-0">Quản lý thông tin và quyền hạn người dùng trong hệ thống</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="/admin/dashboard" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại Dashboard
                    </a>
                    <button class="btn btn-outline-primary" onclick="location.reload()">
                        <i class="fas fa-sync-alt me-1"></i> Làm mới
                    </button>
                    <button class="btn btn-success" onclick="exportUsers()">
                        <i class="fas fa-download me-1"></i> Xuất Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng người dùng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->total() }}</div>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                +{{ $users->where('created_at', '>=', now()->subDays(7))->count() }} tuần này
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Khách hàng
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('role', 'customer')->count() }}
                            </div>
                            <small class="text-muted">
                                {{ number_format(($users->where('role', 'customer')->count() / $users->count()) * 100, 1) }}% tổng số
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Quản trị viên
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('role', 'admin')->count() }}
                            </div>
                            <small class="text-muted">
                                {{ number_format(($users->where('role', 'admin')->count() / $users->count()) * 100, 1) }}% tổng số
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Hoạt động hôm nay
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('updated_at', '>=', now()->startOfDay())->count() }}
                            </div>
                            <small class="text-info">
                                <i class="fas fa-clock me-1"></i>
                                Cập nhật gần đây
                            </small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-search me-2"></i>Tìm kiếm và lọc
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tìm kiếm</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Tìm theo tên, email...">
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Vai trò</label>
                    <select class="form-select" id="roleFilter">
                        <option value="">Tất cả vai trò</option>
                        <option value="customer">Khách hàng</option>
                        <option value="admin">Quản trị viên</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Sắp xếp theo</label>
                    <select class="form-select" id="sortBy">
                        <option value="created_at">Ngày tạo</option>
                        <option value="name">Tên</option>
                        <option value="email">Email</option>
                        <option value="updated_at">Cập nhật gần nhất</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Thứ tự</label>
                    <select class="form-select" id="sortOrder">
                        <option value="desc">Giảm dần</option>
                        <option value="asc">Tăng dần</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Danh sách người dùng
            </h6>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">Hiển thị {{ $users->count() }} / {{ $users->total() }} người dùng</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="usersTable">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">
                                <i class="fas fa-hashtag me-1"></i>Mã
                            </th>
                            <th class="border-0">
                                <i class="fas fa-user me-1"></i>Thông tin
                            </th>
                            <th class="border-0">
                                <i class="fas fa-envelope me-1"></i>Email
                            </th>
                            <th class="border-0">
                                <i class="fas fa-shield-alt me-1"></i>Vai trò
                            </th>
                            <th class="border-0">
                                <i class="fas fa-calendar me-1"></i>Ngày tạo
                            </th>
                            <th class="border-0">
                                <i class="fas fa-clock me-1"></i>Hoạt động
                            </th>
                            @if(Auth::user()->role === 'admin')
                            <th class="border-0">
                                <i class="fas fa-cog me-1"></i>Thao tác
                            </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="user-row" data-role="{{ $user->role }}" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
                            <td>
                                <span class="fw-bold text-primary">#{{ $user->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-{{ $user->role === 'admin' ? 'warning' : 'primary' }} text-white me-3">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        @if($user->id === Auth::id())
                                            <small class="badge bg-info">Bạn</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ $user->email }}</span>
                            </td>
                            <td>
                                <span class="badge fs-6 px-3 py-2 
                                    @if($user->role === 'admin') 
                                        text-white" style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%); box-shadow: 0 2px 8px rgba(246, 194, 62, 0.3);"
                                    @else 
                                        text-white" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); box-shadow: 0 2px 8px rgba(78, 115, 223, 0.3);"
                                    @endif
                                >
                                    @if($user->role === 'admin')
                                        <i class="fas fa-user-shield me-1"></i>Quản trị viên
                                    @else
                                        <i class="fas fa-user me-1"></i>Khách hàng
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '' }}</div>
                                    <small class="text-muted">{{ $user->created_at ? $user->created_at->format('H:i') : '' }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ $user->updated_at ? $user->updated_at->format('d/m/Y') : '' }}</div>
                                    <small class="text-muted">{{ $user->updated_at ? $user->updated_at->format('H:i') : '' }}</small>
                                </div>
                            </td>
                            @if(Auth::user()->role === 'admin')
                            <td>
                                @if($user->id !== Auth::id())
                                <div class="d-flex gap-2 align-items-center">
                                    <form action="{{ route('admin.users.role', $user->id) }}" method="POST" class="role-update-form">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group input-group-sm">
                                            <select name="role" class="form-select" style="width: 120px;">
                                                <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Khách hàng</option>
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                @else
                                <span class="text-muted small">
                                    <i class="fas fa-lock me-1"></i>Không thể thay đổi
                                </span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.avatar-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.table th {
    font-weight: 600;
    color: #5a5c69;
    border-top: none;
}

.user-row:hover {
    background-color: #f8f9fc;
    transition: all 0.2s ease;
}

.role-update-form select {
    border-radius: 0.375rem 0 0 0.375rem;
}

.role-update-form button {
    border-radius: 0 0.375rem 0.375rem 0;
}

.input-group-sm .form-select {
    font-size: 0.875rem;
}
</style>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    filterUsers();
});

document.getElementById('roleFilter').addEventListener('change', function() {
    filterUsers();
});

function filterUsers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const roleFilter = document.getElementById('roleFilter').value;
    const rows = document.querySelectorAll('.user-row');
    
    rows.forEach(row => {
        const name = row.dataset.name;
        const email = row.dataset.email;
        const role = row.dataset.role;
        
        const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
        const matchesRole = roleFilter === '' || role === roleFilter;
        
        if (matchesSearch && matchesRole) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Export functionality
function exportUsers() {
    // This would typically make an AJAX call to export users
    alert('Chức năng xuất Excel sẽ được triển khai trong phiên bản tiếp theo');
}

// Sort functionality
document.getElementById('sortBy').addEventListener('change', function() {
    // This would typically reload the page with sort parameters
    console.log('Sort by:', this.value);
});

document.getElementById('sortOrder').addEventListener('change', function() {
    // This would typically reload the page with sort parameters
    console.log('Sort order:', this.value);
});

// Role update confirmation
document.querySelectorAll('.role-update-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const select = this.querySelector('select[name="role"]');
        const newRole = select.value;
        const userName = this.closest('tr').querySelector('.fw-semibold').textContent;
        
        if (!confirm(`Bạn có chắc chắn muốn thay đổi vai trò của "${userName}" thành "${newRole === 'admin' ? 'Quản trị viên' : 'Khách hàng'}"?`)) {
            e.preventDefault();
        }
    });
});
</script>
@endsection