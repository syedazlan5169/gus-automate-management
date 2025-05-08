<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\ActivityLog;
use App\Models\User;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        $bookings = Booking::all();
        $completedBookings = Booking::where('status', 7)->count();
        $ongoingBookings = Booking::where('status', '<', 7, 'and', '>', 0)->count();
        $cancelledBookings = Booking::where('status', 0)->count();

        $recentActivities = ActivityLog::orderBy('created_at', 'desc')->paginate(10);
        $recentBookings = Booking::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.dashboard', compact('bookings', 'completedBookings', 'ongoingBookings', 'cancelledBookings', 'recentActivities', 'recentBookings'));
    }

    public function clientDashboard()
    {
        return view('client.dashboard');
    }
    
}
