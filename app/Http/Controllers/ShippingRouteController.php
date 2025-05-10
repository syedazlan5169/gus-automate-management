<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShippingRoute;
use App\Models\ActivityLog;

class ShippingRouteController extends Controller
{
    public function create()
    {
        return view('shipping-routes.create');
    }

    public function index()
    {
        return view('shipping-routes.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'place_of_receipt' => 'required',
            'pol' => 'required',
            'pod' => 'required',
            'place_of_delivery' => 'required',
        ]);
        $route_name = $request->origin . ' → ' . $request->destination;

        ShippingRoute::create([
            'route_name' => $route_name,
            'origin' => $request->origin,
            'destination' => $request->destination,
            'place_of_receipt' => $request->place_of_receipt,
            'pol' => $request->pol,
            'pod' => $request->pod,
            'place_of_delivery' => $request->place_of_delivery,
        ]);

        ActivityLog::logShippingRouteCreated($request->user());

        return redirect()->route('shipping-routes.index')->with('success', 'Shipping route created successfully');
    }

    public function edit(ShippingRoute $shippingRoute)
    {
        return view('shipping-routes.edit', compact('shippingRoute'));
    }

    public function update(Request $request, ShippingRoute $shippingRoute)
    {
        $shippingRoute->update($request->all());
        return redirect()->route('shipping-routes.index')->with('success', 'Shipping route updated successfully');
    }

    public function destroy(ShippingRoute $shippingRoute)
    {
        $shippingRoute->delete();
        return redirect()->route('shipping-routes.index')->with('success', 'Shipping route deleted successfully');
    }
}
