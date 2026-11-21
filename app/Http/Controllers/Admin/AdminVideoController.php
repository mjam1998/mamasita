<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VideoType;
use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AdminVideoController extends Controller
{
    public function index()
    {
        return view('admin.video.index');
    }

    public function create()
    {
        $types = VideoType::cases();

        return view('admin.video.form', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required_if:type,'.VideoType::Course->value.'|nullable|exists:courses,id',
            'title' => 'required|string|max:255',
            'type' => 'required|integer|in:1,2',
            'sort' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'video_file' => 'required|file|mimetypes:video/mp4,video/webm,video/ogg|max:512000',
        ], [
            'course_id.required_if' => 'انتخاب دوره برای ویدیوی نوع دوره الزامی است.',
            'course_id.exists' => 'دوره انتخاب‌شده معتبر نیست.',
            'title.required' => 'وارد کردن عنوان الزامی است.',
            'title.string' => 'عنوان باید به‌صورت متن باشد.',
            'title.max' => 'عنوان نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'type.required' => 'انتخاب نوع الزامی است.',
            'type.integer' => 'نوع باید یک عدد صحیح باشد.',
            'type.in' => 'نوع انتخاب‌شده معتبر نیست.',
            'sort.integer' => 'ترتیب نمایش باید عدد صحیح باشد.',
            'sort.min' => 'ترتیب نمایش نمی‌تواند کمتر از صفر باشد.',
            'is_active.boolean' => 'وضعیت فعال بودن باید معتبر باشد.',
            'description.string' => 'توضیحات باید به‌صورت متن باشد.',
            'video_file.required' => 'انتخاب فایل ویدیویی الزامی است.',
            'video_file.file' => 'فایل ارسال‌شده معتبر نیست.',
            'video_file.mimetypes' => 'فرمت ویدیو باید MP4، WebM یا OGG باشد.',
            'video_file.max' => 'حجم فایل ویدیویی نباید بیشتر از ۵۰۰ مگابایت باشد.',
        ]);

        try {
            $validated['is_active'] = $request->boolean('is_active');
            $validated['file_path'] = $this->storeVideo($request, (int) $validated['type']);

            Video::create($validated);

            return redirect()->route('admin.videos.index')
                ->with('success', 'ویدیو با موفقیت ثبت شد.');

        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'خطایی  رخ داد. لطفاً دوباره تلاش کنید.');
        }
    }

    public function edit(Video $video)
    {
        $types = VideoType::cases();

        return view('admin.video.form', compact('video', 'types'));
    }

    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'course_id' => 'required_if:type,'.VideoType::Course->value.'|nullable|exists:courses,id',
            'title' => 'required|string|max:255',
            'type' => 'required|integer|in:1,2',
            'sort' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'video_file' => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg|max:512000',
        ], [
            'course_id.required_if' => 'انتخاب دوره برای ویدیوی نوع دوره الزامی است.',
            'course_id.exists' => 'دوره انتخاب‌شده معتبر نیست.',
            'title.required' => 'وارد کردن عنوان الزامی است.',
            'title.string' => 'عنوان باید به‌صورت متن باشد.',
            'title.max' => 'عنوان نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'type.required' => 'انتخاب نوع الزامی است.',
            'type.integer' => 'نوع باید یک عدد صحیح باشد.',
            'type.in' => 'نوع انتخاب‌شده معتبر نیست.',
            'sort.integer' => 'ترتیب نمایش باید عدد صحیح باشد.',
            'sort.min' => 'ترتیب نمایش نمی‌تواند کمتر از صفر باشد.',
            'is_active.boolean' => 'وضعیت فعال بودن باید معتبر باشد.',
            'description.string' => 'توضیحات باید به‌صورت متن باشد.',
            'video_file.required' => 'انتخاب فایل ویدیویی الزامی است.',
            'video_file.file' => 'فایل ارسال‌شده معتبر نیست.',
            'video_file.mimetypes' => 'فرمت ویدیو باید MP4، WebM یا OGG باشد.',
            'video_file.max' => 'حجم فایل ویدیویی نباید بیشتر از ۵۰۰ مگابایت باشد.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        try {
            if ($request->hasFile('video_file')) {

                $this->deleteOldVideo($video);
                $validated['file_path'] = $this->storeVideo($request, (int) $validated['type']);
            }

            $video->update($validated);

            return redirect()->route('admin.videos.index')
                ->with('success', 'ویدیو با موفقیت ویرایش شد.');

        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'خطایی  رخ داد. لطفاً دوباره تلاش کنید.');
        }

    }

    private function storeVideo(Request $request, int $type): string
    {
        $file = $request->file('video_file');
        $fileName = 'video_'.time().'_'.$file->hashName();

        if ($type === VideoType::Course->value) {
            // دوره → public
            $file->storeAs('videos/courses', $fileName, 'public');
        } else {
            // اشتراک → local
            $file->storeAs('videos/subscriptions', $fileName, 'local');
        }

        return $fileName;
    }

    private function deleteOldVideo(Video $video): void
    {
        if (! $video->file_path) {
            return;
        }

        $disk = $video->type === VideoType::Course ? 'public' : 'local';
        $dir = $video->type === VideoType::Course
            ? 'videos/courses/'
            : 'videos/subscriptions/';

        Storage::disk($disk)->delete($dir.$video->file_path);
    }
}
