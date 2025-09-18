@if ($paginator->hasPages())
    <nav aria-label="Phân trang nâng cao" class="mt-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <!-- Thông tin hiển thị và chọn số items per page -->
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="pagination-info">
                    <small>
                        Hiển thị {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} 
                        trong tổng số {{ $paginator->total() }} kết quả
                    </small>
                </div>
                
                @if(isset($perPageOptions) && count($perPageOptions) > 1)
                <div class="d-flex align-items-center gap-2">
                    <label for="perPageSelect" class="form-label mb-0 small">Hiển thị:</label>
                    <select id="perPageSelect" class="form-select form-select-sm" style="width: auto;" onchange="changePerPage(this.value)">
                        @foreach($perPageOptions as $option)
                            <option value="{{ $option }}" {{ $paginator->perPage() == $option ? 'selected' : '' }}>
                                {{ $option }} / trang
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>

            <!-- Phân trang -->
            <div class="d-flex align-items-center gap-2">
                <!-- Jump to page -->
                @if($paginator->lastPage() > 5)
                <div class="d-flex align-items-center gap-1">
                    <label for="jumpToPage" class="form-label mb-0 small">Trang:</label>
                    <input type="number" id="jumpToPage" class="form-control form-control-sm" 
                           style="width: 60px;" min="1" max="{{ $paginator->lastPage() }}" 
                           value="{{ $paginator->currentPage() }}" onchange="jumpToPage(this.value)">
                </div>
                @endif
                
                <ul class="pagination pagination-sm mb-0">
                    {{-- First Page Link --}}
                    @if ($paginator->currentPage() > 3)
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->url(1) }}" title="Trang đầu">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link" title="Trang trước">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" title="Trang trước">
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
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" title="Trang sau">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link" title="Trang sau">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        </li>
                    @endif

                    {{-- Last Page Link --}}
                    @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" title="Trang cuối">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <script>
        function changePerPage(perPage) {
            const url = new URL(window.location);
            url.searchParams.set('per_page', perPage);
            url.searchParams.delete('page'); // Reset to first page
            window.location.href = url.toString();
        }

        function jumpToPage(page) {
            if (page >= 1 && page <= {{ $paginator->lastPage() }}) {
                const url = new URL(window.location);
                url.searchParams.set('page', page);
                window.location.href = url.toString();
            }
        }
    </script>
@endif
