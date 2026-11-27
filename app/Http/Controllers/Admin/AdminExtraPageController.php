<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Rules\SlugRule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminExtraPageController extends Controller
{
    public function index()
    {
        return view('admin.extra-page.index');
    }

    public function create()
    {
        return view('admin.extra-page.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', 'unique:pages,slug', new SlugRule],
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:400',
            'meta_description' => 'nullable|string|max:400',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'عنوان صفحه الزامی است.',
            'slug.required' => 'اسلاگ الزامی است.',
            'slug.unique' => 'این اسلاگ قبلاً استفاده شده است.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Page::create($validated);

        return redirect()->route('admin.extra-pages.index')->with('success', 'صفحه جدید با موفقیت ایجاد شد.');
    }

    public function edit(Page $page)
    {
        return view('admin.extra-page.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', new SlugRule, Rule::unique('pages', 'slug')->ignore($page->id)],
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:400',
            'meta_description' => 'nullable|string|max:400',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'عنوان صفحه الزامی است.',
            'slug.required' => 'اسلاگ الزامی است.',
            'slug.unique' => 'این اسلاگ قبلاً استفاده شده است.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $page->update($validated);

        return redirect()->route('admin.extra-pages.index')->with('success', 'صفحه با موفقیت بروزرسانی شد.');
    }
}
