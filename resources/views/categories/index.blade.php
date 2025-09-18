@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="text-headline mb-2">
                        <i class="fas fa-tags text-highlight me-2"></i>Quản lý danh mục
                    </h2>
                    <p class="text-secondary mb-0">Quản lý các danh mục sản phẩm nước</p>
                </div>
                <div class="d-flex gap-2">
                    <a class="btn btn-outline-secondary" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại Dashboard
                    </a>
                    <a class="btn btn-primary" href="{{ route('admin.categories.create') }}">
                        <i class="fas fa-plus me-2"></i>Thêm danh mục mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Categories Grid -->
    <div class="row">
        @forelse ($categories as $category)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title text-headline mb-0">{{ $category->name }}</h5>
                            <span class="badge badge-primary">{{ $category->products_count }} sản phẩm</span>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-secondary">
                                <i class="fas fa-calendar me-1"></i>
                                Tạo: {{ $category->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-outline btn-sm flex-grow-1">
                                <i class="fas fa-eye me-1"></i>Xem
                            </a>
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm" 
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Tất cả sản phẩm trong danh mục sẽ bị ảnh hưởng.')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="card shadow-soft">
                        <div class="card-body py-5">
                            <i class="fas fa-tags fa-5x text-highlight mb-4 opacity-50"></i>
                            <h4 class="text-headline mb-3">Chưa có danh mục nào</h4>
                            <p class="text-secondary mb-4">Hãy tạo danh mục đầu tiên để bắt đầu quản lý sản phẩm.</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tạo danh mục đầu tiên
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection