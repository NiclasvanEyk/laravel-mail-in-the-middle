<div class="position-absolute
                    alert alert-warning alert-dismissible
                    fade show
                    vw-100 shadow-sm
                    d-flex flex-row
        " style="z-index: 999" role="alert">
    <div>
        <strong>
            Your current using <code>{{$transport}}</code> to
            send your mails!
        </strong> This means the mails sent by your application will
        probably not show up here. You can change this by setting
        <code>{{$envKey}}="{{\VanEyk\MITM\Support\Config::KEY}}"</code>
        in your <code>.env</code>-file once you have
        <a href="https://github.com/NiclasvanEyk/laravel-mail-in-the-middle#installation">
            installed and configured this package as its described in
            the readme
        </a>.

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
