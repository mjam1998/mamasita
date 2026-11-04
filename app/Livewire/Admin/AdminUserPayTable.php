<?php

namespace App\Livewire\Admin;

use App\Models\CourseOrder;
use App\Models\DoulaSubscription;
use App\Models\MonthlySubscription;
use App\Models\OnlineVisit;
use App\Models\UserPay;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class AdminUserPayTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // فیلترهای ورودی
    public $searchInput = '';

    public $bankRefInput = '';

    public $selectedUserId = '';

    public $referenceableTypeInput = '';

    public $paidDateJalali = '';

    public $statusInput = '';

    public $onlyPaid = false; // چک‌باکس فقط پرداخت‌شده‌ها

    // مقادیر اعمال‌شده در کوئری
    public $search = '';

    public $bankRef = '';

    public $userId = '';

    public $referenceableType = '';

    public $paidDate = '';

    public $status = '';

    public $isPaid = false;

    public function applySearch()
    {
        $this->search = trim($this->searchInput);
        $this->bankRef = trim($this->bankRefInput);
        $this->userId = $this->selectedUserId;
        $this->referenceableType = $this->referenceableTypeInput;
        $this->status = $this->statusInput;
        $this->isPaid = (bool) $this->onlyPaid;

        // تبدیل تاریخ شمسی به میلادی
        if (! empty($this->paidDateJalali)) {
            try {
                $this->paidDate = Jalalian::fromFormat('Y/m/d', $this->paidDateJalali)
                    ->toCarbon()
                    ->format('Y-m-d');
            } catch (\Throwable $e) {
                try {
                    $this->paidDate = Jalalian::fromFormat('Y/n/j', $this->paidDateJalali)
                        ->toCarbon()
                        ->format('Y-m-d');
                } catch (\Throwable $e2) {
                    $this->paidDate = '';
                }
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
            'bankRefInput', 'bankRef',
            'selectedUserId', 'userId',
            'referenceableTypeInput', 'referenceableType',
            'paidDateJalali', 'paidDate',
            'statusInput', 'status',
            'onlyPaid', 'isPaid',
        ]);

        $this->resetPage();
        $this->dispatch('filters-reset');
    }

    public function render()
    {
        $referenceableTypes = [
            CourseOrder::class => 'دوره آموزشی / ویدیویی',
            OnlineVisit::class => 'ویزیت آنلاین',
            MonthlySubscription::class => 'اشتراک ماهیانه',
            DoulaSubscription::class => 'اشتراک ماما همراه',
        ];

        $userPays = UserPay::query()
            ->with('user')
            // جلوگیری از خطای دیتای تست نامعتبر نظیر "aa"
            ->where(function ($q) use ($referenceableTypes) {
                $q->whereIn('referenceable_type', array_keys($referenceableTypes))
                    ->orWhereNull('referenceable_type');
            })
            ->with('referenceable')
            // فیلتر چک‌باکس پرداخت‌شده
            ->when($this->isPaid, function ($q) {
                $q->where('is_paid', true);
            })
            // فیلتر کد پیگیری
            ->when($this->search, function ($q) {
                $q->where('track_number', 'like', "%{$this->search}%");
            })
            // فیلتر شماره پیگیری بانک
            ->when($this->bankRef, function ($q) {
                $q->where('bank_ref_number', 'like', "%{$this->bankRef}%");
            })
            // فیلتر کاربر
            ->when($this->userId, function ($q) {
                $q->where('user_id', $this->userId);
            })
            // فیلتر نوع مرجع
            ->when($this->referenceableType, function ($q) {
                $q->where('referenceable_type', $this->referenceableType);
            })
            // فیلتر وضعیت
            ->when($this->status !== '', function ($q) {
                $q->where('status', $this->status);
            })
            // فیلتر تاریخ پرداخت
            ->when($this->paidDate, function ($q) {
                $q->whereDate('paid_at', $this->paidDate);
            })
            ->latest()
            ->paginate(12);

        return view('livewire.admin.admin-user-pay-table', [
            'userPays' => $userPays,
            'referenceableTypes' => $referenceableTypes,
        ]);
    }
}
