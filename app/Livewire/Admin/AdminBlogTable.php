<?php

namespace App\Livewire\Admin;

use App\Models\Blog;
use Livewire\Component;
use Livewire\WithPagination;

class AdminBlogTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // input جستجو
    public $searchInput = '';

    public $search = '';

    public function applySearch()
    {
        $this->search = $this->searchInput;
        $this->resetPage();
    }

    public function render()
    {
        $blogs = Blog::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%'.$this->search.'%')
                        ->orWhere('slug', 'like', '%'.$this->search.'%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.admin-blog-table', compact('blogs'));
    }
}
