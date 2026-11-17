<?php

namespace App\Livewire\Admin;

use App\Enums\DoulaSubscriptionStatus;
use App\Models\DoulaSubscription;
use App\Models\Province;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class AdminDoulaSubscriptionTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // فیلترها
    public $searchInput = '';

    public $selectedUserId = '';

    public $selectedMamaId = '';

    public $statusInput = '';

    public $dateInput = '';
    public $provinceInput = '';
    public $cityInput = '';

    // متغیرهای کوئری
    public $search;

    public $userId;

    public $mamaId;

    public $status;

    public $date;
    public $provinceId;
    public $cityId;
    public function applySearch()
    {
        $this->search = trim($this->searchInput);
        $this->userId = $this->selectedUserId;
        $this->mamaId = $this->selectedMamaId;
        $this->status = $this->statusInput;
        $this->provinceId = $this->provinceInput;
        $this->cityId = $this->cityInput;

        if (! empty($this->dateInput)) {

            $this->date = Jalalian::fromFormat('Y/n/j', $this->dateInput)->toCarbon()->format('Y-m-d');
        }

        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['searchInput', 'search', 'selectedUserId', 'userId', 'selectedMamaId', 'mamaId', 'statusInput', 'status', 'dateInput', 'date',   'provinceInput', 'provinceId',
            'cityInput', 'cityId', ]);
        $this->resetPage();
        $this->dispatch('filters-reset');
    }

    public function render()
    {
        $statuses = DoulaSubscriptionStatus::cases();

        $provinces = Province::query()->get();

        $doulaSubscriptions = DoulaSubscription::query()
            ->with(['user', 'mama','province','city'])
            ->when($this->search, fn ($q) => $q->where('track_number', 'like', "%{$this->search}%"))
            ->when($this->userId, fn ($q) => $q->where('user_id', $this->userId))
            ->when($this->mamaId, fn ($q) => $q->where('mama_id', $this->mamaId))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->date, fn ($q) => $q->whereDate('created_at', $this->date))
            ->when($this->provinceId, fn ($q) => $q->where('province_id', $this->provinceId))
            ->when($this->cityId, fn ($q) => $q->where('city_id', $this->cityId))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.admin-doula-subscription-table', compact('doulaSubscriptions', 'statuses', 'provinces'));
    }
}
