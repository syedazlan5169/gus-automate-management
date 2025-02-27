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
                                            <div class="space-y-4">
                                                <input type="hidden" 
                                                    name="cargo_allocations[{{ $loop->parent->index }}_{{ $loop->index }}][cargo_id]" 
                                                    value="{{ $cargo->id }}">
                                                
                                                <div class="text-sm text-gray-600">
                                                    Available slots: {{ $cargo->container_count }} containers
                                                </div>
                                                
                                                <div>
                                                    <x-input-label 
                                                        :for="'container_list_' . $cargo->id" 
                                                        value="Container Numbers with Seal Numbers (Format: CONT1/SEAL1, CONT2/SEAL2)" />
                                                    <textarea 
                                                        id="container_list_{{ $cargo->id }}"
                                                        name="cargo_allocations[{{ $loop->parent->index }}_{{ $loop->index }}][container_list]"
                                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                        rows="3"
                                                        placeholder="MSCU1234567/SL123456, TEMU7654321/SL654321"
                                                    >{{ old('cargo_allocations.' . $loop->parent->index . '_' . $loop->index . '.container_list') }}</textarea>
                                                </div>
                                                
                                                <div class="text-sm space-y-2">
                                                    <div class="text-gray-600">
                                                        Total Weight: {{ number_format($cargo->total_weight, 2) }} kg
                                                    </div>
                                                    <div>
                                                        Containers Listed: <span id="container_count_{{ $cargo->id }}" class="font-semibold">0</span>
                                                    </div>
                                                    <div>
                                                        Remaining Slots: <span id="remaining_slots_{{ $cargo->id }}" class="font-semibold">{{ $cargo->container_count }}</span>
                                                    </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const containerTextareas = document.querySelectorAll('textarea[id^="container_list_"]');
            
            containerTextareas.forEach(textarea => {
                // Function to update container counts
                const updateContainerCount = (element) => {
                    const cargoId = element.id.split('_').pop();
                    const maxContainers = parseInt(document.querySelector(`#remaining_slots_${cargoId}`).getAttribute('data-max') || 0);
                    
                    // Split by commas and filter out empty entries
                    const containerList = element.value
                        .split(',')
                        .map(item => item.trim())
                        .filter(item => item.length > 0);
                    
                    // Update the container count
                    const countElement = document.querySelector(`#container_count_${cargoId}`);
                    const remainingElement = document.querySelector(`#remaining_slots_${cargoId}`);
                    
                    const currentCount = containerList.length;
                    countElement.textContent = currentCount;
                    remainingElement.textContent = maxContainers - currentCount;
                    
                    // Validate format
                    const isValidFormat = containerList.every(item => {
                        const parts = item.split('/');
                        return parts.length === 2 && parts[0].trim() && parts[1].trim();
                    });
                    
                    // Visual feedback
                    if (!isValidFormat && element.value.trim() !== '') {
                        element.classList.add('border-red-500');
                        element.title = 'Invalid format. Please use: CONTAINER/SEAL, CONTAINER/SEAL';
                    } else {
                        element.classList.remove('border-red-500');
                        element.title = '';
                    }
                    
                    // Validate count
                    if (currentCount > maxContainers) {
                        remainingElement.classList.add('text-red-600');
                    } else {
                        remainingElement.classList.remove('text-red-600');
                    }
                };

                // Listen for all possible events that might change the content
                ['input', 'change', 'blur', 'keyup'].forEach(eventType => {
                    textarea.addEventListener(eventType, () => updateContainerCount(textarea));
                });

                // Special handling for paste event
                textarea.addEventListener('paste', function(e) {
                    // Update immediately after paste
                    setTimeout(() => {
                        updateContainerCount(this);
                    }, 0);
                });
                
                // Set initial max value and run initial count
                const cargoId = textarea.id.split('_').pop();
                const remainingElement = document.querySelector(`#remaining_slots_${cargoId}`);
                remainingElement.setAttribute('data-max', remainingElement.textContent);
                
                // Run initial count in case there's pre-filled data
                updateContainerCount(textarea);
            });
        });
    </script>
    @endpush
</x-app-layout> 