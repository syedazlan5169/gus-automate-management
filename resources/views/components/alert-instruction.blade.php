@props(['message', 'action_url' => null, 'action_text' => 'Take Action'])

<div class="border-l-4 border-yellow-400 bg-yellow-50 p-4">
    <div class="flex">
        <div class="shrink-0">
            <svg class="size-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                data-slot="icon">
                <path fill-rule="evenodd"
                    d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                    clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3 flex-1 md:flex md:justify-between">
            <p class="text-sm text-yellow-700">{{ $message }}</p>
            @if ($action_url)
            <p class="mt-3 text-sm md:ml-6 md:mt-0">
                <a href="{{ $action_url }}" class="whitespace-nowrap font-medium text-yellow-700 hover:text-yellow-600">
                    <strong>{{ $action_text }}</strong>
                </a>
            </p>
            @endif
        </div>
    </div>
</div> 