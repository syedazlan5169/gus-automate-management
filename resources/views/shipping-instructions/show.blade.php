<x-app-layout>
  <div class="mx-auto flex w-full max-w-10xl flex-col px-4 py-10 sm:px-6 lg:px-8">
    <!-- Header section -->
    <div class="max-w-xl pb-8 space-y-2">
      <!-- Breadcrumb -->
      <nav class="flex" aria-label="Breadcrumb">
        <ol role="list" class="flex items-center space-x-4">
          <li>
            <div>
              <a href="{{ route('booking.show', $shippingInstruction->booking) }}" class="text-gray-400 hover:text-gray-500">
                <svg class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z"/>
                </svg>
                <span class="sr-only">Back to Booking</span>
              </a>
            </div>
          </li>
        </ol>
      </nav>

      <!-- Shipping Instruction Heading -->
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <div class="flex items-center gap-3">
            <h1 class="text-2xl font-semibold">Shipping Instruction Details</h1>
            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/10">
              {{ $shippingInstruction->sub_booking_number }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Content section with flex layout -->
    <div class="flex items-start gap-x-8">
      <main class="flex-1">
        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4 mb-4">
                <div class="flex">
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            There were {{ $errors->count() }} errors with your submission
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul role="list" class="list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md bg-red-50 p-4 mb-4">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('shipping-instructions.update', $shippingInstruction) }}" method="POST" class="space-y-6">
          @csrf
          @method('PUT')
          
          <!-- First container section -->
          <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
              <div class="space-y-6">
                <!-- Box Operator at the top -->
                <div class="sm:col-span-3">
                  <x-input-label for="box_operator">Box Operator</x-input-label>
                  <div class="mt-2">
                    <select id="box_operator" name="box_operator" 
                      class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                      <option value="MAERSK" {{ $shippingInstruction->box_operator === 'MAERSK' ? 'selected' : '' }}>MAERSK</option>
                      <option value="CMA CGM" {{ $shippingInstruction->box_operator === 'CMA CGM' ? 'selected' : '' }}>CMA CGM</option>
                      <option value="HAPPAG LLOYD" {{ $shippingInstruction->box_operator === 'HAPPAG LLOYD' ? 'selected' : '' }}>HAPPAG LLOYD</option>
                    </select>
                  </div>
                </div>

                <!-- Shipper Information -->
                <h2 class="text-base/7 font-semibold text-gray-900">Shipper Information</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-3">
                    <x-input-label for="shipper">Shipper Name</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="shipper" id="shipper" value="{{ $shippingInstruction->shipper }}" required/>
                    </div>
                  </div>

                  <div class="sm:col-span-3">
                    <x-input-label for="contact_shipper">Shipper Contact</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="contact_shipper" id="contact_shipper" value="{{ $shippingInstruction->contact_shipper }}" required/>
                    </div>
                  </div>
                </div>

                <!-- Consignee Information -->
                <h2 class="text-base/7 font-semibold text-gray-900">Consignee Information</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-3">
                    <x-input-label for="consignee">Consignee Name</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="consignee" id="consignee" value="{{ $shippingInstruction->consignee }}" required/>
                    </div>
                  </div>

                  <div class="sm:col-span-3">
                    <x-input-label for="contact_consignee">Consignee Contact</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="contact_consignee" id="contact_consignee" value="{{ $shippingInstruction->contact_consignee }}" required/>
                    </div>
                  </div>
                </div>

                <!-- Notify Party Information -->
                <h2 class="text-base/7 font-semibold text-gray-900">Notify Party Information</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-3">
                    <x-input-label for="notify_party">Notify Party Name</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="notify_party" id="notify_party" value="{{ $shippingInstruction->notify_party }}" required/>
                    </div>
                  </div>

                  <div class="sm:col-span-3">
                    <x-input-label for="notify_party_contact">Notify Party Contact</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="notify_party_contact" id="notify_party_contact" value="{{ $shippingInstruction->notify_party_contact }}" required/>
                    </div>
                  </div>

                  <div class="sm:col-span-full">
                    <x-input-label for="notify_party_address">Notify Party Address</x-input-label>
                    <div class="mt-2">
                      <textarea rows="3" name="notify_party_address" id="notify_party_address"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">{{ $shippingInstruction->notify_party_address }}</textarea>
                    </div>
                  </div>
                </div>

                <!-- Cargo Details -->
                <h2 class="text-base/7 font-semibold text-gray-900">Cargo Details</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-3">
                    <x-input-label for="cargo_description">Cargo Description</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="cargo_description" id="cargo_description" value="{{ $shippingInstruction->cargo_description }}" required/>
                    </div>
                  </div>

                  <div class="sm:col-span-3">
                    <x-input-label for="hs_code">HS Code</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="hs_code" id="hs_code" value="{{ $shippingInstruction->hs_code }}" required/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Container Allocation Section -->
          <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-6">
                    <!-- Container Allocation Header -->
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h2 class="text-base/7 font-semibold text-gray-900">Container Allocation</h2>
                            <div class="mt-2 space-y-1" id="allocation-info">
                                @foreach($shippingInstruction->booking->cargos as $cargo)
                                    @php
                                        // Count all containers that have been allocated to ANY shipping instruction
                                        $allocatedCount = $cargo->containers()
                                            ->whereNotNull('shipping_instruction_id')
                                            ->count();
                                        $availableCount = $cargo->container_count - $allocatedCount;
                                    @endphp
                                    <p class="text-sm {{ $availableCount > 0 ? 'text-blue-700' : 'text-red-700' }}" 
                                       data-container-type="{{ $cargo->id }}" 
                                       data-total="{{ $cargo->container_count }}">
                                        {{ $cargo->container_type }}: 
                                        <strong data-available="{{ $availableCount }}">{{ $availableCount }} of {{ $cargo->container_count }} available</strong>
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Add Container Form -->
                    <div class="mt-4 space-y-4">
                        <div class="sm:col-span-2">
                            <label for="container_type" class="block text-sm font-medium text-gray-900">Container Type</label>
                            <select id="container_type" name="container_type"
                                class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="">Select Container Type</option>
                                @foreach($shippingInstruction->booking->cargos as $cargo)
                                    @php
                                      $allocatedCount = $cargo->containers()
                                            ->whereNotNull('shipping_instruction_id')
                                            ->count();
                                      $availableCount = $cargo->container_count - $allocatedCount;
                                    @endphp
                                    @if($availableCount > 0)
                                        <option value="{{ $cargo->id }}" data-available="{{ $availableCount }}">
                                            {{ $cargo->container_type }} ({{ $availableCount }} available)
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-4">
                            <!-- Manual Entry Section -->
                            <div>
                                <label for="container_list" class="block text-sm font-medium text-gray-900">
                                    Container List (Format: CONTAINER,SEAL - One per line)
                                </label>
                                <textarea id="container_list" name="container_list" rows="4"
                                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    placeholder="TEMU0192292,SEAL001"></textarea>
                            </div>

                            <!-- File Upload Section -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-medium text-gray-700">Or Upload Excel/CSV File</label>
                                    <a href="{{ route('shipping-instructions.download-template') }}" 
                                       class="text-sm text-indigo-600 hover:text-indigo-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10.75 2.75a.75.75 0 00-1.5 0v8.614L6.295 8.235a.75.75 0 10-1.09 1.03l4.25 4.5a.75.75 0 001.09 0l4.25-4.5a.75.75 0 00-1.09-1.03l-2.955 3.129V2.75z" />
                                            <path d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z" />
                                        </svg>
                                        Download Template
                                    </a>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="flex-1">
                                        <input type="file" id="container_file" name="container_file" 
                                            accept=".xlsx,.xls,.csv"
                                            class="mt-2 block w-full text-sm text-gray-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-md file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-indigo-50 file:text-indigo-700
                                                hover:file:bg-indigo-100"/>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Upload Excel/CSV file with container numbers and seal numbers
                                        </p>
                                    </div>
                                    <div class="flex-none pt-6">
                                        <button type="button" onclick="addContainers()"
                                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                            Add Containers
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Existing Containers Display -->
                        <div id="container-sections" class="mt-4 space-y-4">
                            @foreach($shippingInstruction->containers->groupBy('cargo.container_type') as $type => $containers)
                            <div class="border rounded-md p-4" data-container-type="{{ $containers->first()->cargo_id }}">
                                <div class="px-4 py-3 border-b">
                                    <h3 class="text-base font-semibold text-gray-900">
                                        {{ $type }}
                                        <span class="ml-2 text-sm font-normal text-gray-600" id="count-{{ $containers->first()->cargo_id }}">
                                            Total: {{ $containers->count() }}
                                        </span>
                                    </h3>
                                </div>
                                <div class="px-4 py-3">
                                    <div id="container-group-{{ $containers->first()->cargo_id }}" class="space-y-3">
                                        @foreach($containers as $container)
                                        <div class="flex items-center gap-x-4 w-full">
                                            <input type="hidden" name="containers[{{ $container->cargo_id }}][{{ $loop->index }}][container_type]" value="{{ $container->cargo_id }}">
                                            <input type="text" name="containers[{{ $container->cargo_id }}][{{ $loop->index }}][container_number]" 
                                                value="{{ $container->container_number }}" 
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 sm:text-sm/6">
                                            <input type="text" name="containers[{{ $container->cargo_id }}][{{ $loop->index }}][seal_number]" 
                                                value="{{ $container->seal_number }}" 
                                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 sm:text-sm/6">
                                            <button type="button" class="text-gray-400 hover:text-red-600" onclick="removeContainer(this)">
                                                <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
              <div class="flex justify-between space-x-4">
                <div>
                  <a href="{{ route('booking.show', $shippingInstruction->booking) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Back
                  </a>
                </div>
                <div class="flex space-x-4">
                  <x-primary-button type="submit" class="bg-blue-700">
                    Update SI
                  </x-primary-button>
                  <a href="#"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                    Generate BL
                  </a>
                </div>
              </div>
            </div>
          </div>
        </form>
      </main>

      <!-- Right Sidebar -->
      <aside class="sticky top-8 hidden w-96 shrink-0 xl:block">
        <div class="overflow-hidden rounded-lg bg-white shadow">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900">Shipping Instruction Status</h3>
            <div class="mt-2 max-w-xl text-sm text-gray-500">
              <p>Current status and next steps for this shipping instruction.</p>
            </div>
            <div class="mt-5">
              <div class="rounded-md bg-blue-50 p-4">
                <div class="flex">
                  <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Ready for Bill of Lading</h3>
                    <div class="mt-2 text-sm text-blue-700">
                      <p>All information has been verified. You can now generate the Bill of Lading.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>
