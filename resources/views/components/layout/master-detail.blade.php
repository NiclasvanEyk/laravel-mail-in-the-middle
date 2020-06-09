<div class="d-flex flexrow">
    <div class="d-flex flex-column w-25 h-100 position-fixed border-right border-gray">
        @yield('master')
    </div>

    <div class="flex-grow-1" style="padding-left: 25%;">
        <div class="container">
            @yield('detail')
        </div>
    </div>
</div>
