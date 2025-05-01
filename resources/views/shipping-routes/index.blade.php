<x-app-layout>
    <div class="mx-auto flex w-full flex-col px-4 py-10 sm:px-6 lg:px-8">
        <!-- Header section -->
        <div class="max-w-xl pb-8 space-y-2">
            <!-- Breadcrumb -->
            {{ Breadcrumbs::render('routes') }}

            <!-- Heading -->
            <h1 id="create-shipping-route-heading" class="text-3xl font-bold tracking-tight text-gray-900">Shipping Routes</h1>
        </div>

        <div class="flex-1">
            @if(session('success'))
                <x-alert-success :message="session('success')" />
            @endif

            <livewire:shipping-route-table />
        </div>
    </div>
</x-app-layout>