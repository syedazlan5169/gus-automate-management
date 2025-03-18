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
      <!-- Booking Heading -->
      <h1 id="create-booking-heading" class="text-3xl font-bold tracking-tight text-gray-900">My Bookings</h1>
    </div>
    <div class="flex items-start gap-x-8">
      <main class="flex-1">
        <div class="overflow-hidden rounded-lg bg-white shadow">
          <div class="px-4 py-5 sm:p-6">
            <div class="sm:flex sm:items-center">
              <div class="sm:flex-auto">
                <h1 class="text-base font-semibold text-gray-900">Viewing all bookings</h1>
              </div>
              <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none flex">
                <div class="grid grid-cols-1 px-3">
                  <input type="search" name="search" aria-label="Search"
                    class="col-start-1 row-start-1 block w-full rounded-md bg-white py-1.5 pl-10 pr-3 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                    placeholder="Search">
                  <svg class="pointer-events-none col-start-1 row-start-1 ml-3 size-5 self-center text-gray-400"
                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path fill-rule="evenodd"
                      d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                      clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
            </div>
            <div class="mx-4 mt-3 ring-1 ring-gray-300 sm:mx-0 sm:rounded-lg">
              <!-- <div class="max-h-[600px] overflow-y-auto"></div>   
             if want table to be scrollable vertically-->
              <div class="">
                <table class="min-w-full divide-y divide-gray-300">
                  <thead>
                    <tr>
                      <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                        Booking Number</th>
                      <th scope="col"
                        class="text-center hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                        Service
                      </th>
                      <th scope="col"
                        class="text-center hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                        Vessel |
                        Voyage
                      </th>
                      <th scope="col"
                        class="text-center hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                        Route
                      </th>
                      <th scope="col"
                        class="text-center hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                        Schedule
                      </th>
                      <th scope="col" class="text-center px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                        Status
                      </th>
                      <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Action</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    <tr>
                      <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div class="font-medium text-gray-900">BC000001</div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>GU Melur | VY0001</span>
                          <span class="hidden sm:inline">·</span>
                          <span>Singapore → Port Klang</span>
                        </div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>ETS : 12-03-2025 | 8:00 AM</span>
                          <span class="hidden sm:inline">·</span>
                          <span>ETA : 12-03-2025 | 8:00 AM</span>
                        </div>
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">SOC</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">GU Melur | VY0001
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">Singapore → Port
                        Klang</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">
                        <div class="mt-1 flex flex-col text-gray-500 sm:block ">
                          <p>ETS : 12-03-2025 | 8:00 AM</p>
                          <p>ETA : 12-03-2025 | 8:00 AM</p>
                        </div>
                      </td>
                      <!-- 
                      Status for customer
                      1. Pending SI
                      2. Draft
                      3. Processing
                      4. Sailing
                      5. Completed
                      6. Cancelled
                      https://tailwindui.com/components/application-ui/elements/badges
                      -->
                      <td class="text-center px-3 py-3.5 text-sm text-gray-500">
                        <div
                          class="sm:hidden mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Completed</div>
                        <div
                          class="hidden sm:block mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Completed</div>
                      </td>
                      <td class="relative py-3.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <!-- Dropdown Button -->
                        <button type="button" id="options-menu-0-button" aria-expanded="false" aria-haspopup="true"
                          class="inline-flex items-center rounded-md text-gray-700 hover:text-gray-900">
                          <span class="sr-only">Open options</span>
                          <!-- Three dots/ellipsis icon -->
                          <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                              d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                          </svg>
                        </button>

                        <!-- Dropdown Menu (hidden by default) -->
                        <div
                          class="text-start hidden absolute right-0 z-10 mt-2 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none"
                          role="menu" aria-orientation="vertical" aria-labelledby="options-menu-0-button" tabindex="-1">
                          <a href="{{ route('shipping-instructions.create-new-ui') }}"
                            class="block px-3 py-1 text-sm text-gray-900 hover:bg-gray-50" role="menuitem" tabindex="-1"
                            id="options-menu-0-item-0">
                            Update SI
                          </a>
                          <a href="/booking/1" class="block px-3 py-1 text-sm text-gray-900 hover:bg-gray-50"
                            role="menuitem" tabindex="-1" id="options-menu-0-item-1">
                            View Booking
                          </a>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div class="font-medium text-gray-900">BC000001</div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>GU Melur | VY0001</span>
                          <span class="hidden sm:inline">·</span>
                          <span>Singapore → Port
                            Klang</span>
                        </div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>ETS : 12-03-2025 | 8:00 AM</span>
                          <span class="hidden sm:inline">·</span>
                          <span>ETA : 12-03-2025 | 8:00 AM</span>
                        </div>
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">SOC</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">GU Melur | VY0001
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">Singapore → Port
                        Klang</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">
                        <div class="mt-1 flex flex-col text-gray-500 sm:block ">
                          <p>ETS : 12-03-2025 | 8:00 AM</p>
                          <p>ETA : 12-03-2025 | 8:00 AM</p>
                        </div>
                      </td>
                      <td class="text-center px-3 py-3.5 text-sm text-gray-500">
                        <div
                          class="sm:hidden mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                        <div
                          class="hidden sm:block mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                      </td>
                      <td class="relative py-3.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <a href="{{ route('shipping-instructions.create-new-ui') }}"
                          class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">
                          Create SI
                        </a>
                      </td>
                    </tr>
                    <!-- More plans... -->
                    <tr>
                      <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div class="font-medium text-gray-900">BC000001</div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>GU Melur | VY0001</span>
                          <span class="hidden sm:inline">·</span>
                          <span>Singapore → Port
                            Klang</span>
                        </div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>ETS : 12-03-2025 | 8:00 AM</span>
                          <span class="hidden sm:inline">·</span>
                          <span>ETA : 12-03-2025 | 8:00 AM</span>
                        </div>
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">SOC</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">GU Melur | VY0001
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">Singapore → Port
                        Klang</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">
                        <div class="mt-1 flex flex-col text-gray-500 sm:block ">
                          <p>ETS : 12-03-2025 | 8:00 AM</p>
                          <p>ETA : 12-03-2025 | 8:00 AM</p>
                        </div>
                      </td>
                      <td class="text-center px-3 py-3.5 text-sm text-gray-500">
                        <div
                          class="sm:hidden mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                        <div
                          class="hidden sm:block mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                      </td>
                      <td class="relative py-3.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <a href="{{ route('shipping-instructions.create-new-ui') }}"
                          class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">
                          Create SI
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div class="font-medium text-gray-900">BC000001</div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>GU Melur | VY0001</span>
                          <span class="hidden sm:inline">·</span>
                          <span>Singapore → Port
                            Klang</span>
                        </div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>ETS : 12-03-2025 | 8:00 AM</span>
                          <span class="hidden sm:inline">·</span>
                          <span>ETA : 12-03-2025 | 8:00 AM</span>
                        </div>
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">SOC</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">GU Melur | VY0001
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">Singapore → Port
                        Klang</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">
                        <div class="mt-1 flex flex-col text-gray-500 sm:block ">
                          <p>ETS : 12-03-2025 | 8:00 AM</p>
                          <p>ETA : 12-03-2025 | 8:00 AM</p>
                        </div>
                      </td>
                      <td class="text-center px-3 py-3.5 text-sm text-gray-500">
                        <div
                          class="sm:hidden mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                        <div
                          class="hidden sm:block mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                      </td>
                      <td class="relative py-3.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <a href="{{ route('shipping-instructions.create-new-ui') }}"
                          class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">
                          Create SI
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div class="font-medium text-gray-900">BC000001</div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>GU Melur | VY0001</span>
                          <span class="hidden sm:inline">·</span>
                          <span>Singapore → Port
                            Klang</span>
                        </div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>ETS : 12-03-2025 | 8:00 AM</span>
                          <span class="hidden sm:inline">·</span>
                          <span>ETA : 12-03-2025 | 8:00 AM</span>
                        </div>
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">SOC</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">GU Melur | VY0001
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">Singapore → Port
                        Klang</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">
                        <div class="mt-1 flex flex-col text-gray-500 sm:block ">
                          <p>ETS : 12-03-2025 | 8:00 AM</p>
                          <p>ETA : 12-03-2025 | 8:00 AM</p>
                        </div>
                      </td>
                      <td class="text-center px-3 py-3.5 text-sm text-gray-500">
                        <div
                          class="sm:hidden mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                        <div
                          class="hidden sm:block mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                      </td>
                      <td class="relative py-3.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <a href="{{ route('shipping-instructions.create-new-ui') }}"
                          class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">
                          Create SI
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div class="font-medium text-gray-900">BC000001</div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>GU Melur | VY0001</span>
                          <span class="hidden sm:inline">·</span>
                          <span>Singapore → Port
                            Klang</span>
                        </div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>ETS : 12-03-2025 | 8:00 AM</span>
                          <span class="hidden sm:inline">·</span>
                          <span>ETA : 12-03-2025 | 8:00 AM</span>
                        </div>
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">SOC</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">GU Melur | VY0001
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">Singapore → Port
                        Klang</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">
                        <div class="mt-1 flex flex-col text-gray-500 sm:block ">
                          <p>ETS : 12-03-2025 | 8:00 AM</p>
                          <p>ETA : 12-03-2025 | 8:00 AM</p>
                        </div>
                      </td>
                      <td class="text-center px-3 py-3.5 text-sm text-gray-500">
                        <div
                          class="sm:hidden mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                        <div
                          class="hidden sm:block mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                      </td>
                      <td class="relative py-3.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <button type="button"
                          class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">Update</button>
                      </td>
                    </tr>
                    <tr>
                      <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div class="font-medium text-gray-900">BC000001</div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>GU Melur | VY0001</span>
                          <span class="hidden sm:inline">·</span>
                          <span>Singapore → Port
                            Klang</span>
                        </div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>ETS : 12-03-2025 | 8:00 AM</span>
                          <span class="hidden sm:inline">·</span>
                          <span>ETA : 12-03-2025 | 8:00 AM</span>
                        </div>
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">SOC</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">GU Melur | VY0001
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">Singapore → Port
                        Klang</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">
                        <div class="mt-1 flex flex-col text-gray-500 sm:block ">
                          <p>ETS : 12-03-2025 | 8:00 AM</p>
                          <p>ETA : 12-03-2025 | 8:00 AM</p>
                        </div>
                      </td>
                      <td class="text-center px-3 py-3.5 text-sm text-gray-500">
                        <div
                          class="sm:hidden mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                        <div
                          class="hidden sm:block mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                      </td>
                      <td class="relative py-3.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <button type="button"
                          class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">Update</button>
                      </td>
                    </tr>
                    <tr>
                      <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div class="font-medium text-gray-900">BC000001</div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>GU Melur | VY0001</span>
                          <span class="hidden sm:inline">·</span>
                          <span>Singapore → Port
                            Klang</span>
                        </div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>ETS : 12-03-2025 | 8:00 AM</span>
                          <span class="hidden sm:inline">·</span>
                          <span>ETA : 12-03-2025 | 8:00 AM</span>
                        </div>
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">SOC</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">GU Melur | VY0001
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">Singapore → Port
                        Klang</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">
                        <div class="mt-1 flex flex-col text-gray-500 sm:block ">
                          <p>ETS : 12-03-2025 | 8:00 AM</p>
                          <p>ETA : 12-03-2025 | 8:00 AM</p>
                        </div>
                      </td>
                      <td class="text-center px-3 py-3.5 text-sm text-gray-500">
                        <div
                          class="sm:hidden mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                        <div
                          class="hidden sm:block mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                      </td>
                      <td class="relative py-3.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <button type="button"
                          class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">Update</button>
                      </td>
                    </tr>
                    <tr>
                      <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div class="font-medium text-gray-900">BC000001</div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>GU Melur | VY0001</span>
                          <span class="hidden sm:inline">·</span>
                          <span>Singapore → Port
                            Klang</span>
                        </div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                          <span>ETS : 12-03-2025 | 8:00 AM</span>
                          <span class="hidden sm:inline">·</span>
                          <span>ETA : 12-03-2025 | 8:00 AM</span>
                        </div>
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">SOC</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">GU Melur | VY0001
                      </td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">Singapore → Port
                        Klang</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">
                        <div class="mt-1 flex flex-col text-gray-500 sm:block ">
                          <p>ETS : 12-03-2025 | 8:00 AM</p>
                          <p>ETA : 12-03-2025 | 8:00 AM</p>
                        </div>
                      </td>
                      <td class="text-center px-3 py-3.5 text-sm text-gray-500">
                        <div
                          class="sm:hidden mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                        <div
                          class="hidden sm:block mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                          Pending SI</div>
                      </td>
                      <td class="relative py-3.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <button type="button"
                          class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">Update</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <nav class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6"
                aria-label="Pagination">
                <div class="hidden sm:block">
                  <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">1</span>
                    to
                    <span class="font-medium">10</span>
                    of
                    <span class="font-medium">20</span>
                    results
                  </p>
                </div>
                <div class="flex flex-1 justify-between sm:justify-end">
                  <a href="#"
                    class="relative inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0">Previous</a>
                  <a href="#"
                    class="relative ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-offset-0">Next</a>
                </div>
              </nav>

            </div>
          </div>
        </div>
      </main>
    </div>
</x-app-layout>

<script>
  document.getElementById('options-menu-0-button').addEventListener('click', function () {
    const dropdown = this.nextElementSibling;
    const isHidden = dropdown.classList.contains('hidden');

    // Toggle dropdown
    dropdown.classList.toggle('hidden');

    // Update aria-expanded
    this.setAttribute('aria-expanded', isHidden);

    // Close dropdown when clicking outside
    if (!isHidden) return;

    const closeDropdown = (e) => {
      if (!dropdown.contains(e.target) && !this.contains(e.target)) {
        dropdown.classList.add('hidden');
        this.setAttribute('aria-expanded', 'false');
        document.removeEventListener('click', closeDropdown);
      }
    };

    document.addEventListener('click', closeDropdown);
  });
</script>