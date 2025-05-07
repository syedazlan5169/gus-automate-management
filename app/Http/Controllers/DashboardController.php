<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        $bookings = Booking::all();
        $completedBookings = Booking::where('status', 7)->count();
        $ongoingBookings = Booking::where('status', '<', 7, 'and', '>', 0)->count();
        $cancelledBookings = Booking::where('status', 0)->count();
        return view('admin.dashboard', compact('bookings', 'completedBookings', 'ongoingBookings', 'cancelledBookings'));
    }

    public function clientDashboard()
    {
        return view('client.dashboard');
    }
    
}
