@props(['message', 'action_url' => null, 'action_text' => 'Take Action', 'color' => 'yellow'])

@php
$colorClasses = [
    'yellow' => [
        'border' => 'border-yellow-400',
        'bg' => 'bg-yellow-50',
        'text' => 'text-yellow-700',
        'icon' => 'text-yellow-400',
        'hover' => 'hover:text-yellow-600'
    ],
    'red' => [
        'border' => 'border-red-400',
        'bg' => 'bg-red-50',
        'text' => 'text-red-700',
        'icon' => 'text-red-400',
        'hover' => 'hover:text-red-600'
    ],
    'green' => [
        'border' => 'border-green-400',
        'bg' => 'bg-green-50',
        'text' => 'text-green-700',
        'icon' => 'text-green-400',
        'hover' => 'hover:text-green-600'
    ],
    'blue' => [
        'border' => 'border-blue-400',
        'bg' => 'bg-blue-50',
        'text' => 'text-blue-700',
        'icon' => 'text-blue-400',
        'hover' => 'hover:text-blue-600'
    ],
];

$currentColor = $colorClasses[$color] ?? $colorClasses['yellow'];
@endphp

<div class="border-l-4 {{ $currentColor['border'] }} {{ $currentColor['bg'] }} px-4 py-2">
    <div class="flex">
        <div class="shrink-0">
            <svg class="size-5 {{ $currentColor['icon'] }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3 flex-1 md:flex md:justify-between">
            <p class="text-sm {{ $currentColor['text'] }}">{{ $message }}</p>
            @if ($action_url)
            <p class="mt-3 text-sm md:ml-6 md:mt-0">
                <a href="{{ $action_url }}" class="whitespace-nowrap font-medium {{ $currentColor['text'] }} {{ $currentColor['hover'] }}">
                    <strong>{{ $action_text }}</strong>
                </a>
            </p>
            @endif
        </div>
    </div>
</div> 