</x-app-layout>

<script>
function updateContainerCounts(containerType, change) {
    // Update dropdown option
    const option = document.querySelector(`option[value="${containerType}"]`);
    if (option) {
        const available = parseInt(option.dataset.available) - change;
        option.dataset.available = available;
        option.textContent = `${option.textContent.split('(')[0].trim()} (${available} available)`;
        
        // Remove option if no more containers available
        if (available <= 0) {
            option.remove();
        }
    }

    // Update allocation info
    const allocationInfo = document.querySelector(`p[data-container-type="${containerType}"]`);
    if (allocationInfo) {
        const strong = allocationInfo.querySelector('strong');
        const total = allocationInfo.dataset.total;
        const available = parseInt(strong.dataset.available) - change;
        strong.dataset.available = available;
        strong.textContent = `${available} of ${total} available`;
        
        // Update color based on availability
        allocationInfo.className = `text-sm ${available > 0 ? 'text-blue-700' : 'text-red-700'}`;
    }
}

function addContainers() {
    let textarea = document.getElementById("container_list");
    let containerTypeSelect = document.getElementById("container_type");
    let containerType = containerTypeSelect.value;
    let containerTypeText = containerTypeSelect.options[containerTypeSelect.selectedIndex].text;
    let containerSection = document.getElementById("container-sections");

    if (!containerType) {
        alert("Please select a container type first.");
        return;
    }

    let lines = textarea.value.trim().split("\n");
    let containers = [];

    lines.forEach(line => {
        let parts = line.split(",");
        if (parts.length === 2) {
            let containerNumber = parts[0].trim();
            let sealNumber = parts[1].trim();
            if (containerNumber && sealNumber) {
                containers.push({ container: containerNumber, seal: sealNumber });
            }
        }
    });

    if (containers.length === 0) {
        alert("Invalid format! Please enter containers in the format: CONTAINER,SEAL");
        return;
    }

    // Check available count
    const option = containerTypeSelect.selectedOptions[0];
    const availableCount = parseInt(option.dataset.available);
    if (containers.length > availableCount) {
        alert(`Only ${availableCount} containers available for this type`);
        return;
    }

    // Check if a section for the selected container type already exists
    let existingSection = document.querySelector(`div[data-container-type="${containerType}"]`);
    let containerGroup;
    
    if (!existingSection) {
        // Create new section
        existingSection = document.createElement("div");
        existingSection.setAttribute("data-container-type", containerType);
        existingSection.classList.add("border", "rounded-md", "p-4");
        
        // Create container group div first
        containerGroup = document.createElement("div");
        containerGroup.id = `container-group-${containerType}`;
        containerGroup.classList.add("space-y-3");
        
        // Set up the section HTML structure
        existingSection.innerHTML = `
            <h3 class="font-medium mb-3">${containerTypeText.split('(')[0].trim()}</h3>
        `;
        
        // Append the container group to the section
        existingSection.appendChild(containerGroup);
        
        // Add the section to the main container sections area
        containerSection.appendChild(existingSection);
    } else {
        // Get existing container group
        containerGroup = existingSection.querySelector(`#container-group-${containerType}`);
    }

    // Now add the containers
    containers.forEach((entry, index) => {
        let div = document.createElement("div");
        div.classList.add("flex", "items-center", "gap-x-4", "w-full");

        // Get the current count of containers for this type
        const existingCount = containerGroup.children.length;
        
        div.innerHTML = `
            <input type="hidden" name="containers[${containerType}][${existingCount}][container_type]" value="${containerType}">
            <input type="text" name="containers[${containerType}][${existingCount}][container_number]" value="${entry.container}" 
                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 sm:text-sm/6">
            <input type="text" name="containers[${containerType}][${existingCount}][seal_number]" value="${entry.seal}" 
                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 sm:text-sm/6">
            <button type="button" class="text-gray-400 hover:text-red-600" onclick="removeContainer(this)">
                <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                </svg>
            </button>
        `;

        containerGroup.appendChild(div);
    });

    // Update counts after adding containers
    updateContainerCounts(containerType, containers.length);

    // Clear textarea after processing
    textarea.value = "";
}

