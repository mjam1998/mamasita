<?php

namespace App\Livewire\Admin\UsersList;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class AllUsers extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // input جستجو
    public $searchInput = '';

    public $search = '';

    public $selectedRole = '';

    public function applySearch()
    {
        $this->search = $this->searchInput;
        $this->resetPage();
    }

    public function render()
    {
        $roles = Role::all();

        $users = User::query()
            ->with('roles')
            ->when($this->search, function ($q) {
                $term = trim($this->search);

                $q->where(function ($sub) use ($term) {
                    $sub->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$term}%"])
                        ->orWhere('mobile', $term);
                });
            })
            ->when($this->selectedRole, function ($q) {
                $q->whereHas('roles', fn ($roleQuery) => $roleQuery->where('name', $this->selectedRole));
            })
            ->latest()
            ->paginate(10);


        return view('livewire.admin.users-list.all-users', compact('users', 'roles'));
    }
}
