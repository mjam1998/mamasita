<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Rules\SlugRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class AdminBlogController extends Controller
{
    public function index()
    {
        return view('admin.blog.index');
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', 'unique:blogs,slug', new SlugRule],
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image_alt' => 'nullable|string|max:400',
            'image_title' => 'nullable|string|max:400',
            'meta_title' => 'nullable|string|max:400',
            'meta_description' => 'nullable|string|max:400',
            'keywords' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'عنوان مقاله الزامی است.',
            'image.required' => 'عکس مقاله الزامی است.',
            'slug.required' => 'اسلاگ الزامی است.',
            'slug.unique' => 'این اسلاگ قبلاً استفاده شده است.',
            'image.image' => 'فایل ارسالی باید تصویر باشد.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // تبدیل کلمات کلیدی به JSON
        if (! empty($validated['keywords'])) {
            $tags = array_filter(array_map('trim', explode(',', $validated['keywords'])));
            $validated['keywords'] = json_encode(array_map(fn ($t) => ['value' => $t], $tags));
        }

        // آپلود تصویر
        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadImage($request, $validated['slug']);
        }

        Blog::create($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'مقاله جدید با موفقیت ایجاد شد.');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', new SlugRule, Rule::unique('blogs', 'slug')->ignore($blog->id)],
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image_alt' => 'nullable|string|max:400',
            'image_title' => 'nullable|string|max:400',
            'meta_title' => 'nullable|string|max:400',
            'meta_description' => 'nullable|string|max:400',
            'keywords' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'عنوان مقاله الزامی است.',
            'slug.required' => 'اسلاگ الزامی است.',
            'slug.unique' => 'این اسلاگ قبلاً استفاده شده است.',
            'image.image' => 'فایل ارسالی باید تصویر باشد.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if (! empty($validated['keywords'])) {
            $tags = array_filter(array_map('trim', explode(',', $validated['keywords'])));
            $validated['keywords'] = json_encode(array_map(fn ($t) => ['value' => $t], $tags));
        } else {
            $validated['keywords'] = null;
        }

        if ($request->hasFile('image')) {
            // حذف تصویر قدیمی
            if ($blog->image && Storage::disk('public')->exists('blogs/'.$blog->image)) {
                Storage::disk('public')->delete('blogs/'.$blog->image);
            }
            $validated['image'] = $this->uploadImage($request, $validated['slug']);
        }

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'مقاله با موفقیت بروزرسانی شد.');
    }

    private function uploadImage(Request $request, string $slug): string
    {
        $imageManager = new ImageManager(new Driver);
        $imgName = 'blog_'.time().'_'.$slug.'.webp';
        $encoded = $imageManager->decode($request->file('image'))->encode(new WebpEncoder(quality: 80));
        Storage::disk('public')->put('blogs/'.$imgName, (string) $encoded);

        return $imgName;
    }
}
