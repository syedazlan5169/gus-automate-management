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
    public $sortBy = 'booking_number';
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

    public function updatedSearch()
    {
        $this->resetPage(); 
    }

    public function render()
    {
        $bookings = Booking::search($this->search)->orderBy($this->sortBy, $this->sortDir)->paginate($this->perPage);
        
        // Get status labels for each booking
        $statusLabels = [];
        foreach ($bookings as $booking) {
            $statusLabels[$booking->id] = BookingStatus::labels($booking->status)[$booking->status] ?? 'Unknown';
        }
        
        return view('livewire.bookings-table', [
            'bookings' => $bookings,
            'statusLabels' => $statusLabels
        ]);
    }
}
