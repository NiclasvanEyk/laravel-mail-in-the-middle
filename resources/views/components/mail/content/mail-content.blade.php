@php
/** @var \VanEyk\MITM\Models\StoredMail $mail */
@endphp

<div class="card-header d-flex flex-row mail-content">
    <nav class="w-100">
        <div class="nav nav-pills nav-fill" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" aria-selected="true"
               id="mailable-rendered-tab" aria-controls="mailable-rendered" href="#mailable-rendered"
               data-toggle="tab" role="tab">
                <svg class="bi bi-envelope" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M14 3H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zM2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2z"/>
                    <path d="M.05 3.555C.017 3.698 0 3.847 0 4v.697l5.803 3.546L0 11.801V12c0 .306.069.596.192.856l6.57-4.027L8 9.586l1.239-.757 6.57 4.027c.122-.26.191-.55.191-.856v-.2l-5.803-3.557L16 4.697V4c0-.153-.017-.302-.05-.445L8 8.414.05 3.555z"/>
                </svg>
                Content
            </a>
            <a class="nav-item nav-link" aria-selected="false"
               id="mailable-src-tab" aria-controls="mailable-src" href="#mailable-src"
               data-toggle="tab" role="tab">
                <svg class="bi bi-file-earmark-text" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 1h5v1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V6h1v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2z"/>
                    <path d="M9 4.5V1l5 5h-3.5A1.5 1.5 0 0 1 9 4.5z"/>
                    <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0-2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                </svg>
                Source
            </a>
            <a class="nav-item nav-link" aria-selected="false"
               id="mailable-view-data-tab" aria-controls="mailable-view-data" href="#mailable-view-data"
               data-toggle="tab" role="tab">
                <svg class="bi bi-file-earmark-code" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 1h5v1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V6h1v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2z"/>
                    <path d="M9 4.5V1l5 5h-3.5A1.5 1.5 0 0 1 9 4.5z"/>
                    <path fill-rule="evenodd" d="M8.646 6.646a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L10.293 9 8.646 7.354a.5.5 0 0 1 0-.708zm-1.292 0a.5.5 0 0 0-.708 0l-2 2a.5.5 0 0 0 0 .708l2 2a.5.5 0 0 0 .708-.708L5.707 9l1.647-1.646a.5.5 0 0 0 0-.708z"/>
                </svg>
                View Data
            </a>
        </div>
    </nav>
</div>

<div class="card-body p-0 position-relative flex-grow-1 mh-100">
    <div class="position-absolute h-100 w-100 overflow-auto">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="mailable-rendered" role="tabpanel" aria-labelledby="mailable-rendered-tab">
                <iframe
                        id="mail-preview"
                        height="100%"
                        width="100%"
                        class="d-block h-100 w-100 border-0 position-absolute"
                        srcdoc="{{ $mail->rendered }}"
                ></iframe>
            </div>

            <div class="tab-pane fade" id="mailable-src" role="tabpanel" aria-labelledby="mailable-src-tab">
                <pre class="mail-src"><code>{{ $mail->src }}</code></pre>
            </div>

            <div class="tab-pane fade" id="mailable-view-data" role="tabpanel" aria-labelledby="mailable-view-data-tab">
                <div class="var-dump">
                    {!! $mail->viewData !!}
                </div>
            </div>
        </div>
    </div>
</div>
