@if ($paginator->hasPages())
@php
    $paginator->appends(request()->except($paginator->getPageName()));
@endphp
<div class="pag">
    @if ($paginator->onFirstPage())
        <span class="dis">&laquo; Prev</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}">&laquo; Prev</a>
    @endif
    <span>Page {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }} ({{ $paginator->total() }} total)</span>
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}">Next &raquo;</a>
    @else
        <span class="dis">Next &raquo;</span>
    @endif
</div>
@else
<div class="pag"><span>{{ $paginator->total() }} total</span></div>
@endif
