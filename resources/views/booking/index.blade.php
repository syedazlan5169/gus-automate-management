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

    <!-- Success Message -->
    @if (session('success'))
        <x-alert-success :message="session('success')" />
    @endif

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
                        class="text-center hidden px-3 py-3.5 text-sm font-semibold text-gray-900 lg:table-cell">
                        Service
                      </th>
                      <th scope="col"
                        class="text-center hidden px-3 py-3.5 text-sm font-semibold text-gray-900 lg:table-cell">
                        Vessel |
                        Voyage
                      </th>
                      <th scope="col"
                        class="text-center hidden px-3 py-3.5 text-sm font-semibold text-gray-900 lg:table-cell">
                        Route
                      </th>
                      <th scope="col"
                        class="text-center hidden px-3 py-3.5 text-sm font-semibold text-gray-900 lg:table-cell">
                        Schedule
                      </th>
                      <th scope="col" class="text-center px-3 py-3.5 text-sm font-semibold text-gray-900">
                        Status
                      </th>
                      <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Action</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                    <tr>
                      <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                          <div class="font-medium text-gray-900">{{ $booking->booking_number }}</div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                            <span>{{ $booking->vessel }} | {{ $booking->voyage }}</span>
                          <span class="hidden sm:inline">·</span>
                            <span>{{ $booking->pol }} → {{ $booking->pod }}</span>
                        </div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                            <span>ETS : {{ $booking->ets->format('d-m-Y | g:i A') }}</span>
                          <span class="hidden sm:inline">·</span>
                            <span>ETA : {{ $booking->eta->format('d-m-Y | g:i A') }}</span>
                        </div>
                      </td>
                        <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">{{ $booking->service }}</td>
                        <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">{{ $booking->vessel }} | {{ $booking->voyage }}</td>
                        <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">{{ $booking->pol }} → {{ $booking->pod }}</td>
                      <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">
                          <div class="mt-1 flex flex-col text-gray-500 sm:block">
                            <p>ETS : {{ $booking->ets->format('d-m-Y | g:i A') }}</p>
                            <p>ETA : {{ $booking->eta->format('d-m-Y | g:i A') }}</p>
                        </div>
                      </td>
                      <td class="text-center px-3 py-3.5 text-sm text-gray-500">
                          <div class="sm:hidden mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                            {{ $booking->status }}
                          </div>
                          <div class="hidden sm:block mt-0.5 whitespace-nowrap rounded-md bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                            {{ $booking->status }}
                          </div>
                      </td>
                      <td class="relative py-3.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <a href="{{ route('booking.show', $booking) }}" 
                               class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">
                                View
                            </a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>

                <!-- Modal -->
                <div id="confirm-modal" class="relative z-10 hidden" aria-labelledby="modal-title"
                    role="dialog" aria-modal="true">
                    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

                    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                        <div
                            class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                            <div
                                class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
                                <div>
                                    <div
                                        class="mx-auto flex size-12 items-center justify-center rounded-full bg-green-100">
                                        <svg class="size-6 text-green-600" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            aria-hidden="true" data-slot="icon">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-5">
                                        <h3 class="text-base font-semibold text-gray-900"
                                            id="modal-title">{{ session('success') }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

              </div>
              <nav class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                <div class="hidden sm:block">
                  <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ $bookings->firstItem() }}</span>
                    to
                    <span class="font-medium">{{ $bookings->lastItem() }}</span>
                    of
                    <span class="font-medium">{{ $bookings->total() }}</span>
                    results
                  </p>
                </div>
                <div class="flex flex-1 justify-between sm:justify-end">
                  {{ $bookings->links() }}
                </div>
              </nav>

            </div>
          </div>
        </div>
      </main>
    </div>
</x-app-layout>

