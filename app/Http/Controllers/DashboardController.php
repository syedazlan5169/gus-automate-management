<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

        // Get monthly booking data for the current year
        $monthlyBookings = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Fill in missing months with 0
        $monthlyData = array_fill(1, 12, 0);
        foreach ($monthlyBookings as $month => $count) {
            $monthlyData[$month] = $count;
        }

        return view('admin.dashboard', compact(
            'bookings',
            'completedBookings',
            'ongoingBookings',
            'cancelledBookings',
            'recentActivities',
            'recentBookings',
            'monthlyData'
        ));
    }

    public function clientDashboard()
    {
        $user = Auth::user();
        $bookings = Booking::where('user_id', $user->id)->get();
        $completedBookings = $bookings->where('status', 7)->count();
        $ongoingBookings = $bookings->where('status', '<', 7, 'and', '>', 0)->count();
        $cancelledBookings = $bookings->where('status', 0)->count();

        return view('client.dashboard', compact(
            'bookings',
            'completedBookings',
            'ongoingBookings',
            'cancelledBookings',
        ));
    }
    
}
