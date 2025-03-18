<x-app-layout>
  <div class="mx-auto flex w-full max-w-10xl flex-col px-4 py-10 sm:px-6 lg:px-8">
    <!-- Header section -->
    <div class="max-w-xl pb-8 space-y-2">
      <!-- Breadcrumb -->
      <nav class="flex" aria-label="Breadcrumb">
        <ol role="list" class="flex items-center space-x-4">
          <li>
            <div>
              <a href="#" class="text-gray-400 hover:text-gray-500">
                <svg class="size-5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                  data-slot="icon">
                  <path fill-rule="evenodd"
                    d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z"
                    clip-rule="evenodd" />
                </svg>
                <span class="sr-only">Home</span>
              </a>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <svg class="size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                data-slot="icon">
                <path fill-rule="evenodd"
                  d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                  clip-rule="evenodd" />
              </svg>
              <a href="#" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Projects</a>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <svg class="size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                data-slot="icon">
                <path fill-rule="evenodd"
                  d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                  clip-rule="evenodd" />
              </svg>
              <a href="#" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700" aria-current="page">Project
                Nero</a>
            </div>
          </li>
        </ol>
      </nav>

      <!-- Shipping Instruction Heading -->
      <h1 id="create-booking-heading" class="text-3xl font-bold tracking-tight text-gray-900">Create / View Shipping
        Instruction</h1>
      <!-- For admin, View Shipping Instruction, for user, Create Shipping Instruction -->
    </div>

    <!-- Content section with flex layout -->
    <div class="flex items-start gap-x-8">
      <main class="flex-1">
        <form class="space-y-6">
          <!-- First container section -->
          <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
              <div class="space-y-6">
                <!-- Shipper Information -->
                <h2 class="text-base/7 font-semibold text-gray-900">Shipper Information</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-3">
                    <label for="first-name" class="block text-sm/6 font-medium text-gray-900">Shipper Name</label>
                    <div class="mt-2">
                      <input type="text" name="first-name" id="first-name" autocomplete="given-name"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                  </div>

                  <div class="sm:col-span-3">
                    <label for="last-name" class="block text-sm/6 font-medium text-gray-900">Shipper Contact</label>
                    <div class="mt-2">
                      <input type="text" name="last-name" id="last-name" autocomplete="family-name"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                  </div>
                </div>

                <!-- Consignee Information -->
                <h2 class="text-base/7 font-semibold text-gray-900">Consignee Information</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-3">
                    <label for="first-name" class="block text-sm/6 font-medium text-gray-900">Consignee Name</label>
                    <div class="mt-2">
                      <input type="text" name="first-name" id="first-name" autocomplete="given-name"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                  </div>

                  <div class="sm:col-span-3">
                    <label for="last-name" class="block text-sm/6 font-medium text-gray-900">Consignee Contact</label>
                    <div class="mt-2">
                      <input type="text" name="last-name" id="last-name" autocomplete="family-name"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                  </div>
                </div>

                <!-- Notify Party Information -->
                <h2 class="text-base/7 font-semibold text-gray-900">Notify Party Information</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-3">
                    <label for="first-name" class="block text-sm/6 font-medium text-gray-900">Notify Party Name</label>
                    <div class="mt-2">
                      <input type="text" name="first-name" id="first-name" autocomplete="given-name"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                  </div>

                  <div class="sm:col-span-3">
                    <label for="last-name" class="block text-sm/6 font-medium text-gray-900">Notify Party
                      Contact</label>
                    <div class="mt-2">
                      <input type="text" name="last-name" id="last-name" autocomplete="family-name"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                  </div>
                </div>

                <!-- Cargo Description -->
                <h2 class="text-base/7 font-semibold text-gray-900">Notify Party Address</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-1">
                  <div class="sm:col-span-1">
                    <div class="mt-2">
                      <textarea rows="4" name="cargo-description" id="cargo-description"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
                    </div>
                  </div>
                </div>

                <!-- Cargo Description -->
                <h2 class="text-base/7 font-semibold text-gray-900">Cargo Description</h2>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-1">
                  <div class="sm:col-span-1">
                    <div class="mt-2">
                      <textarea rows="4" name="cargo-description" id="cargo-description"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
              <div class="space-y-6">
                <!-- Container Allocation -->
                <div class="border-b border-gray-900/10 space-y-6">
                  <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                      <h1 class="text-base font-semibold text-gray-900">Containers Allocation</h1>
                      <p class="text-sm text-red-700">Pending Allocation : <strong>100 containers remaining</strong>.
                      </p>
                    </div>
                    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                      <div class="sm:flex-auto">
                        <button type="button"
                          onclick="document.getElementById('si-upload-modal').classList.remove('hidden')"
                          class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 uppercase tracking-widest">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                          </svg>
                          Upload Details
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="border-t border-gray-200"></div>
                  <!-- Operator Section 1-->
                  <div id="operator-section-card">
                    <div class="sm:flex sm:items-center mt-4">
                      <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold text-gray-900">Box Operator : MAERSK</h1>
                      </div>
                      <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                        <div class="sm:flex-auto">
                          <p class="text-sm text-blue-700">Total Allocation : <strong>50 containers</strong>.
                          </p>
                        </div>
                      </div>
                    </div>

                    <!-- SI table list -->
                    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                      <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="min-w-full divide-y divide-gray-300">
                          <thead>
                            <tr>
                              <th scope="col"
                                class="w-1/2 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                Container Number</th>
                              <th scope="col" class="w-1/2 px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                Seal number</th>
                              <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">Edit</span>
                              </th>
                            </tr>
                          </thead>
                          <tbody class="divide-y divide-gray-200">
                            <tr>
                              <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <x-text-input name="container_count[]" type="number" min="1" class="" />
                              </td>
                              <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <x-text-input name="container_count[]" type="number" min="1" class="" />
                              </td>
                              <td
                                class="hidden text-center relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                <button type="button" class="text-red-600 hover:text-red-900">
                                  <span class="sr-only">Delete</span>
                                  <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                      d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                      clip-rule="evenodd" />
                                  </svg>
                                </button>
                              </td>
                              <td
                                class="text-center relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0 text-gray-400">
                                <button type="button" class="text-gray-400 hover:text-gray-500">
                                  <span class="sr-only">Add</span>
                                  <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path
                                      d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                  </svg>
                                </button>
                              </td>
                            </tr>
                            <tr>
                              <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <x-text-input name="container_count[]" type="number" min="1" class="" />
                              </td>
                              <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <x-text-input name="container_count[]" type="number" min="1" class="" />
                              </td>
                              <td
                                class="text-center relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                <button type="button" class="text-red-600 hover:text-red-900">
                                  <span class="sr-only">Delete</span>
                                  <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                      d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                      clip-rule="evenodd" />
                                  </svg>
                                </button>
                              </td>
                              <td
                                class="text-center relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0 text-gray-400">
                                <button type="button" class="text-gray-400 hover:text-gray-500">
                                  <span class="sr-only">Add</span>
                                  <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path
                                      d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                  </svg>
                                </button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <!-- Operator Section 2 -->
                  <div id="operator-section-card">
                    <div class="sm:flex sm:items-center mt-4">
                      <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold text-gray-900">Box Operator : MAERSK</h1>
                      </div>
                      <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                        <div class="sm:flex-auto">
                          <p class="text-sm text-blue-700">Total Allocation : <strong>50 containers</strong>.
                          </p>
                        </div>
                      </div>
                    </div>

                    <!-- SI table list -->
                    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                      <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="min-w-full divide-y divide-gray-300">
                          <thead>
                            <tr>
                              <th scope="col"
                                class="w-1/2 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                Container Number</th>
                              <th scope="col" class="w-1/2 px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                Seal number</th>
                              <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">Edit</span>
                              </th>
                            </tr>
                          </thead>
                          <tbody class="divide-y divide-gray-200">
                            <tr>
                              <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <x-text-input name="container_count[]" type="number" min="1" class="" />
                              </td>
                              <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                <x-text-input name="container_count[]" type="number" min="1" class="" />
                              </td>
                              <td
                                class="hidden text-center relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                <button type="button" class="text-red-600 hover:text-red-900">
                                  <span class="sr-only">Delete</span>
                                  <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                      d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                      clip-rule="evenodd" />
                                  </svg>
                                </button>
                              </td>
                              <td
                                class="text-center relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0 text-gray-400">
                                <button type="button" class="text-gray-400 hover:text-gray-500">
                                  <span class="sr-only">Add</span>
                                  <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path
                                      d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                  </svg>
                                </button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Action Buttons -->
          <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="px-4 py-5 sm:p-6">
              <div class="space-y-6">
                <div class="flex justify-between space-x-4">
                  <div>
                    <button type="button" onclick="window.history.back()"
                      class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                      Back
                    </button>
                  </div>
                  <div class="flex space-x-4">
                    <button type="submit"
                      class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                      Submit SI
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </main>


      <aside class="sticky top-8 hidden w-96 shrink-0 xl:block">
        <!-- Right column area -->
        <div class="overflow-hidden rounded-lg bg-white shadow">
          <div class="px-4 py-5 sm:p-6">
            <nav aria-label="Progress">
              <ol role="list" class="overflow-hidden">
                <li class="relative pb-10">
                  <div class="absolute left-4 top-4 -ml-px mt-0.5 h-full w-0.5 bg-indigo-600" aria-hidden="true"></div>
                  <!-- Complete Step -->
                  <a href="#" class="group relative flex items-start">
                    <span class="flex h-9 items-center">
                      <span
                        class="relative z-10 flex size-8 items-center justify-center rounded-full bg-indigo-600 group-hover:bg-indigo-800">
                        <svg class="size-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                          data-slot="icon">
                          <path fill-rule="evenodd"
                            d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                            clip-rule="evenodd" />
                        </svg>
                      </span>
                    </span>
                    <span class="ml-4 flex min-w-0 flex-col">
                      <span class="text-sm font-medium">Shipper Information</span>
                      <span class="text-sm text-gray-500">Provide shipper and consingnee details.</span>
                    </span>
                  </a>
                </li>
                <li class="relative">
                  <!-- Upcoming Step -->
                  <a href="#" class="group relative flex items-start">
                    <span class="flex h-9 items-center" aria-hidden="true">
                      <span
                        class="relative z-10 flex size-8 items-center justify-center rounded-full border-2 border-gray-300 bg-white group-hover:border-gray-400">
                        <span class="size-2.5 rounded-full bg-transparent group-hover:bg-gray-300"></span>
                      </span>
                    </span>
                    <span class="ml-4 flex min-w-0 flex-col">
                      <span class="text-sm font-medium text-indigo-600">Container Details</span>
                      <span class="text-sm text-gray-500">Provide container details (Box Operator, Container Size, HS
                        Code, Notify Party Address).</span>
                    </span>
                  </a>
                </li>

                <!-- class for current step. please hide once applied. -->
                <li class="relative pb-10">
                  <div class="absolute left-4 top-4 -ml-px mt-0.5 h-full w-0.5 bg-gray-300" aria-hidden="true"></div>
                  <!-- Current Step -->
                  <a href="#" class="group relative flex items-start" aria-current="step">
                    <span class="flex h-9 items-center" aria-hidden="true">
                      <span
                        class="relative z-10 flex size-8 items-center justify-center rounded-full border-2 border-indigo-600 bg-white">
                        <span class="size-2.5 rounded-full bg-indigo-600"></span>
                      </span>
                    </span>
                    <span class="ml-4 flex min-w-0 flex-col">

                    </span>
                  </a>
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </aside>
    </div>
  </div>


  <!-- Modal -->
  <div id="si-upload-modal" class="hidden relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true">
    </div>
    <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div
          class="z-50 relative w-full transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-5xl sm:p-6">
          <div>
            <div class="mt-3 sm:mt-5">
              <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Upload Container
                Details</h3>
              <div class="mt-4">
                <form class="space-y-4" id="upload-form" enctype="multipart/form-data" method="POST">
                  @csrf

                  <div class="space-y-12">

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                      <div class="sm:col-span-2">
                        <label for="container-size" class="block text-sm/6 font-medium text-gray-900">Container
                          Size</label>
                        <div class="mt-2 grid grid-cols-1">
                          <select id="container-size" name="container-size" autocomplete="container-size"
                            class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                            <option>20' Standard High Cube</option>
                            <option>40' High Cube</option>
                            <option>40' Flat</option>
                          </select>
                          <svg
                            class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                            viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                          </svg>
                        </div>
                      </div>

                      <div class="sm:col-span-2">
                        <label for="box-operator" class="block text-sm/6 font-medium text-gray-900">Box
                          Operator</label>
                        <div class="mt-2 grid grid-cols-1">
                          <select id="box-operator" name="box-operator" autocomplete="box-operator"
                            class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                            <option>MAERSK</option>
                            <option>CMA CGM</option>
                            <option>HAPPAG LLOYD</option>
                          </select>
                          <svg
                            class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                            viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                          </svg>
                        </div>
                      </div>

                      <div class="sm:col-span-2">
                        <label for="hscode" class="block text-sm/6 font-medium text-gray-900">HS Code</label>
                        <div class="mt-2 grid grid-cols-1">
                          <select id="hscode" name="hscode" autocomplete="hscode"
                            class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                            <option>20' Standard High Cube</option>
                            <option>40' High Cube</option>
                            <option>40' Flat</option>
                          </select>
                          <svg
                            class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                            viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                          </svg>
                        </div>
                      </div>
                    </div>

                    <!-- <div class="border-b border-gray-900/10 pb-12">
                      <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="col-span-full">
                          <label for="notify-party-address" class="block text-sm/6 font-medium text-gray-900">Notify
                            Party
                            Address</label>
                          <div class="mt-2">
                            <textarea name="notify-party-address" id="notify-party-address" rows="3"
                              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
                          </div>
                        </div>
                      </div>
                    </div> -->
                    <!-- File Upload -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Upload Excel File</label>
                      <div class="mt-1 flex items-center justify-center w-full">
                        <label
                          class="w-full flex flex-col items-center px-4 py-6 bg-white rounded-lg border-2 border-dashed border-gray-300 cursor-pointer hover:border-indigo-600">
                          <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                          </svg>
                          <span class="mt-2 text-sm text-gray-600">Click to upload or drag and
                            drop</span>
                          <input type="file" class="hidden" accept=".xlsx,.xls,.csv">
                        </label>
                      </div>
                    </div>



                    <!-- Template Download -->
                    <div class="flex justify-center">
                      <a href="#" class="text-sm text-indigo-600 hover:text-indigo-500">
                        Download template file
                      </a>
                    </div>
                </form>
              </div>
            </div>
          </div>
          <div class="mt-5 sm:mt-6 flex space-x-3">
            <button type="button" onclick="document.getElementById('si-upload-modal').classList.add('hidden')"
              class="inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
              Cancel
            </button>
            <button type="submit" form="upload-form"
              class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
              Upload
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add error message container -->
  <div id="error-message" class="mt-2 hidden text-red-600 text-sm"></div>

</x-app-layout>