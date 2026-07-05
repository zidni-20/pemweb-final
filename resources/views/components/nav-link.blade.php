@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'inline-flex items-center px-3 py-2 rounded-md bg-white/20 text-white text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out'
        : 'inline-flex items-center px-3 py-2 rounded-md text-sm font-medium leading-5 text-white opacity-90 hover:opacity-100 hover:bg-white/10 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>