<x-app-layout>
  <div class="mx-auto flex w-full max-w-10xl flex-col px-4 py-10 sm:px-6 lg:px-8">
    <!-- Header section -->
    <div class="max-w-xl pb-8 space-y-2">
      <!-- Breadcrumb -->
      {{ Breadcrumbs::render('shipping-instructions.show', $shippingInstruction) }}

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
                      <option value="OOCL" {{ $shippingInstruction->box_operator === 'OOCL' ? 'selected' : '' }}>OOCL</option>
                      <option value="EVERGREEN" {{ $shippingInstruction->box_operator === 'EVERGREEN' ? 'selected' : '' }}>EVERGREEN</option>
                      <option value="NAVEGACION" {{ $shippingInstruction->box_operator === 'NAVEGACION' ? 'selected' : '' }}>NAVEGACION</option>
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
                    <x-input-label for="shipper_contact">Shipper Contact</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="shipper_contact" id="shipper_contact" value="{{ $shippingInstruction->shipper_contact }}" required/>
                    </div>
                  </div>
                </div>

                <div class="sm:col-span-full">
                  <x-input-label for="shipper_address">Shipper Address</x-input-label>
                  <div class="mt-2 border rounded-md overflow-hidden">
                      <div class="flex flex-col w-full rounded-md bg-white outline outline-1 -outline-offset-1 outline-gray-300">
                          <input type="text" name="shipper_address[line1]" id="shipper_address_line1" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" value="{{ $shippingInstruction->shipper_address['line1'] }}" required />
                          <input type="text" name="shipper_address[line2]" id="shipper_address_line2" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" value="{{ $shippingInstruction->shipper_address['line2'] }}" />
                          <input type="text" name="shipper_address[line3]" id="shipper_address_line3" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" value="{{ $shippingInstruction->shipper_address['line3'] }}" />
                          <input type="text" name="shipper_address[line4]" id="shipper_address_line4" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" value="{{ $shippingInstruction->shipper_address['line4'] }}" />
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
                    <x-input-label for="consignee_contact">Consignee Contact</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="consignee_contact" id="consignee_contact" value="{{ $shippingInstruction->consignee_contact }}" required/>
                    </div>
                  </div>
                </div>

                <div class="sm:col-span-full">
                  <x-input-label for="consignee_address">Consignee Address</x-input-label>
                  <div class="mt-2 border rounded-md overflow-hidden">
                      <div class="flex flex-col w-full rounded-md bg-white outline outline-1 -outline-offset-1 outline-gray-300">
                          <input type="text" name="consignee_address[line1]" id="consignee_address_line1" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" value="{{ $shippingInstruction->consignee_address['line1'] }}" required />
                          <input type="text" name="consignee_address[line2]" id="consignee_address_line2" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" value="{{ $shippingInstruction->consignee_address['line2'] }}" />
                          <input type="text" name="consignee_address[line3]" id="consignee_address_line3" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" value="{{ $shippingInstruction->consignee_address['line3'] }}" />
                          <input type="text" name="consignee_address[line4]" id="consignee_address_line4" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" value="{{ $shippingInstruction->consignee_address['line4'] }}" />
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
                    <div class="mt-2 border rounded-md overflow-hidden">
                        <div class="flex flex-col w-full rounded-md bg-white outline outline-1 -outline-offset-1 outline-gray-300">
                            <input type="text" name="notify_party_address[line1]" id="notify_party_address_line1" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" value="{{ $shippingInstruction->notify_party_address['line1'] }}" required />
                            <input type="text" name="notify_party_address[line2]" id="notify_party_address_line2" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" value="{{ $shippingInstruction->notify_party_address['line2'] }}" />
                            <input type="text" name="notify_party_address[line3]" id="notify_party_address_line3" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" value="{{ $shippingInstruction->notify_party_address['line3'] }}" />
                            <input type="text" name="notify_party_address[line4]" id="notify_party_address_line4" class="rounded-none border-0 focus:ring-0 focus:outline-none shadow-none text-sm" value="{{ $shippingInstruction->notify_party_address['line4'] }}" />
                        </div>
                    </div>
                  </div>
                </div>

                <!-- Cargo Details -->
                <h2 class="text-base/7 font-semibold text-gray-900">Cargo Details</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-4">
                  <div class="sm:col-span-1">
                    <x-input-label for="cargo_description">Cargo Description</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="cargo_description" id="cargo_description" value="{{ $shippingInstruction->cargo_description }}" required/>
                    </div>
                  </div>

                  <div class="sm:col-span-1">
                    <x-input-label for="hs_code">HS Code</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="hs_code" id="hs_code" value="{{ $shippingInstruction->hs_code }}" required/>
                    </div>
                  </div>

                  <div class="sm:col-span-1">
                    <x-input-label for="gross_weight">Gross Weight</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="gross_weight" id="gross_weight" value="{{ $shippingInstruction->gross_weight }}" required/>
                    </div>
                  </div>

                  <div class="sm:col-span-1">
                    <x-input-label for="volume">Volume</x-input-label>
                    <div class="mt-2">
                      <x-text-input type="text" name="volume" id="volume" value="{{ $shippingInstruction->volume }}" />
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
                                        $availableCount = max(0, $cargo->container_count - $allocatedCount);
                                        $extraContainers = max(0, $allocatedCount - $cargo->container_count);
                                    @endphp
                                    <p class="text-sm {{ $availableCount > 0 ? 'text-blue-700' : 'text-red-700' }}" 
                                       data-container-type="{{ $cargo->id }}" 
                                       data-total="{{ $cargo->container_count }}">
                                        {{ $cargo->container_type }}: 
                                        @if ($extraContainers > 0)
                                            <strong data-available="{{ $availableCount }}">{{ $availableCount }} of {{ $cargo->container_count }} available ({{ $extraContainers }} extra in list)</strong>
                                        @else
                                            <strong data-available="{{ $availableCount }}">{{ $availableCount }} of {{ $cargo->container_count }} available</strong>
                                        @endif
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
                                    <option value="{{ $cargo->id }}" data-available="{{ $availableCount }}">
                                        {{ $cargo->container_type }}
                                    </option>
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
                                <!-- <div class="flex items-center justify-between">
                                    <label class="block text-sm font-medium text-gray-700">Or Upload Excel/CSV File</label>
                                    <a href="{{ route('shipping-instructions.download-template') }}" 
                                       class="text-sm text-indigo-600 hover:text-indigo-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10.75 2.75a.75.75 0 00-1.5 0v8.614L6.295 8.235a.75.75 0 10-1.09 1.03l4.25 4.5a.75.75 0 001.09 0l4.25-4.5a.75.75 0 00-1.09-1.03l-2.955 3.129V2.75z" />
                                            <path d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z" />
                                        </svg>
                                        Download Template
                                    </a>
                                </div> -->
                                <div class="flex items-center space-x-4">
                                    <!-- <div class="flex-1">
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
                                    </div> -->
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
                                            Total: <span class="container-count">{{ $containers->count() }}</span>
                                        </span>
                                    </h3>
                                </div>
                                <div class="px-4 py-3">
                                    <div id="container-group-{{ $containers->first()->cargo_id }}" class="space-y-3 max-h-[400px] overflow-y-auto">
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
                </div>
              </div>
            </div>
          </div>
        </form>
      </main>
      
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
        option.textContent = `${option.textContent.split('(')[0].trim()}`;
    }

    // Update allocation info
    const allocationInfo = document.querySelector(`p[data-container-type="${containerType}"]`);
    if (allocationInfo) {
        const strong = allocationInfo.querySelector('strong');
        const total  = allocationInfo.dataset.total;

        // work out the new available count
        let available = parseInt(strong.dataset.available) - change;
        let extra     = 0;

        if (available < 0) {          // we’ve over-allocated
            extra     = -available;   // convert to positive “extra”
            available = 0;            // don’t display a negative number
        }

        // persist the baseline for the next call
        strong.dataset.available = available;

        // visible text
        strong.textContent = extra
            ? `${available} of ${total} available (${extra} extra in list)`
            : `${available} of ${total} available`;

        // colour
        allocationInfo.className = `text-sm ${available > 0 ? 'text-blue-700' : 'text-red-700'}`;
    }

    // Update the container count display
    const containerGroup = document.querySelector(`#container-group-${containerType}`);
    if (containerGroup) {
        const countElement = document.querySelector(`#count-${containerType} .container-count`);
        if (countElement) {
            const currentCount = containerGroup.children.length;
            countElement.textContent = currentCount;
        }
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
        containerGroup.classList.add("space-y-3", "max-h-[400px]", "overflow-y-auto");
        
        // Set up the section HTML structure
        existingSection.innerHTML = `
            <div class="px-4 py-3 border-b">
                <h3 class="text-base font-semibold text-gray-900">
                    ${containerTypeText.split('(')[0].trim()}
                    <span class="ml-2 text-sm font-normal text-gray-600" id="count-${containerType}">
                        Total: <span class="container-count">0</span>
                    </span>
                </h3>
            </div>
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
        'shipper_contact',
        'consignee',
        'consignee_contact',
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