<?php

namespace App\Livewire\Admin;

use App\Enums\TransactionDirection;
use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\Wallet;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class AdminWalletTransactionTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public Wallet $wallet;

    // فیلترهای ورودی
    public $searchInput = '';

    public $typeInput = '';

    public $directionInput = '';

    public $createdDateJalali = '';

    // متغیرهای اعمالی روی کوئری
    public $search = '';

    public $type = '';

    public $direction = '';

    public $createdDate = '';

    public function mount(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    public function applySearch()
    {
        $this->search = trim($this->searchInput);
        $this->type = $this->typeInput;
        $this->direction = $this->directionInput;

        if (! empty($this->createdDateJalali)) {
            try {
                $this->createdDate = Jalalian::fromFormat('Y/n/j', $this->createdDateJalali)
                    ->toCarbon()
                    ->format('Y-m-d');
            } catch (\Exception $e) {
                $this->createdDate = '';
            }
        } else {
            $this->createdDate = '';
        }

        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset([
            'searchInput', 'search',
            'typeInput', 'type',
            'directionInput', 'direction',
            'createdDateJalali', 'createdDate',
        ]);
        $this->resetPage();
        $this->dispatch('filters-reset');
    }

    public function render()
    {
        $transactions = Transaction::query()
            ->where('wallet_id', $this->wallet->id)
            ->when($this->search, function ($q) {
                $q->where('track_number', 'like', "%{$this->search}%");
            })
            ->when($this->type !== '', function ($q) {
                $q->where('type', $this->type);
            })
            ->when($this->direction !== '', function ($q) {
                $q->where('direction', $this->direction);
            })
            ->when($this->createdDate, function ($q) {
                $q->whereDate('created_at', $this->createdDate);
            })
            ->latest('id')
            ->paginate(10);

        return view('livewire.admin.admin-wallet-transaction-table', [
            'transactions' => $transactions,
            'types' => TransactionType::cases(),
            'directions' => TransactionDirection::cases(),
        ]);
    }
}
