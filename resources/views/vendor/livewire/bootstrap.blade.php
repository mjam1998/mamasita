@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination mb-0 justify-content-center flex-nowrap">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    {{-- تغییر برای لایووایر --}}
                    <button type="button" class="page-link" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="prev">&lsaquo;</button>
                </li>
            @endif

            @php
                $current = $paginator->currentPage();
                $last    = $paginator->lastPage();
                $onEachSide = 1; // تعداد صفحات نمایش‌داده‌شده در دو طرف صفحه فعلی
                $start = max($current - $onEachSide, 1);
                $end   = min($current + $onEachSide, $last);
            @endphp

            {{-- صفحه اول + ... --}}
            @if ($start > 1)
                <li class="page-item">
                    {{-- تغییر برای لایووایر --}}
                    <button type="button" class="page-link" wire:click="gotoPage(1, '{{ $paginator->getPageName() }}')">1</button>
                </li>
                @if ($start > 2)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
            @endif

            {{-- بازه اطراف صفحه فعلی --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $current)
                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item">
                        {{-- تغییر برای لایووایر --}}
                        <button type="button" class="page-link" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')">{{ $page }}</button>
                    </li>
                @endif
            @endfor

            {{-- ... + صفحه آخر --}}
            @if ($end < $last)
                @if ($end < $last - 1)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                <li class="page-item">
                    {{-- تغییر برای لایووایر --}}
                    <button type="button" class="page-link" wire:click="gotoPage({{ $last }}, '{{ $paginator->getPageName() }}')">{{ $last }}</button>
                </li>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    {{-- تغییر برای لایووایر --}}
                    <button type="button" class="page-link" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="next">&rsaquo;</button>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
