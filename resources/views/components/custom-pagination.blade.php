@if ($paginator->hasPages())
    <nav aria-label="Phân trang" class="mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Thông tin hiển thị -->
            <div class="pagination-info">
                <small>
                    Hiển thị {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} 
                    trong tổng số {{ $paginator->total() }} kết quả
                </small>
            </div>

            <!-- Phân trang -->
            <ul class="pagination pagination-sm mb-0">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @php
                    $currentPage = $paginator->currentPage();
                    $lastPage = $paginator->lastPage();
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($lastPage, $currentPage + 2);
                @endphp
                
                @if($startPage > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                    </li>
                    @if($startPage > 2)
                        <li class="page-item disabled">
                            <span class="page-link" title="Có thêm trang">
                                <i class="fas fa-ellipsis-h"></i>
                            </span>
                        </li>
                    @endif
                @endif
                
                @for($page = $startPage; $page <= $endPage; $page++)
                    @if ($page == $currentPage)
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">
                                {{ $page }}
                                <span class="visually-hidden">(trang hiện tại)</span>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->url($page) }}" title="Trang {{ $page }}">
                                {{ $page }}
                            </a>
                        </li>
                    @endif
                @endfor
                
                @if($endPage < $lastPage)
                    @if($endPage < $lastPage - 1)
                        <li class="page-item disabled">
                            <span class="page-link" title="Có thêm trang">
                                <i class="fas fa-ellipsis-h"></i>
                            </span>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url($lastPage) }}">{{ $lastPage }}</a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
@endif
