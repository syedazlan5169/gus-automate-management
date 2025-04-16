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
    public $sortBy = 'booking_date';
    public $sortDir = 'DESC';

    public function mount()
    {
        $this->bookings = Booking::all();
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
        
        // Apply status filter if selected
        if ($this->status !== '') {
            $query->where('status', $this->status);
        }
        
        $bookings = $query->orderBy($this->sortBy, $this->sortDir)->paginate($this->perPage);
        
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
}
