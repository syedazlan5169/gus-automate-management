<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;
use App\Models\BookingStatus;
use Livewire\WithPagination;

class BookingsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $status = '';
    public $sortBy = 'created_at';
    public $sortDir = 'DESC';

    public function mount()
    {
        // Get status from URL if present
        if (request()->has('status')) {
            $this->status = request()->get('status');
        }

        if(auth()->user()->role === 'customer')
        {
            $this->bookings = Booking::where('user_id', auth()->user()->id)->get();
        }
        else
        {
            $this->bookings = Booking::all();
        }
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

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage(); 
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Booking::search($this->search);
        
        // Filter by user_id for customers
        if(auth()->user()->role === 'customer')
        {
            $query->where('user_id', auth()->user()->id);
        }
        
        // Apply status filter if selected
        if ($this->status !== '') {
            if ($this->status === 'ongoing') {
                // For ongoing, show all bookings that are not completed or cancelled
                $query->whereNotIn('status', [BookingStatus::COMPLETED, BookingStatus::CANCELLED]);
            } else {
                $query->where('status', $this->status);
            }
        }
        
        $bookings = $query->with(['voyage', 'user'])->orderBy($this->sortBy, $this->sortDir)->paginate($this->perPage);
        
        // Get status labels for each booking
        $statusLabels = [];
        foreach ($bookings as $booking) {
            $statusLabels[$booking->id] = BookingStatus::labels($booking->status)[$booking->status] ?? 'Unknown';
        }
        
        return view('livewire.bookings-table', [
            'bookings' => $bookings,
            'statusLabels' => $statusLabels,
        ]);
    }
    
    /**
     * Get the invoice status label for a booking
     * 
     * @param \App\Models\Booking $booking
     * @return string
     */
    public function getInvoiceStatusLabel($booking)
    {
        if (!$booking->invoice) {
            return 'No Invoice';
        }
        
        // You can customize this based on your invoice status values
        $status = $booking->invoice->status;
        
        // Example status labels - adjust according to your actual status values
        $labels = [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'overdue' => 'Overdue',
            'cancelled' => 'Cancelled',
            // Add more status labels as needed
        ];
        
        return $labels[$status] ?? ucfirst($status);
    }
}
