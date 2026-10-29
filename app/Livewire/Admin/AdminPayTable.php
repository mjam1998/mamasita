<?php

namespace App\Livewire\Admin;

use App\Enums\AdminPayStatus;
use App\Models\AdminPay;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class AdminPayTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // متغیرهای فیلترهای ورودی
    public $searchInput = '';       // کد پیگیری
    public $selectedUserId = '';    // شناسه کاربر از Choices.js
    public $paidDateJalali = '';    // تاریخ شمسی انتخابی
    public $statusInput = '';       // وضعیت انتخابی

    // متغیرهای اعمال‌شده روی کوئری
    public $search = '';
    public $userId = '';
    public $paidDate = '';          // تاریخ میلادی تبدیل‌شده
    public $status = '';

    public function applySearch()
    {
        $this->search = trim($this->searchInput);
        $this->userId = $this->selectedUserId;
        $this->status = $this->statusInput;

        // تبدیل تاریخ شمسی به میلادی
        if (! empty($this->paidDateJalali)) {
            try {
                $this->paidDate = Jalalian::fromFormat('Y/m/d', $this->paidDateJalali)
                    ->toCarbon()
                    ->format('Y-m-d');
            } catch (\Exception $e) {
                $this->paidDate = '';
            }
        } else {
            $this->paidDate = '';
        }

        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset([
            'searchInput', 'search',
            'selectedUserId', 'userId',
            'paidDateJalali', 'paidDate',
            'statusInput', 'status'
        ]);
        $this->resetPage();
        $this->dispatch('filters-reset');
    }

    public function render()
    {
        $adminPays = AdminPay::query()
            ->with('user')
            // فیلتر کد پیگیری
            ->when($this->search, function ($q) {
                $q->where('track_number', 'like', "%{$this->search}%");
            })
            // فیلتر کاربر
            ->when($this->userId, function ($q) {
                $q->where('user_id', $this->userId);
            })
            // فیلتر تاریخ پرداخت میلادی
            ->when($this->paidDate, function ($q) {
                $q->whereDate('paid_at', $this->paidDate);
            })
            // فیلتر وضعیت
            ->when($this->status !== '', function ($q) {
                $q->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.admin-pay-table', [
            'adminPays' => $adminPays,
            'statuses' => AdminPayStatus::cases(),
        ]);
    }
}
