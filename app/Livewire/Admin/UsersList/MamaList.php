<?php

namespace App\Livewire\Admin\UsersList;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class MamaList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // input جستجو
    public $searchInput = '';

    public $search = '';

    public function applySearch()
    {
        $this->search = $this->searchInput;
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->with('roles')
            ->role('mama')
            ->when($this->search, function ($q) {
                $term = trim($this->search);

                $q->where(function ($sub) use ($term) {
                    $sub->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$term}%"])
                        ->orWhere('mobile', $term);
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.users-list.mama-list', compact('users'));
    }
}
