<?php

namespace App\Livewire\Admin;

use App\Enums\VideoType;
use App\Models\Video;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class AdminVideoTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // فیلترها
    public $searchInput = '';

    public $selectedCourseId = '';

    public $typeInput = '';

    public $dateInput = '';

    // متغیرهای کوئری
    public $search;

    public $courseId;

    public $type;

    public $date;

    public function applySearch()
    {
        $this->search = trim($this->searchInput);
        $this->courseId = $this->selectedCourseId;
        $this->type = $this->typeInput;

        if (! empty($this->dateInput)) {

            $this->date = Jalalian::fromFormat('Y/n/j', $this->dateInput)->toCarbon()->format('Y-m-d');
        }

        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['searchInput', 'search', 'selectedCourseId', 'courseId', 'type', 'typeInput', 'dateInput', 'date']);
        $this->resetPage();
        $this->dispatch('filters-reset');
    }

    public function render()
    {
        $types = VideoType::cases();

        $videos = Video::query()
            ->with(['course'])
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->courseId, fn ($q) => $q->where('course_id', $this->courseId))
            ->when($this->type, fn ($q) => $q->where('type', $this->type))
            ->when($this->date, fn ($q) => $q->whereDate('created_at', $this->date))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.admin-video-table', compact('videos', 'types'));
    }
}
