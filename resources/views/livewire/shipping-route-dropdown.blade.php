<div>
    <select id="route" name="route" wire:model="selectedRoute" 
        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
        onchange="handleRouteChange(this)">
        <option value="">Select Route</option>
        @if($shippingRoutes->count() > 0)
            @foreach ($shippingRoutes as $route)
                <option value="{{ $route->id }}" 
                    data-place-of-receipt="{{ $route->place_of_receipt }}"
                    data-pol="{{ $route->pol }}"
                    data-pod="{{ $route->pod }}"
                    data-place-of-delivery="{{ $route->place_of_delivery }}">
                    {{ $route->route_name }}
                </option>
            @endforeach
        @else
            <option value="" disabled>No shipping routes available</option>
        @endif
    </select>
</div>

<script>
function handleRouteChange(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    if (selectedOption && selectedOption.value) {
        const placeOfReceipt = selectedOption.getAttribute('data-place-of-receipt');
        const pol = selectedOption.getAttribute('data-pol');
        const pod = selectedOption.getAttribute('data-pod');
        const placeOfDelivery = selectedOption.getAttribute('data-place-of-delivery');
        
        console.log('Route selected via direct JS:', {
            placeOfReceipt,
            pol,
            pod,
            placeOfDelivery
        });
        
        // Populate the form fields
        const placeOfReceiptField = document.getElementById('place_of_receipt');
        const polField = document.getElementById('pol');
        const podField = document.getElementById('pod');
        const placeOfDeliveryField = document.getElementById('place_of_delivery');
        
        if (placeOfReceiptField) placeOfReceiptField.value = placeOfReceipt;
        if (polField) polField.value = pol;
        if (podField) podField.value = pod;
        if (placeOfDeliveryField) placeOfDeliveryField.value = placeOfDelivery;
    }
}
</script>