function removeContainer(button) {
    const containerItem = button.closest('.flex');
    if (containerItem) {
        // Get container type before removing the element
        const containerType = containerItem.querySelector('input[type="hidden"]').value;
        
        containerItem.remove();
        
        // Check if the container group is empty
        const containerSection = document.querySelector(`div[data-container-type="${containerType}"]`);
        const containerGroup = containerSection.querySelector(`#container-group-${containerType}`);
        
        // If no more items, remove the entire container section
        if (containerGroup && containerGroup.children.length === 0) {
            containerSection.remove();
        }

        // Update counts after removing container
        updateContainerCounts(containerType, -1);
    }
}

// Add this helper function for text selection in querySelector
document.querySelector = ((function(original) {
    return function(selector) {
        if (selector.includes(':contains')) {
            const [tag, text] = selector.split(':contains(');
            const cleanText = text.slice(0, -1);
            const elements = document.getElementsByTagName(tag || '*');
            
            for (const element of elements) {
                if (element.textContent.includes(cleanText)) {
                    return element;
                }
            }
            return null;
        }
        return original.apply(this, arguments);
    };
})(document.querySelector));

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    // Remove e.preventDefault() to allow form submission
    
    const requiredFields = [
        'box_operator',
        'shipper',
        'contact_shipper',
        'consignee',
        'contact_consignee',
        'notify_party',
        'notify_party_contact',
        'notify_party_address',
        'cargo_description',
        'hs_code'
    ];

    let isValid = true;
    requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element.value.trim()) {
            isValid = false;
            element.classList.add('outline-red-500');
        } else {
            element.classList.remove('outline-red-500');
        }
    });

    if (!isValid) {
        e.preventDefault(); // Only prevent if validation fails
        alert('Please fill in all required fields');
    }
});
</script> 