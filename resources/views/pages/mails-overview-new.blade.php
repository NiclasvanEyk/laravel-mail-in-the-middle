@extends('mail-in-the-middle::layout')

@section('content')
    @if($mailerUnused)
        @component('mail-in-the-middle::components.wrong-driver-banner', [
            'transport' => $transport,
            'envKey' => $envKey,
        ])@endcomponent
    @endif

    @component('mail-in-the-middle::components.layout.master-detail')
        @section('master')
            @component('mail-in-the-middle::components.mail.overview.mail-list', [
                'mails' => $mails,
                'selectedMailIndex' => $selectedMailIndex,
            ])@endcomponent
        @endsection

        @section('detail')
            @if(count($mails) > 0)
                @component('mail-in-the-middle::components.mail.detail.mail-detail', [
                    'mail' => $mails[$selectedMailIndex],
                ])@endcomponent
            @else
                @component('mail-in-the-middle::components.mail.overview.empty')@endcomponent
            @endif
        @endsection
    @endcomponent
@endsection
