<?php

namespace App\Livewire\Admin;

use Coderflex\LaravelTicket\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class AdminTicketTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // فیلترها
    public $searchInput = '';

    public $selectedUserId = '';

    public $selectedMamaId = '';

    public $statusInput = '';

    public $isResolvedInput = '';

    public $dateInput = '';

    // متغیرهای کوئری
    public $search;

    public $userId;

    public $mamaId;

    public $status;

    public $isResolved;

    public $date;

    public function applySearch()
    {
        $this->search = trim($this->searchInput);
        $this->userId = $this->selectedUserId;
        $this->mamaId = $this->selectedMamaId;
        $this->status = $this->statusInput;
        $this->isResolved = $this->isResolvedInput;

        if (! empty($this->dateInput)) {
            $this->date = Jalalian::fromFormat('Y/n/j', $this->dateInput)->toCarbon()->format('Y-m-d');
        }

        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['searchInput', 'search', 'selectedUserId', 'userId', 'selectedMamaId', 'mamaId', 'statusInput', 'status', 'isResolvedInput', 'isResolved', 'dateInput', 'date']);
        $this->resetPage();
        $this->dispatch('filters-reset');
    }

    public function render()
    {
        $tickets = Ticket::query()
            ->with(['user', 'mama'])
            ->when($this->search, fn ($q) => $q->where('track_number', 'like', "%{$this->search}%"))
            ->when($this->userId, fn ($q) => $q->where('user_id', $this->userId))
            ->when($this->mamaId, fn ($q) => $q->where('mama_id', $this->mamaId))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->isResolved !== '', fn ($q) => $q->where('is_resolved', $this->isResolved))
            ->when($this->date, fn ($q) => $q->whereDate('created_at', $this->date))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.admin-ticket-table', compact('tickets'));
    }
}
