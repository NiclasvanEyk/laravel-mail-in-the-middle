@extends('mail-in-the-middle::layout')

@section('content')
    <div class="container">
        @component('mail-in-the-middle::components.mail.detail.mail-detail', [
            'mail' => $mail,
            'showPermaLink' => false, // We are already on the detail page
        ])@endcomponent
    </div>
@endsection
