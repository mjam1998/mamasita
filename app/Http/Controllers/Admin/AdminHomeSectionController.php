<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class AdminHomeSectionController extends Controller
{
    public function edit()
    {
        $section = HomeSection::first() ?? new HomeSection;

        return view('admin.home-section.index', compact('section'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'h1_text' => 'nullable|string|max:255',
            'h2_text' => 'nullable|string|max:255',
            'h3_text' => 'nullable|string|max:255',
            'btn_text' => 'nullable|string|max:100',
            'btn_link' => 'nullable|string|max:400',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8096',
            'image_alt' => 'nullable|string|max:400',
            'image_title' => 'nullable|string|max:400',
            'meta_title' => 'nullable|string|max:400',
            'meta_description' => 'nullable|string|max:400',
        ], [
            'image.image' => 'فایل ارسالی باید تصویر باشد.',
            'image.max' => 'حجم تصویر نباید بیشتر از 8 مگابایت باشد.',
        ]);

        $section = HomeSection::first() ?? new HomeSection;

        if ($request->hasFile('image')) {
            // حذف تصویر قدیمی
            if ($section->image && Storage::disk('public')->exists('home-section/'.$section->image)) {
                Storage::disk('public')->delete('home-section/'.$section->image);
            }
            $validated['image'] = $this->uploadImage($request);
        }

        $section->fill($validated)->save();

        return redirect()->route('admin.home-section.edit')->with('success', 'تنظیمات صفحه اصلی با موفقیت ذخیره شد.');
    }

    private function uploadImage(Request $request): string
    {
        $manager = new ImageManager(new Driver);
        $imgName = 'home_hero_'.time().'.webp';
        $encoded = $manager->decode($request->file('image'))->encode(new WebpEncoder(quality: 82));
        Storage::disk('public')->put('home-section/'.$imgName, (string) $encoded);

        return $imgName;
    }
}
