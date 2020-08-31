@php
    /** @var \VanEyk\MITM\Models\StoredMail $mail */
    /** @var bool $showPermaLink */
@endphp

<div class="d-flex flex-column mail-detail" style="height: 100vh">
    @component('mail-in-the-middle::components.mail.meta.mail-meta', [
         'mail' => $mail,
         'showPermaLink' => $showPermaLink ?? true,
    ])@endcomponent

    <div class="mt-5 d-flex flex-column flex-grow-1 overflow-hidden border rounded">
        @component('mail-in-the-middle::components.mail.content.mail-content', [
            'mail' => $mail,
        ])@endcomponent
    </div>
</div>
