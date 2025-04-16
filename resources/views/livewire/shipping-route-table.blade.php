<div>
<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <div class="w-full">
        <!-- Search and Per Page Controls -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-2 sm:space-y-0">
            <div class="w-full sm:w-64">
                <input wire:model.live="search" type="text" placeholder="Search routes..." 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('shipping-routes.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Route
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="setSortBy('origin')" class="px-6 py-3 text-left cursor-pointer">
                            <div class="flex items-center">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Origin</span>
                                @if($sortBy === 'origin')
                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDir === 'ASC' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th wire:click="setSortBy('destination')" class="px-6 py-3 text-left cursor-pointer">
                            <div class="flex items-center">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</span>
                                @if($sortBy === 'destination')
                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDir === 'ASC' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Place of Receipt</span>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Place of Delivery</span>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">POL</span>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">POD</span>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($shippingRoutes as $route)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $route->origin }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $route->destination }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $route->place_of_receipt }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $route->place_of_delivery }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $route->pol }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $route->pod }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('shipping-routes.edit', $route) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-route-deletion-{{ $route->id }}')" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                No shipping routes found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $shippingRoutes->links() }}
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@foreach($shippingRoutes as $route)
    <x-modal name="confirm-route-deletion-{{ $route->id }}" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="delete({{ $route->id }})" class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete this shipping route?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once this shipping route is deleted, all of its resources and data will be permanently deleted.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ml-3">
                    {{ __('Delete Route') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
@endforeach
</div>