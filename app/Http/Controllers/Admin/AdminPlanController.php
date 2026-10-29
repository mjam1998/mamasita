<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MamaFiled;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Rules\SlugRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class AdminPlanController extends Controller
{
    public function index()
    {
        return view('admin.plan.index');
    }

    public function create()
    {
        $types = MamaFiled::cases();

        return view('admin.plan.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', 'unique:plans,slug', new SlugRule],
            'type' => [
                'required',
                new Enum(MamaFiled::class),
                function ($attribute, $value, $fail) {
                    $uniqueTypes = [MamaFiled::OnlineVisit->value, MamaFiled::DoulaSubscription->value];
                    if (in_array((int) $value, $uniqueTypes)) {
                        $exists = Plan::where('type', $value)->exists();
                        if ($exists) {
                            $typeLabel = MamaFiled::from((int) $value)->label();
                            $fail("قبلاً برای «{$typeLabel}» یک پلن ثبت شده است و امکان ثبت پلن جدید وجود ندارد.");
                        }
                    }
                },
            ],
            'month_period' => 'nullable|required_if:type,'.MamaFiled::MonthlySubscription->value.'|integer|min:1|max:120',
            'price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0|lte:price',
            'mama_share' => 'required|numeric|min:0|lte:price',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image_alt' => 'nullable|string|max:400',
            'image_title' => 'nullable|string|max:400',
            'meta_title' => 'nullable|string|max:400',
            'meta_description' => 'nullable|string|max:400',
        ], [
            'name.required' => 'عنوان پلن الزامی است.',
            'slug.required' => 'ادرس اسلاگ پلن الزامی است.',
            'slug.unique' => 'این اسلاگ (Slug) قبلاً استفاده شده است.',
            'type.required' => 'نوع پلن الزامی است.',
            'month_period.required_if' => 'برای اشتراک ماهانه، تعیین دوره به ماه الزامی است.',
            'month_period.integer' => 'دوره به ماه باید عددی صحیح باشد.',
            'price.required' => 'قیمت پلن الزامی است.',
            'price.numeric' => 'قیمت باید عددی باشد.',
            'discount_amount.lte' => 'مبلغ تخفیف نمی‌تواند بیشتر از مبلغ پلن باشد.',
            'mama_share.required' => 'سهم ماما الزامی است.',
            'mama_share.lte' => 'سهم ماما نمی‌تواند بیشتر از مبلغ پلن باشد.',
            'image.image' => 'فایل ارسالی باید تصویر باشد.',
        ]);

        $validated['discount_amount'] = $validated['discount_amount'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        // در صورت ویزیت آنلاین یا همراه زایمان نیازی به ماه نیست
        if ((int) $validated['type'] !== MamaFiled::MonthlySubscription->value) {
            $validated['month_period'] = null;
        }

        // آپلود تصویر شاخص با Intervention Image v4
        if ($request->hasFile('image')) {
            $imageManager = new ImageManager(new Driver);
            $imgName = 'plan_'.time().'_'.$request['slug'].'.webp';
            $imageDecoded = $imageManager->decode($request->file('image'));
            $imageEncoded = $imageDecoded->encode(new WebpEncoder(quality: 80));

            Storage::disk('public')->put('plans/'.$imgName, (string) $imageEncoded);
            $validated['image'] = $imgName;
        }

        Plan::create($validated);

        return redirect()->route('admin.plan.index')->with('success', 'پلن جدید با موفقیت ایجاد شد.');
    }

    public function edit(Plan $plan)
    {
        $types = MamaFiled::cases();

        return view('admin.plan.edit', compact('plan', 'types'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', new SlugRule, Rule::unique('plans', 'slug')->ignore($plan->id)],
            'type' => [
                'required',
                new Enum(MamaFiled::class),
                function ($attribute, $value, $fail) use ($plan) {
                    $uniqueTypes = [MamaFiled::OnlineVisit->value, MamaFiled::DoulaSubscription->value];
                    if (in_array((int) $value, $uniqueTypes)) {
                        $exists = Plan::where('type', $value)->where('id', '!=', $plan->id)->exists();
                        if ($exists) {
                            $typeLabel = MamaFiled::from((int) $value)->label();
                            $fail("قبلاً برای «{$typeLabel}» پلن دیگری ثبت شده است.");
                        }
                    }
                },
            ],
            'month_period' => 'nullable|required_if:type,'.MamaFiled::MonthlySubscription->value.'|integer|min:1|max:120',
            'price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0|lte:price',
            'mama_share' => 'required|numeric|min:0|lte:price',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image_alt' => 'nullable|string|max:400',
            'image_title' => 'nullable|string|max:400',
            'meta_title' => 'nullable|string|max:400',
            'meta_description' => 'nullable|string|max:400',
        ], [
            'name.required' => 'عنوان پلن الزامی است.',
            'slug.required' => 'ادرس اسلاگ پلن الزامی است.',
            'slug.unique' => 'این اسلاگ قبلاً استفاده شده است.',
            'type.required' => 'نوع پلن الزامی است.',
            'month_period.required_if' => 'برای اشتراک ماهانه، تعیین دوره به ماه الزامی است.',
            'month_period.integer' => 'دوره به ماه باید عددی صحیح باشد.',
            'price.required' => 'قیمت پلن الزامی است.',
            'price.numeric' => 'قیمت باید عددی باشد.',
            'discount_amount.lte' => 'مبلغ تخفیف نمی‌تواند بیشتر از مبلغ پلن باشد.',
            'mama_share.required' => 'سهم ماما الزامی است.',
            'mama_share.lte' => 'سهم ماما نمی‌تواند بیشتر از مبلغ پلن باشد.',
            'image.image' => 'فایل ارسالی باید تصویر باشد.',
        ]);

        $validated['slug'] = ! empty($validated['slug'])
            ? Str::slug($validated['slug'], '-', null)
            : Str::slug($validated['name'], '-', null).'-'.$plan->id;

        $validated['discount_amount'] = $validated['discount_amount'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        if ((int) $validated['type'] !== MamaFiled::MonthlySubscription->value) {
            $validated['month_period'] = null;
        }

        if ($request->hasFile('image')) {
            if ($plan->image && Storage::disk('public')->exists('plans/'.$plan->image)) {
                Storage::disk('public')->delete('plans/'.$plan->image);
            }

            $imageManager = new ImageManager(new Driver);
            $imgName = 'plan_'.time().'_'.$request['slug'].'.webp';
            $imageDecoded = $imageManager->decode($request->file('image'));
            $imageEncoded = $imageDecoded->encode(new WebpEncoder(quality: 80));

            Storage::disk('public')->put('plans/'.$imgName, (string) $imageEncoded);
            $validated['image'] = $imgName;
        }

        $plan->update($validated);

        return redirect()->route('admin.plan.index')->with('success', 'پلن با موفقیت به‌روزرسانی شد.');
    }
}
