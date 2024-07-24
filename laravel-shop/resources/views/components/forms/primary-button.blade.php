@props([
    'type' => 'submit',
])

<button {{ $attributes
    ->class([
        'w-full btn btn-pink'
    ]) }}
>
    {{ $slot }}
</button>
