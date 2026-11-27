<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Rules\SlugRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class AdminCourseController extends Controller
{
    public function index()
    {
        return view('admin.course.index');
    }

    public function create()
    {
        return view('admin.course.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', 'unique:courses,slug', new SlugRule],
            'spot_player_code' => 'required|string|max:400',
            'price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0|lte:price',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image_alt' => 'nullable|string|max:400',
            'image_title' => 'nullable|string|max:400',
            // CourseLimit
            'p0_all_device' => 'nullable|integer|min:0',
            'p1_windows' => 'nullable|integer|min:0',
            'p2_macos' => 'nullable|integer|min:0',
            'p3_linux' => 'nullable|integer|min:0',
            'p4_android' => 'nullable|integer|min:0',
            'p5_ios' => 'nullable|integer|min:0',
            'p6_webapp' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'عنوان دوره الزامی است.',
            'slug.required' => 'اسلاگ دوره الزامی است.',
            'slug.unique' => 'این اسلاگ قبلاً استفاده شده است.',
            'price.required' => 'قیمت دوره الزامی است.',
            'discount_amount.lte' => 'مبلغ تخفیف نمی‌تواند بیشتر از قیمت دوره باشد.',
            'image.image' => 'فایل ارسالی باید تصویر باشد.',
        ]);

        $validated['discount_amount'] = $validated['discount_amount'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $imageManager = new ImageManager(new Driver);
            $imgName = 'course_'.time().'_'.$validated['slug'].'.webp';
            $encoded = $imageManager->decode($request->file('image'))
                ->encode(new WebpEncoder(quality: 80));
            Storage::disk('public')->put('courses/'.$imgName, (string) $encoded);
            $validated['image'] = $imgName;
        }

        $course = Course::create($validated);

        $course->courseLimit()->create([
            'p0_all_device' => $validated['p0_all_device'] ?? 0,
            'p1_windows' => $validated['p1_windows'] ?? 0,
            'p2_macos' => $validated['p2_macos'] ?? 0,
            'p3_linux' => $validated['p3_linux'] ?? 0,
            'p4_android' => $validated['p4_android'] ?? 0,
            'p5_ios' => $validated['p5_ios'] ?? 0,
            'p6_webapp' => $validated['p6_webapp'] ?? 0,
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', 'دوره جدید با موفقیت ایجاد شد.');
    }

    public function edit(Course $course)
    {
        $course->load('courseLimit');

        return view('admin.course.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', new SlugRule, Rule::unique('courses', 'slug')->ignore($course->id)],
            'spot_player_code' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0|lte:price',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image_alt' => 'nullable|string|max:400',
            'image_title' => 'nullable|string|max:400',
            // CourseLimit
            'p0_all_device' => 'nullable|integer|min:0',
            'p1_windows' => 'nullable|integer|min:0',
            'p2_macos' => 'nullable|integer|min:0',
            'p3_linux' => 'nullable|integer|min:0',
            'p4_android' => 'nullable|integer|min:0',
            'p5_ios' => 'nullable|integer|min:0',
            'p6_webapp' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'عنوان دوره الزامی است.',
            'slug.required' => 'اسلاگ دوره الزامی است.',
            'slug.unique' => 'این اسلاگ قبلاً استفاده شده است.',
            'price.required' => 'قیمت دوره الزامی است.',
            'discount_amount.lte' => 'مبلغ تخفیف نمی‌تواند بیشتر از قیمت دوره باشد.',
            'image.image' => 'فایل ارسالی باید تصویر باشد.',
        ]);

        $validated['discount_amount'] = $validated['discount_amount'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $validated['slug'] = ! empty($validated['slug'])
            ? Str::slug($validated['slug'], '-', null)
            : Str::slug($validated['name'], '-', null).'-'.$course->id;

        if ($request->hasFile('image')) {
            if ($course->image && Storage::disk('public')->exists('courses/'.$course->image)) {
                Storage::disk('public')->delete('courses/'.$course->image);
            }

            $imageManager = new ImageManager(new Driver);
            $imgName = 'course_'.time().'_'.$validated['slug'].'.webp';
            $encoded = $imageManager->decode($request->file('image'))
                ->encode(new WebpEncoder(quality: 80));
            Storage::disk('public')->put('courses/'.$imgName, (string) $encoded);
            $validated['image'] = $imgName;
        }

        $course->update($validated);

        $course->courseLimit()->updateOrCreate(
            ['course_id' => $course->id],
            [
                'p0_all_device' => $validated['p0_all_device'] ?? 0,
                'p1_windows' => $validated['p1_windows'] ?? 0,
                'p2_macos' => $validated['p2_macos'] ?? 0,
                'p3_linux' => $validated['p3_linux'] ?? 0,
                'p4_android' => $validated['p4_android'] ?? 0,
                'p5_ios' => $validated['p5_ios'] ?? 0,
                'p6_webapp' => $validated['p6_webapp'] ?? 0,
            ]
        );

        return redirect()->route('admin.courses.index')
            ->with('success', 'دوره با موفقیت به‌روزرسانی شد.');
    }

    public function search(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $courses = Course::query()
            ->where(function ($query) use ($q) {
                $query->where('slug', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%");
            })
            ->select(['id', 'name', 'slug'])
            ->limit(15)
            ->get()
            ->map(fn ($u) => [
                'value' => $u->id,
                'label' => "{$u->name} ({$u->slug})",
            ]);

        return response()->json($courses);
    }
}
