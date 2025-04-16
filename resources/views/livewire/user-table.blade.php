<div>
<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <div class="w-full">
        <!-- Search and Per Page Controls -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-2 sm:space-y-0">
            <div class="w-full sm:w-64">
                <input wire:model.live="search" type="text" placeholder="Search users..." 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create User
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="mx-4 mt-3 ring-1 ring-gray-300 sm:mx-0 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-300">
                  <thead>
                    <tr>
                        <th scope="col" class="text-center hidden px-3 py-3.5 text-sm font-semibold text-gray-900 lg:table-cell">
                            Name
                        </th>
                        <th scope="col" class="text-center hidden px-3 py-3.5 text-sm font-semibold text-gray-900 lg:table-cell">
                            Email
                        </th>
                        <th scope="col" class="text-center hidden px-3 py-3.5 text-sm font-semibold text-gray-900 lg:table-cell">
                            Company
                        </th>
                        <th scope="col" class="text-center hidden px-3 py-3.5 text-sm font-semibold text-gray-900 lg:table-cell">
                            Role
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
                    @foreach($users as $user)
                    <tr>
                      <td class="relative py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                            <span>Email : {{ $user->email }}</span>
                        </div>
                        <div class="mt-1 flex flex-col text-gray-500 sm:block lg:hidden">
                            <span>Company : {{ $user->company_name }}</span>
                          <span class="hidden sm:inline">·</span>
                            <span>Role : {{ $user->role }}</span>
                        </div>

                        </td>
                        <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">
                          <div class="mt-1 flex flex-col text-gray-500 sm:block">
                            <p>{{ $user->email }}</p>
                        </div>
                        </td>
                        <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">{{ $user->company_name }}</td>
                        <td class="text-center hidden px-3 py-3.5 text-sm text-gray-500 lg:table-cell">{{ $user->role }}</td>
                        <td class="text-center px-3 py-3.5 text-sm text-gray-500">
                          @php
                              $statusClass = 'bg-green-50 text-green-700 ring-green-600/20';
                              $statusText = 'Verified';
                              if (!$user->email_verified_at) {
                                  $statusClass = 'bg-red-50 text-red-700 ring-red-600/20';
                                  $statusText = 'Unverified';
                              }
                          @endphp
                          <div class="sm:hidden mt-0.5 whitespace-nowrap rounded-md {{ $statusClass }} px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset">
                            {{ $statusText }}
                          </div>
                          <div class="hidden sm:block mt-0.5 whitespace-nowrap rounded-md {{ $statusClass }} px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset">
                            {{ $statusText }}
                          </div>
                        </td>
                      <td class="relative py-3.5 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <a href="{{ route('users.show', $user) }}" 
                               class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">
                                View
                            </a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@foreach($users as $user)
    <x-modal name="confirm-user-deletion-{{ $user->id }}" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="delete({{ $user->id }})" class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete this user?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once this user is deleted, all of its resources and data will be permanently deleted.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ml-3">
                    {{ __('Delete User') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
@endforeach
</div>