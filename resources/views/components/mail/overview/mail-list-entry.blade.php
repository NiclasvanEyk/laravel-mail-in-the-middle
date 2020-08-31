@php
    /** @var \VanEyk\MITM\Models\StoredMail $mail */
    /** @var \Illuminate\Pagination\Paginator $pagination */
    /** @var bool $active */
    /** @var int $index */

    $linkParams = [];
    $page = $pagination->currentPage();

    if ($index !== 0) { $linkParams['index'] = $index; }
    if ($page !== 1) { $linkParams['page'] = $page; }

    $link = route(\VanEyk\MITM\Support\Route::name('mail-overview'), $linkParams);
@endphp

<li class="mail-sidebar-entry {{ $active ? 'active' : '' }}
           list-group-item list-group-item-action
           overflow-hidden position-relative">
    <div class="d-flex flex-row">
        <div class="flex-grow-1" style="overflow: hidden; text-overflow: ellipsis">
            <span style="white-space: nowrap" class="font-weight-bold">
                {{ $mail->subject }}
            </span>

            <br/>

            <span style="white-space: nowrap">
                @component('mail-in-the-middle::components.mail.meta.recipients', [
                    'addresses' => $mail->to,
                    'viewMode' => 'compact',
                ])@endcomponent
            </span>
        </div>

        <a class="btn view-mail {{ $active ? 'btn-primary' : 'btn-white bg-white' }}"
           href="{{ $link }}">
            <svg class="bi bi-eye mr-1" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.134 13.134 0 0 0 1.66 2.043C4.12 11.332 5.88 12.5 8 12.5c2.12 0 3.879-1.168 5.168-2.457A13.134 13.134 0 0 0 14.828 8a13.133 13.133 0 0 0-1.66-2.043C11.879 4.668 10.119 3.5 8 3.5c-2.12 0-3.879 1.168-5.168 2.457A13.133 13.133 0 0 0 1.172 8z"/>
                <path fill-rule="evenodd" d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
            </svg>
            <span style="vertical-align: middle">View</span>
        </a>
    </div>
</li>