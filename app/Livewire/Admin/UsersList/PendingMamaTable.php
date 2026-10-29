<?php

namespace App\Livewire\Admin\UsersList;

use App\Enums\MamaInformationStatus;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class PendingMamaTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // فیلترهای ورودی
    public $searchInput = '';
    public $documentStatusInput = '';

    // فیلترهای اعمال‌شده روی کوئری
    public $search = '';
    public $documentStatus = '';

    public function applySearch()
    {
        $this->search = trim($this->searchInput);
        $this->documentStatus = $this->documentStatusInput;
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['searchInput', 'search', 'documentStatusInput', 'documentStatus']);
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->with(['roles', 'mamaInformation'])
            ->role('pending-mama')
            // فیلتر متنی نام یا موبایل
            ->when($this->search, function ($q) {
                $term = $this->search;
                $q->where(function ($sub) use ($term) {
                    $sub->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$term}%"])
                        ->orWhere('mobile', $term);
                });
            })
            // فیلتر وضعیت مدارک در رابطه mamaInformation
            ->when($this->documentStatus !== '', function ($q) {
                $q->whereHas('mamaInformation', function ($sub) {
                    $sub->where('status', $this->documentStatus);
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.users-list.pending-mama-table', [
            'users' => $users,
            'documentStatuses' => MamaInformationStatus::cases(),
        ]);
    }
}
