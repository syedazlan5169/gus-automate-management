<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ShippingRoute;
use Illuminate\Support\Facades\Log;

class ShippingRouteDropdown extends Component
{
    public $shippingRoutes;
    public $selectedRoute;

    public function mount()
    {
        $this->shippingRoutes = ShippingRoute::all();
        $this->selectedRoute = '';
    }

    public function updatedSelectedRoute($value)
    {
        $route = ShippingRoute::find($value);
        if ($route) {
            // Add logging to debug
            Log::info('Route selected: ' . $route->id, [
                'place_of_receipt' => $route->place_of_receipt,
                'pol' => $route->pol,
                'pod' => $route->pod,
                'place_of_delivery' => $route->place_of_delivery,
            ]);
            
            // Try both event dispatch methods
            $this->dispatch('routeSelected', [
                'place_of_receipt' => $route->place_of_receipt,
                'pol' => $route->pol,
                'pod' => $route->pod,
                'place_of_delivery' => $route->place_of_delivery,
            ]);
            
            // Also emit the event for older Livewire versions
            $this->emit('routeSelected', [
                'place_of_receipt' => $route->place_of_receipt,
                'pol' => $route->pol,
                'pod' => $route->pod,
                'place_of_delivery' => $route->place_of_delivery,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.shipping-route-dropdown');
    }
}
