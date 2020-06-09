@php
/** @var \VanEyk\MITM\Models\StoredMail[]|\Illuminate\Pagination\Paginator $mails */
@endphp

<div class="list-group-item border-right-0 d-flex flex-row align-items-center mail-list-header">
    <h1 class="m-0">
        <svg class="bi bi-inbox-fill inbox-icon" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M3.81 4.063A1.5 1.5 0 0 1 4.98 3.5h6.04a1.5 1.5 0 0 1 1.17.563l3.7 4.625a.5.5 0 0 1-.78.624l-3.7-4.624a.5.5 0 0 0-.39-.188H4.98a.5.5 0 0 0-.39.188L.89 9.312a.5.5 0 1 1-.78-.624l3.7-4.625z"/>
            <path fill-rule="evenodd" d="M.125 8.67A.5.5 0 0 1 .5 8.5h5A.5.5 0 0 1 6 9c0 .828.625 2 2 2s2-1.172 2-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 .496.562l-.39 3.124a1.5 1.5 0 0 1-1.489 1.314H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .121-.393z"/>
        </svg>
        <span style="margin-left: 15px; font-weight: bold;">
            Mails
        </span>
    </h1>

    <div class="flex-grow-1"></div>

    @if(count($mails) > 0)
        <form method="post"
              action="{{ route(\VanEyk\MITM\Support\Route::name('clear-all')) }}"
              onsubmit="return confirm('This deletes *ALL* stored mails! Are you sure you want to do this?')">
            @method('delete')
            @csrf
            <button type="submit" class="btn p-0" data-toggle="tooltip" title="test">
                <svg class="bi bi-trash delete-all-mail"
                     data-tooltip="Delete all mail"
                     width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                </svg>
            </button>
        </form>
    @endif
</div>

<ul class="list-group list-group-flush">
    @foreach($mails as $mail)
        @component('mail-in-the-middle::components.mail.overview.mail-list-entry', [
            'mail' => $mails[$selectedMailIndex],
            'active' => $loop->index == $selectedMailIndex,
            'pagination' => $mails,
        ])@endcomponent
    @endforeach
</ul>

<div class="flex-grow-1"></div>

@component('mail-in-the-middle::components.mail.overview.pagination', [
    'pagination' => $mails,
])@endcomponent
