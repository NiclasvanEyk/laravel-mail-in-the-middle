@php
/** @var string|string[] $addresses */

if (is_string($addresses)) {
    $addresses = explode(', ', $addresses);
}
@endphp

@if (is_array($addresses) || empty($addresses))
    @if (count($addresses) === 1)
    
        @component('mail-in-the-middle::components.mail.meta.address', [
            'address' => $addresses[0],
            'viewMode' => $viewMode ?? '',
        ])@endcomponent

    @else

    @foreach ($addresses as $address)
        @component('mail-in-the-middle::components.mail.meta.address', [
            'address' => $address,
            'viewMode' => $viewMode ?? '',
        ])@endcomponent @if ($loop->last), @endif
    @endforeach

    @endif
@endif
