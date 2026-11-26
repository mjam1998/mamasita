<?php

namespace App\Livewire\Admin;

use App\Enums\CallStatus;
use App\Models\Call;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class AdminCallTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // فیلترها
    public $searchInput = '';
    public $trackNumber = '';

    public $selectedUserId = '';

    public $selectedMamaId = '';

    public $statusInput = '';

    public $dateInput = '';

    // متغیرهای کوئری
    public $search;

    public $userId;

    public $mamaId;

    public $status;

    public $date;

    public function mount(): void
    {
        if ($this->trackNumber) {
            $this->searchInput = $this->trackNumber;
            $this->search = $this->trackNumber;
        }
    }
    public function applySearch()
    {
        $this->search = trim($this->searchInput);
        $this->userId = $this->selectedUserId;
        $this->mamaId = $this->selectedMamaId;
        $this->status = $this->statusInput;


        if (! empty($this->dateInput)) {

            $this->date = Jalalian::fromFormat('Y/n/j', $this->dateInput)->toCarbon()->format('Y-m-d');
        }

        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['searchInput', 'search', 'selectedUserId', 'userId', 'selectedMamaId', 'mamaId', 'statusInput', 'status', 'dateInput', 'date',
        ]);
        $this->resetPage();
        $this->dispatch('filters-reset');
    }

    public function render()
    {
        $statuses = CallStatus::cases();

        $calls = Call::query()
            ->with(['monthlySubscription'])
            ->when($this->search, function ($q) {
                $q->whereHas('monthlySubscription', function ($sub) {
                    $sub->where('track_number', 'like', "%{$this->search}%");
                });
            })
            ->when($this->userId, function ($q) {
                $q->whereHas('monthlySubscription', function ($sub) {
                    $sub->where('user_id', $this->userId);
                });
            })
            ->when($this->mamaId, function ($q) {
                $q->whereHas('monthlySubscription', function ($sub) {
                    $sub->where('mama_id', $this->mamaId);
                });
            })
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->date, fn ($q) => $q->whereDate('created_at', $this->date))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.admin-call-table', compact('calls', 'statuses'));
    }
}
