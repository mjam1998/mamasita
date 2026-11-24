<?php

namespace App\Livewire\Admin;

use App\Enums\SubscriptionStatus;
use App\Models\MonthlySubscription;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class AdminMonthlySubscriptionTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // فیلترها
    public $searchInput = '';

    public $selectedUserId = '';

    public $selectedMamaId = '';

    public $statusInput = '';

    public $dateInput = '';

    public $monthPeriodInput = '';

    // متغیرهای کوئری
    public $search;

    public $userId;

    public $mamaId;

    public $status;

    public $date;

    public $monthPeriod;

    public function applySearch()
    {
        $this->search = trim($this->searchInput);
        $this->userId = $this->selectedUserId;
        $this->mamaId = $this->selectedMamaId;
        $this->status = $this->statusInput;
        $this->monthPeriod = $this->monthPeriodInput;

        if (! empty($this->dateInput)) {

            $this->date = Jalalian::fromFormat('Y/n/j', $this->dateInput)->toCarbon()->format('Y-m-d');
        }

        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['searchInput', 'search', 'selectedUserId', 'userId', 'selectedMamaId', 'mamaId', 'statusInput', 'status', 'dateInput', 'date',   'monthPeriod', 'monthPeriodInput',
        ]);
        $this->resetPage();
        $this->dispatch('filters-reset');
    }

    public function render()
    {
        $statuses = SubscriptionStatus::cases();

        $monthlySubscriptions = MonthlySubscription::query()
            ->with(['user', 'mama'])
            ->when($this->search, fn ($q) => $q->where('track_number', 'like', "%{$this->search}%"))
            ->when($this->userId, fn ($q) => $q->where('user_id', $this->userId))
            ->when($this->mamaId, fn ($q) => $q->where('mama_id', $this->mamaId))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->date, fn ($q) => $q->whereDate('created_at', $this->date))
            ->when($this->monthPeriod, fn ($q) => $q->where('month_period', $this->monthPeriod))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.admin-monthly-subscription-table', compact('monthlySubscriptions', 'statuses'));
    }
}
