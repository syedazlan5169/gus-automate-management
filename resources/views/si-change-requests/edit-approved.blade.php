<x-app-layout>
    <div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Edit Approved Fields</h2>
            <p class="text-sm text-gray-600">
                Only the fields approved by the administrator are editable. Others are shown as read-only.
            </p>
        </div>

        <div class="rounded-lg bg-gradient-to-r from-amber-50 to-yellow-50 border-2 border-amber-200 p-4 mb-6 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-amber-900 mb-1">Important Notice</p>
                <p class="text-sm text-amber-800 leading-relaxed">
                    Telex BL has been released. Changes may incur an additional fee. Your request status is
                    <strong class="uppercase">{{ str_replace('_',' ', $changeRequest->status) }}</strong>.
                </p>
            </div>
        </div>

        {{-- display SI fields --}}
        <form method="POST" action="{{ route('si-change-requests.submit-edits', [$si, $changeRequest]) }}" id="edit-approved-form">
            @csrf
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-indigo-100/50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Shipping Instruction Fields</h3>
                    <p class="text-xs text-gray-600 mt-1">Fields highlighted in blue are editable</p>
                </div>
                
                <div class="p-6 space-y-6">
                    @foreach($fieldLabels as $name => $label)
                        @php $editable = in_array($name, $approvedFields, true); @endphp
                        
                        @if($name === 'containers')
                            {{-- Containers editing section --}}
                            @continue
                        @endif
                        
                        <div class="@if($editable) bg-indigo-50/50 border-2 border-indigo-200 rounded-lg p-4 @else bg-gray-50 border border-gray-200 rounded-lg p-4 @endif transition-all">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-semibold @if($editable) text-indigo-900 @else text-gray-700 @endif">
                                    {{ $label }}
                                    @if($editable)
                                        <span class="ml-2 inline-flex items-center gap-1 text-xs font-normal text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded-full">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Editable
                                        </span>
                                    @endif
                                </label>
                                @unless($editable)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-md">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Read-only
                                    </span>
                                @endunless
                            </div>

                            @if(in_array($name, ['shipper_address','consignee_address','notify_party_address'], true))
                                <textarea
                                    name="{{ $name }}"
                                    class="block w-full rounded-lg @if($editable) border-indigo-300 bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 @else border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed @endif text-sm transition-colors"
                                    rows="4"
                                    @disabled(!$editable)
                                    @if(!$editable) readonly @endif
                                >{{ is_array($si->{$name}) ? implode("\n", array_filter($si->{$name})) : ($si->{$name} ?? '') }}</textarea>
                            @elseif(in_array($name, ['gross_weight', 'volume'], true))
                                <input type="number"
                                    name="{{ $name }}"
                                    step="0.01"
                                    min="0"
                                    pattern="[0-9]+(\.[0-9]{1,2})?"
                                    class="block w-full rounded-lg @if($editable) border-indigo-300 bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 @else border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed @endif text-sm transition-colors"
                                    value="{{ old($name, $si->{$name}) }}"
                                    @disabled(!$editable)
                                    @if(!$editable) readonly @endif
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                />
                            @else
                                <input type="text"
                                    name="{{ $name }}"
                                    class="block w-full rounded-lg @if($editable) border-indigo-300 bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 @else border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed @endif text-sm transition-colors"
                                    value="{{ old($name, $si->{$name}) }}"
                                    @disabled(!$editable)
                                    @if(!$editable) readonly @endif
                                />
                            @endif
                        </div>
                    @endforeach

                {{-- Containers section --}}
                @php $containersEditable = in_array('containers', $approvedFields, true); @endphp
                @if(isset($fieldLabels['containers']))
                    <div class="@if($containersEditable) bg-indigo-50/50 border-2 border-indigo-200 rounded-lg p-4 @else bg-gray-50 border border-gray-200 rounded-lg p-4 @endif transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-semibold @if($containersEditable) text-indigo-900 @else text-gray-700 @endif">
                                {{ $fieldLabels['containers'] }}
                                @if($containersEditable)
                                    <span class="ml-2 inline-flex items-center gap-1 text-xs font-normal text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded-full">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Editable
                                    </span>
                                @else
                                    <span class="ml-2 inline-flex items-center gap-1 text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-md">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Read-only
                                    </span>
                                @endif
                            </label>
                        </div>

                        @if($containersEditable)
                            <div id="container-sections" class="mt-2 space-y-4">
                                @php
                                    $containersByCargo = $si->containers->groupBy('cargo_id');
                                @endphp
                                
                                @foreach($containersByCargo as $cargoId => $containers)
                                    @php
                                        $cargo = $containers->first()->cargo ?? null;
                                        $containerType = $cargo ? ($cargo->container_type ?? 'Unknown') : 'Unknown';
                                    @endphp
                                    
                                    @if($cargo)
                                        <div class="border-2 border-indigo-200 rounded-lg p-4 bg-white shadow-sm" data-container-type="{{ $cargoId }}">
                                            <div class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-indigo-100/50 rounded-lg border-b border-indigo-200 mb-3">
                                                <div class="flex items-center justify-between">
                                                    <h3 class="text-base font-semibold text-indigo-900">
                                                        {{ $containerType }}
                                                        <span class="ml-2 text-sm font-normal text-indigo-700">
                                                            Total: <span class="container-count font-semibold">{{ $containers->count() }}</span>
                                                        </span>
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="px-4 py-3">
                                                <div id="container-group-{{ $cargoId }}" class="space-y-3 max-h-[400px] overflow-y-auto">
                                                    @foreach($containers as $container)
                                                        <div class="flex items-center gap-3 w-full bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/30 transition-all">
                                                            <input type="hidden" name="containers[{{ $cargoId }}][{{ $loop->index }}][container_type]" value="{{ $cargoId }}">
                                                            <input type="text" 
                                                                name="containers[{{ $cargoId }}][{{ $loop->index }}][container_number]" 
                                                                value="{{ $container->container_number }}" 
                                                                placeholder="Container Number"
                                                                class="flex-1 rounded-lg bg-white px-3 py-2 text-sm text-gray-900 border border-indigo-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition-all">
                                                            <input type="text" 
                                                                name="containers[{{ $cargoId }}][{{ $loop->index }}][seal_number]" 
                                                                value="{{ $container->seal_number }}" 
                                                                placeholder="Seal Number"
                                                                class="flex-1 rounded-lg bg-white px-3 py-2 text-sm text-gray-900 border border-indigo-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition-all">
                                                            <button type="button" 
                                                                class="flex-shrink-0 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg p-2 transition-all" 
                                                                onclick="removeContainer(this)"
                                                                title="Remove container">
                                                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                
                                                <button type="button" 
                                                    onclick="addContainer({{ $cargoId }})"
                                                    class="mt-4 inline-flex items-center gap-2 px-4 py-2 border-2 border-dashed border-indigo-300 text-sm font-semibold rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 hover:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    Add Container
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                
                                @if($containersByCargo->isEmpty())
                                    <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">No containers allocated to this shipping instruction.</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="mt-2 border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <p class="text-sm font-medium text-gray-700">
                                    @if($si->containers->count() > 0)
                                        {{ $si->containers->count() }} container(s) allocated (view only)
                                    @else
                                        No containers allocated
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                @endif
                </div>
            </div>

            <div class="mt-8 flex justify-between items-center gap-4 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <a href="{{ route('booking.show', $booking) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:shadow-lg hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Submit for Final Review
                </button>
            </div>
        </form>

    </div>

    <script>
        function removeContainer(button) {
            const containerDiv = button.closest('.flex.items-center');
            const containerGroup = containerDiv.closest('[id^="container-group-"]');
            containerDiv.remove();
            
            // Update count
            updateContainerCount(containerGroup);
        }

        function addContainer(cargoId) {
            const containerGroup = document.getElementById(`container-group-${cargoId}`);
            if (!containerGroup) return;

            const existingCount = containerGroup.children.length;
            const div = document.createElement('div');
            div.className = 'flex items-center gap-3 w-full bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/30 transition-all';
            
            div.innerHTML = `
                <input type="hidden" name="containers[${cargoId}][${existingCount}][container_type]" value="${cargoId}">
                <input type="text" 
                    name="containers[${cargoId}][${existingCount}][container_number]" 
                    value="" 
                    placeholder="Container Number"
                    class="flex-1 rounded-lg bg-white px-3 py-2 text-sm text-gray-900 border border-indigo-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition-all">
                <input type="text" 
                    name="containers[${cargoId}][${existingCount}][seal_number]" 
                    value="" 
                    placeholder="Seal Number"
                    class="flex-1 rounded-lg bg-white px-3 py-2 text-sm text-gray-900 border border-indigo-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition-all">
                <button type="button" 
                    class="flex-shrink-0 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg p-2 transition-all" 
                    onclick="removeContainer(this)"
                    title="Remove container">
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;

            containerGroup.appendChild(div);
            updateContainerCount(containerGroup);
        }

        function updateContainerCount(containerGroup) {
            if (!containerGroup) return;
            
            const cargoId = containerGroup.id.replace('container-group-', '');
            const section = containerGroup.closest('[data-container-type]');
            if (!section) return;
            
            const count = containerGroup.children.length;
            const countSpan = section.querySelector('.container-count');
            if (countSpan) {
                countSpan.textContent = count;
            }
        }
    </script>
</x-app-layout>

