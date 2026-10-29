<?php

namespace App\Livewire\Admin;

use App\Models\Plan;
use Livewire\Component;
use Livewire\WithPagination;

class AdminPlanTable extends Component
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
        $plans = Plan::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('slug', 'like', '%'.$this->search.'%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.admin-plan-table', compact('plans'));
    }
}
