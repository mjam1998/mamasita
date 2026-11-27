<?php

namespace App\Livewire\Admin;

use App\Enums\CourseOrderStatus;
use App\Models\CourseOrder;
use App\Models\MonthlySubscription;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class AdminCourseOrderTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // فیلترها
    public $searchInput = '';

    public $selectedUserId = '';

    public $selectedCourseId = '';

    public $statusInput = '';

    public $dateInput = '';

    // متغیرهای کوئری
    public $search;

    public $userId;

    public $courseId;

    public $status;

    public $date;

    public function applySearch()
    {
        $this->search = trim($this->searchInput);
        $this->userId = $this->selectedUserId;
        $this->courseId = $this->selectedCourseId;
        $this->status = $this->statusInput;

        if (! empty($this->dateInput)) {

            $this->date = Jalalian::fromFormat('Y/n/j', $this->dateInput)->toCarbon()->format('Y-m-d');
        }

        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['searchInput', 'search', 'selectedUserId', 'userId',  'selectedCourseId', 'courseId', 'statusInput', 'status', 'dateInput', 'date',
        ]);
        $this->resetPage();
        $this->dispatch('filters-reset');
    }

    public function render()
    {
        $statuses = CourseOrderStatus::cases();

        $courseOrders = CourseOrder::query()
            ->with(['user', 'course'])
            ->when($this->search, fn ($q) => $q->where('track_number', 'like', "%{$this->search}%"))
            ->when($this->userId, fn ($q) => $q->where('user_id', $this->userId))
            ->when($this->courseId, fn ($q) => $q->where('course_id', $this->courseId))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->date, fn ($q) => $q->whereDate('created_at', $this->date))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.admin-course-order-table', compact('courseOrders', 'statuses'));
    }
}
