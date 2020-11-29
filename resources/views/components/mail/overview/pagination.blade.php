@php
/** @var Illuminate\Pagination\Paginator $pagination */
@endphp

<div class="d-flex flex-row">
    @if($pagination->previousPageUrl())
        <a class="btn p-3" style="font-size: 2em"
           href="{{ $pagination->previousPageUrl() }}">
            <svg class="bi bi-chevron-left" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
            </svg>
        </a>
    @endif

    <div class="flex-grow-1"></div>

    @if($pagination->hasMorePages())
        <a class="btn p-3" style="font-size: 2em"
           href="{{ $pagination->nextPageUrl() }}">
            <svg class="bi bi-chevron-right" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
            </svg>
        </a>
    @endif
</div>
