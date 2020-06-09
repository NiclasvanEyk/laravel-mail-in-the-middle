@php
/** @var \VanEyk\MITM\Models\StoredMail $mail */
/** @var bool $showPermaLink */
@endphp

<div class="card" id="meta-data" style="margin-top: 10px">
    <div class="card-header d-flex flex-row border-bottom-0">
        <h2 class="mb-0 flex-grow-1"
            style="cursor: pointer"
            data-toggle="collapse"
            data-target="#meta-data-list" aria-controls="meta-data-list"
            aria-expanded="true"
        >
            <svg class="bi bi-info-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
                <circle cx="8" cy="4.5" r="1"/>
            </svg>
            <span class="ml-2" style="vertical-align: middle">Metadata</span>
        </h2>

        <div>
            @component('mail-in-the-middle::components.mail.detail.mail-actions', [
                'mail' => $mail,
                'showPermaLink' => $showPermaLink,
            ])@endcomponent
        </div>
    </div>

    <ul class="list-group list-group-flush collapse show"
        id="meta-data-list" data-parent="#meta-data"
        style="max-height: 30vh; overflow-y: auto;"
    >
        <li class="list-group-item">
            Subject: {{$mail->subject}}
        </li>
        <li class="list-group-item">
            Sent: <span data-toggle="tooltip" title="{{ $mail->created_at->format('Y-m-d H:i:s') }}">
                {{ $mail->created_at->longRelativeToNowDiffForHumans() }}
            </span>
        </li>
        <li class="list-group-item">
            From: @component('mail-in-the-middle::components.mail.meta.address', [
                'address' => $mail->from,
            ])@endcomponent
        </li>
        <li class="list-group-item">
            To: @component('mail-in-the-middle::components.mail.meta.recipients', [
                'addresses' => $mail->to,
            ])@endcomponent
        </li>
        @if ($mail->cc)
            <li class="list-group-item">
                CC: @component('mail-in-the-middle::components.mail.meta.recipients', [
                    'addresses' => $mail->cc,
                ])@endcomponent
            </li>
        @endif
        @if ($mail->bcc)
            <li class="list-group-item">
                BCC: @component('mail-in-the-middle::components.mail.meta.recipients', [
                    'addresses' => $mail->bcc,
                ])@endcomponent
            </li>
        @endif
        @if (!empty($mail->attachments))
            <li class="list-group-item d-flex flex-row flex-wrap">
                <div class="d-flex align-items-center mr-1">
                    Attachments:
                </div>
                @foreach ($mail->attachments as $attachment)
                    <div class="mx-1 my-1" title="download {{$attachment->name}}">
                        {{-- <AttachmentPill
                            mail={mail}
                            attachment={attachment}
                        /> --}}
                        TODO
                    </div>
                @endforeach
            </li>
        @endif
    </ul>
</div>
