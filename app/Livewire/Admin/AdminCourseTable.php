<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class AdminCourseTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // فیلترها
    public $searchInput = '';

    public $spotCodeInput = '';

    public $slugInput = '';

    public $dateInput = '';

    // متغیرهای کوئری
    public $search;

    public $spotCode;

    public $slug;

    public $date;

    public function applySearch()
    {
        $this->search = trim($this->searchInput);
        $this->spotCode = trim($this->spotCodeInput);
        $this->slug = trim($this->slugInput);

        if (! empty($this->dateInput)) {

            $this->date = Jalalian::fromFormat('Y/n/j', $this->dateInput)->toCarbon()->format('Y-m-d');
        }

        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['searchInput', 'search', 'spotCode', 'spotCodeInput', 'slug', 'slugInput', 'dateInput', 'date',
        ]);
        $this->resetPage();
        $this->dispatch('filters-reset');
    }

    public function render()
    {

        $courses = Course::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->slug, fn ($q) => $q->where('slug', 'like', "%{$this->slug}%"))
            ->when($this->spotCode, fn ($q) => $q->where('spot_player_code', $this->spotCode))
            ->when($this->date, fn ($q) => $q->whereDate('created_at', $this->date))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.admin-course-table', compact('courses'));
    }
}
