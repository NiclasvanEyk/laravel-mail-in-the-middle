<a
    download
    class="text-reset file-download-pill"
    style="
        vertical-align: middle;
        word-wrap: anywhere;
        text-decoration: none;
    "
    href={{\VanEyk\MITM\Support\Route::resolve('attachment.download', [
        'mailId' => data_get($mail, 'id'),
        'attachmentId' => data_get($attachment, 'id'),
    ])}}
>
    <span class="mr-2">
        <x-mitm-attachment-icon
            :mimeType="data_get($attachment, 'mime_type')"
            :fileName="data_get($attachment, 'name')"
        />
    </span>
    <span>
      {{ data_get($attachment, 'name') }}
    </span>
</a>

