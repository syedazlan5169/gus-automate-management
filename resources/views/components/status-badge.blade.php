@props(['text', 'color' => 'green'])

@php
$colorClasses = [
    'green' => 'bg-green-50 text-green-700 ring-green-600/10',
    'red' => 'bg-red-50 text-red-700 ring-red-600/10',
    'yellow' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/10',
    'blue' => 'bg-blue-50 text-blue-700 ring-blue-600/10',
    'gray' => 'bg-gray-50 text-gray-700 ring-gray-600/10',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset ' . ($colorClasses[$color] ?? $colorClasses['gray'])]) }}>
    {{ $text }}
</span> 