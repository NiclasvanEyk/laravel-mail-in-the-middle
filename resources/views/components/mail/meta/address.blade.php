@php
    /** @var $address $all */

    $all = trim($address);
    $addressStart = strpos($all, '<');
    $addressEnd = strrpos($all, '>');
    $addressInBrackets = trim(mb_substr(trim($address), $addressStart + 1, $addressEnd - 1));
    $name = trim(mb_substr($all, 0, $addressStart));
@endphp
    
@if ($addressInBrackets && $name)
    @if ($viewMode === 'compact')
        <a
            href="mailto:{{$addressInBrackets}}"
            title="{{$addressInBrackets}}"
            class="mailto"
        >
            {{$name}}
        </a>
    @else 
    <span>{{$name}}</span>&nbsp;&lt;<a class="mailto" href="mailto:{{$addressInBrackets}}">{{$addressInBrackets}}</a>&gt;
    @endif
@else
    <a class="mailto" href="mailto:{{$all}}">
        {{ $addressInBrackets ? $addressInBrackets : $all }}
    </a>
@endif
