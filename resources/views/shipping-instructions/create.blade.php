<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Create Shipping Instruction</h2>
                        <div class="text-sm text-gray-600">
                            Booking: <span class="font-semibold">{{ $booking->booking_number }}</span>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">Please check the form and try again.</span>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('shipping-instructions.store', $booking) }}" class="space-y-6">
                        @csrf

                        <!-- Shipper Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Shipper Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="shipper" value="Shipper Name" />
                                    <x-text-input id="shipper" name="shipper" type="text" 
                                        class="mt-1 block w-full" 
                                        :value="old('shipper')"
                                        required />
                                    <x-input-error :messages="$errors->get('shipper')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="contact_shipper" value="Shipper Contact" />
                                    <x-text-input id="contact_shipper" name="contact_shipper" type="text" 
                                        class="mt-1 block w-full"
                                        :value="old('contact_shipper')"
                                        required />
                                    <x-input-error :messages="$errors->get('contact_shipper')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Consignee Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Consignee Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="consignee" value="Consignee Name" />
                                    <x-text-input id="consignee" name="consignee" type="text" 
                                        class="mt-1 block w-full"
                                        :value="old('consignee')"
                                        required />
                                    <x-input-error :messages="$errors->get('consignee')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="contact_consignee" value="Consignee Contact" />
                                    <x-text-input id="contact_consignee" name="contact_consignee" type="text" 
                                        class="mt-1 block w-full"
                                        :value="old('contact_consignee')"
                                        required />
                                    <x-input-error :messages="$errors->get('contact_consignee')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Container Allocation -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Container Allocation</h3>
                            
                            @if($availableCargos->isEmpty())
                                <p class="text-red-600">No containers available for allocation.</p>
                            @else
                                @foreach($availableCargos as $containerType => $cargos)
                                    <div class="mb-4 p-4 border rounded">
                                        <h4 class="font-medium mb-2">{{ $containerType }}</h4>
                                        @foreach($cargos as $cargo)
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2 items-center">
                                                <input type="hidden" 
                                                    name="cargo_allocations[{{ $loop->parent->index }}_{{ $loop->index }}][cargo_id]" 
                                                    value="{{ $cargo->id }}">
                                                
                                                <div class="text-sm text-gray-600">
                                                    Available: {{ $cargo->container_count }} containers
                                                </div>
                                                
                                                <div>
                                                    <x-input-label :for="'allocation_' . $cargo->id" value="Allocate" />
                                                    <x-text-input 
                                                        id="allocation_{{ $cargo->id }}"
                                                        name="cargo_allocations[{{ $loop->parent->index }}_{{ $loop->index }}][container_count]"
                                                        type="number"
                                                        class="mt-1 block w-full"
                                                        min="0"
                                                        max="{{ $cargo->container_count }}"
                                                        :value="old('cargo_allocations.' . $loop->parent->index . '_' . $loop->index . '.container_count', 0)"
                                                    />
                                                </div>
                                                
                                                <div class="text-sm text-gray-600">
                                                    Total Weight: {{ number_format($cargo->total_weight, 2) }} kg
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Customer Instructions -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-4">Additional Instructions</h3>
                            <div>
                                <x-input-label for="customer_instructions" value="Special Instructions" />
                                <textarea id="customer_instructions" name="customer_instructions" 
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    rows="4">{{ old('customer_instructions') }}</textarea>
                                <x-input-error :messages="$errors->get('customer_instructions')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end gap-4">
                            <x-secondary-button onclick="window.history.back()">
                                Cancel
                            </x-secondary-button>
                            <x-primary-button>
                                Create Shipping Instruction
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Add any JavaScript validation or dynamic functionality here
        document.addEventListener('DOMContentLoaded', function() {
            const allocationInputs = document.querySelectorAll('input[type="number"]');
            
            allocationInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const max = parseInt(this.getAttribute('max'));
                    const value = parseInt(this.value);
                    
                    if (value > max) {
                        this.value = max;
                    } else if (value < 0) {
                        this.value = 0;
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout> 