<x-app-layout>
  <div class="mx-auto flex w-full max-w-10xl flex-col px-4 py-10 sm:px-6 lg:px-8">
    <!-- Header section -->
    <div class="max-w-xl pb-8 space-y-2">
      <!-- Breadcrumb -->
      {{ Breadcrumbs::render('users') }}

      <!-- Booking Heading -->
      <h1 id="create-booking-heading" class="text-3xl font-bold tracking-tight text-gray-900">Users Management</h1>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <x-alert-success :message="session('success')" />
    @endif

    <div class="flex items-start gap-x-8">
      <main class="flex-1">
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <livewire:user-table />
        </div>
      </main>
    </div>
</x-app-layout>