<x-app-layout>
    <div class="py-8 max-w-3xl mx-auto">
        <h2 class="text-2xl font-semibold mb-1">Edit Approved Fields</h2>
        <p class="text-sm text-gray-600 mb-6">
            Only the fields approved by the administrator are editable. Others are shown as read-only.
        </p>

        <div class="rounded-md bg-yellow-50 border border-yellow-200 p-3 mb-6 text-sm text-yellow-900">
            Telex BL has been released. Changes may incur an additional fee. Your request status is
            <strong class="uppercase">{{ str_replace('_',' ', $changeRequest->status) }}</strong>.
        </div>

        {{-- display SI fields --}}
        <form method="POST" action="{{ route('si-change-requests.submit-edits', [$si, $changeRequest]) }}">
            @csrf
            <div class="space-y-5 bg-white rounded-lg shadow p-6">
                @foreach($fieldLabels as $name => $label)
                    @php $editable = in_array($name, $approvedFields, true); @endphp
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>

                        @if(in_array($name, ['shipper_address','consignee_address','notify_party_address'], true))
                            <textarea
                                name="{{ $name }}"
                                class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                rows="3"
                                @disabled(!$editable)
                            >{{ is_array($si->{$name}) ? implode("\n", array_filter($si->{$name})) : ($si->{$name} ?? '') }}</textarea>
                        @else
                            <input type="text"
                                name="{{ $name }}"
                                class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                value="{{ old($name, $si->{$name}) }}"
                                @disabled(!$editable)
                            />
                        @endif

                        @unless($editable)
                            <p class="mt-1 text-xs text-gray-500">Not approved for edit</p>
                        @endunless
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('booking.show', $booking) }}"
                class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    Back
                </a>
                <button type="submit"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    Submit for Final Review
                </button>
            </div>
        </form>

    </div>
</x-app-layout>

