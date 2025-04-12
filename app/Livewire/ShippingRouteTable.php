<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ShippingRoute;

class ShippingRouteTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'origin';
    public $sortDir = 'DESC';

    public function mount()
    {
        $this->shippingRoutes = ShippingRoute::all();
    }

    public function setSortBy($sortByField)
    {
        if($this->sortBy === $sortByField)
        {
            $this->sortDir = ($this->sortDir == "ASC") ? 'DESC' : "ASC";
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDir = 'DESC';
    }

    public function updatedSearch()
    {
        $this->resetPage(); 
    }

    public function render()
    {
        $shippingRoutes = ShippingRoute::search($this->search)->orderBy($this->sortBy, $this->sortDir)->paginate($this->perPage);
        return view('livewire.shipping-route-table', [
            'shippingRoutes' => $shippingRoutes
        ]);
    }

    public function delete($id)
    {
        $route = ShippingRoute::findOrFail($id);
        $route->delete();
        
        return redirect()->route('shipping-routes.index')->with('success', 'Shipping route deleted successfully');
    }
}
