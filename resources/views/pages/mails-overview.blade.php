@extends('mail-in-the-middle::layout')

@section('content')
    <script async defer src="{{ \VanEyk\MITM\Support\Route::asset(
        'js/overview.js'
    ) }}"></script>

    @if(config('mail.driver') !== \VanEyk\MITM\Support\Config::KEY)
        <div class="position-absolute
                    alert alert-warning alert-dismissible
                    fade show
                    vw-100 shadow-sm
                    d-flex flex-row
        " style="z-index: 999" role="alert">
            <div>
                <strong>
                    Your current mail driver is <code>{{config('mail.driver')}}</code>!
                </strong> This means the mails sent by your application will
                probably not show up here. You can change this by setting
                <code>MAIL_DRIVER="{{\VanEyk\MITM\Support\Config::KEY}}"</code> in
                your <code>.env</code>-file or configure it your
                <code>config/mail.php</code>.

                <i>Note that you need to restart your server, if you are using
                <code>php artisan serve</code> after you changed your
                <code>.env</code>-file!</i>
            </div>

            <button type="button"
                    class="close"
                    title="close"
                    data-dismiss="alert"
                    aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endsection
