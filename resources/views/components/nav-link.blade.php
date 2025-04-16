@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'group flex gap-x-3 rounded-md bg-gray-800 p-2 text-sm/6 font-semibold text-white'
        : 'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-gray-400 hover:bg-gray-800 hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>