<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
class UserTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'created_at';
    public $sortDir = 'DESC';

    public function mount()
    {
        $this->users = User::all();
    }

    public function setSortBy($sortByField)
    {
        if($this->sortBy === $sortByField)
        {
            $this->sortDir = ($this->sortDir == "ASC") ? 'DESC' : "ASC";
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
        $users = User::search($this->search)->orderBy($this->sortBy, $this->sortDir)->paginate($this->perPage);

        return view('livewire.user-table', [
            'users' => $users
        ]);
    }

    public function canViewUser($userRole)
    {
        $currentUserRole = auth()->user()->role;
        
        // Admin can view all roles
        if ($currentUserRole === 'admin') {
            return true;
        }
        
        // Manager can only view customer and manager roles
        if ($currentUserRole === 'manager') {
            return in_array($userRole, ['customer', 'manager']);
        }
        
        // Finance can only view customer and finance roles
        if ($currentUserRole === 'finance') {
            return in_array($userRole, ['customer', 'finance']);
        }
        
        // Customer can only view their own profile
        if ($currentUserRole === 'customer') {
            return false;
        }
        
        return false;
    }
}
