<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MamaFiled;
use App\Enums\MamaInformationStatus;
use App\Enums\Status;
use App\Events\UserRoleAssigned;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\MamaInformation;
use App\Models\MamaType;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function create()
    {
        $roles = Role::all();

        return view('admin.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'national_code' => 'required|digits:10|unique:users,national_code',
            'mobile' => ['required', 'regex:/^09[0-9]{9}$/', 'unique:users,mobile'],
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'is_active' => 'nullable|boolean',
        ], [
            'first_name.required' => 'وارد کردن نام الزامی است.',
            'last_name.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'national_code.required' => 'وارد کردن کد ملی الزامی است.',
            'national_code.digits' => 'کد ملی باید دقیقاً ۱۰ رقم باشد.',
            'national_code.unique' => 'این کد ملی قبلاً ثبت شده است.',
            'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
            'mobile.regex' => 'فرمت شماره موبایل معتبر نیست؛ مثال: 09123456789.',
            'mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است.',
            'password.required' => 'وارد کردن کلمه عبور الزامی است.',
            'password.min' => 'کلمه عبور باید حداقل ۶ کاراکتر باشد.',
            'password.confirmed' => 'تکرار کلمه عبور با کلمه عبور مطابقت ندارد.',
            'roles.required' => 'انتخاب حداقل یک نقش برای کاربر الزامی است.',
            'roles.array' => 'نقش‌های ارسالی نامعتبر است.',
            'roles.*.exists' => 'یک یا چند نقش انتخاب‌شده نامعتبر است.',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'national_code' => $validated['national_code'],
            'mobile' => $validated['mobile'],
            'password' => Hash::make($validated['password']),
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        // انتصاب چندین نقش
        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.list.all')->with('success', 'کاربر جدید با موفقیت ثبت شد.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        // دریافت آرایه‌ای از نام نقش‌های فعلی کاربر
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('admin.user.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'national_code' => [
                'required',
                'digits:10',
                Rule::unique('users')->ignore($user->id),
            ],
            'mobile' => [
                'required',
                'regex:/^09[0-9]{9}$/',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'roles' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    if (in_array('mama', $value) && in_array('pending-mama', $value)) {
                        $fail('یک کاربر نمی‌تواند همزمان هر دو نقش «ماما» و «ماما در حال بررسی» را داشته باشد.');
                    }
                },
            ],
            'roles.*' => 'exists:roles,name',
            'is_active' => 'nullable|boolean',
        ], [
            'first_name.required' => 'وارد کردن نام الزامی است.',
            'last_name.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'national_code.required' => 'وارد کردن کد ملی الزامی است.',
            'national_code.digits' => 'کد ملی باید دقیقاً ۱۰ رقم باشد.',
            'national_code.unique' => 'این کد ملی قبلاً برای کاربر دیگری ثبت شده است.',
            'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
            'mobile.regex' => 'فرمت شماره موبایل معتبر نیست؛ مثال: 09123456789.',
            'mobile.unique' => 'این شماره موبایل قبلاً برای کاربر دیگری ثبت شده است.',
            'password.min' => 'کلمه عبور باید حداقل ۶ کاراکتر باشد.',
            'password.confirmed' => 'تکرار کلمه عبور با کلمه عبور مطابقت ندارد.',
            'roles.required' => 'انتخاب حداقل یک نقش برای کاربر الزامی است.',
            'roles.*.exists' => 'یک یا چند نقش انتخاب‌شده نامعتبر است.',
        ]);

        $data = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'national_code' => $validated['national_code'],
            'mobile' => $validated['mobile'],
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        // بروزرسانی نقش‌ها
        $user->syncRoles($validated['roles']);

        $user->refresh();
        UserRoleAssigned::dispatch($user);

        return redirect()->route('admin.list.all')->with('success', 'اطلاعات کاربر با موفقیت به‌روزرسانی شد.');
    }

    public function mamaEdit(User $user)
    {
        $mamaInformation = $user->mamaInformation;
        if (! $mamaInformation) {
            $mamaInformation = $user->mamaInformation()->create([
                'province_id' => Province::first()?->id,
                'city_id' => City::first()?->id,
            ]);
        }
        $provinces = Province::all();
        $cities = City::where('province_id', $mamaInformation?->province_id)->get();
        $statuses = MamaInformationStatus::cases();

        return view('admin.user.mama.edit', compact('mamaInformation', 'provinces', 'cities', 'statuses'));
    }

    public function mamaUpdate(Request $request, MamaInformation $mamaInformation)
    {
        $validated = $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'doula_price' => 'nullable|numeric|min:0',
            'bank_name' => 'required|string|max:255',
            'card_number' => ['required', 'digits:16'],
            'iban_number' => ['required', 'string', 'size:24', 'regex:/^[0-9]{24}$/'],
            'status' => ['required', new Enum(MamaInformationStatus::class)],
            'image_alt' => 'nullable|string|max:400',
            'image_title' => 'nullable|string|max:400',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'national_card_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'education_file' => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:10240',
            'admin_review' => ['nullable', 'string', 'max:2000'],
        ], [
            'province_id.required' => 'انتخاب استان الزامی است.',
            'province_id.exists' => 'استان انتخاب شده نامعتبر است.',
            'city_id.required' => 'انتخاب شهر الزامی است.',
            'city_id.exists' => 'شهر انتخاب شده نامعتبر است.',
            'doula_price.numeric' => 'مبلغ پیشنهادی ماما همراه باید به عدد باشد.',
            'doula_price.min' => 'مبلغ پیشنهادی نمی‌تواند منفی باشد.',
            'bank_name.required' => 'نام بانک الزامی است.',
            'bank_name.max' => 'نام بانک نباید بیش از ۲۵۵ کاراکتر باشد.',
            'card_number.required' => 'شماره کارت بانکی الزامی است.',
            'card_number.digits' => 'شماره کارت بانکی باید دقیقاً ۱۶ رقم باشد.',
            'iban_number.required' => 'شماره شبا الزامی است.',
            'iban_number.size' => 'شماره شبا باید دقیقاً ۲۴ رقم (بدون IR) باشد.',
            'iban_number.regex' => 'فرمت شماره شبا صحیح نیست؛ تنها ۲۴ رقم بدون IR وارد کنید.',
            'status.required' => 'وضعیت پرونده الزامی است.',
            'image.image' => 'فایل تصویر پرسونلی باید از نوع عکس باشد.',
            'image.mimes' => 'فرمت‌های مجاز تصویر پرسونلی: jpeg, png, jpg, webp.',
            'image.max' => 'حداکثر حجم تصویر پرسونلی ۴ مگابایت است.',
            'national_card_file.image' => 'فایل کارت ملی باید عکس باشد.',
            'national_card_file.mimes' => 'فرمت‌های مجاز تصویر کارت ملی: jpeg, png, jpg, webp.',
            'national_card_file.max' => 'حداکثر حجم تصویر کارت ملی ۴ مگابایت است.',
            'education_file.file' => 'فایل مدرک تحصیلی نامعتبر است.',
            'education_file.mimes' => 'مدرک تحصیلی باید به صورت PDF یا عکس (jpg, png, webp) باشد.',
            'education_file.max' => 'حداکثر حجم فایل مدرک تحصیلی ۱۰ مگابایت است.',
        ]);

        $imageManager = new ImageManager(new Driver);

        // ۱. آپلود و بهینه‌سازی عکس پرسونلی
        if ($request->hasFile('image')) {
            if ($mamaInformation->image && Storage::disk('public')->exists('mamas/images/'.$mamaInformation->image)) {
                Storage::disk('public')->delete('mamas/images/'.$mamaInformation->image);
            }

            $imgName = 'avatar_'.$mamaInformation->user_id.'_'.time().'.webp';
            $imageDecoded = $imageManager->decode($request->file('image'));
            $imageEncoded = $imageDecoded->encode(new WebpEncoder(quality: 80));

            Storage::disk('public')->put('mamas/images/'.$imgName, (string) $imageEncoded);
            $validated['image'] = $imgName;
        }

        if ($request->hasFile('national_card_file')) {
            if ($mamaInformation->national_card_file && Storage::disk('local')->exists('mamas/documents/'.$mamaInformation->national_card_file)) {
                Storage::disk('local')->delete('mamas/documents/'.$mamaInformation->national_card_file);
            }

            $ncName = 'national_card_'.$mamaInformation->user_id.'_'.time().'.webp';
            $ncDecoded = $imageManager->decode($request->file('national_card_file'));
            $ncEncoded = $ncDecoded->encode(new WebpEncoder(quality: 80));

            Storage::disk('local')->put('mamas/documents/'.$ncName, (string) $ncEncoded);
            $validated['national_card_file'] = $ncName;
        }

        // ۳. مدرک تحصیلی (خصوصی و محرمانه - دیسک local)
        if ($request->hasFile('education_file')) {
            if ($mamaInformation->education_file && Storage::disk('local')->exists('mamas/documents/'.$mamaInformation->education_file)) {
                Storage::disk('local')->delete('mamas/documents/'.$mamaInformation->education_file);
            }

            $file = $request->file('education_file');
            $ext = strtolower($file->getClientOriginalExtension());

            if ($ext === 'pdf') {
                $eduName = 'education_'.$mamaInformation->user_id.'_'.time().'.pdf';
                Storage::disk('local')->putFileAs('mamas/documents', $file, $eduName);
            } else {
                $eduName = 'education_'.$mamaInformation->user_id.'_'.time().'.webp';
                $eduDecoded = $imageManager->decode($request->file('education_file'));
                $eduEncoded = $eduDecoded->encode(new WebpEncoder(quality: 80));
                Storage::disk('local')->put('mamas/documents/'.$eduName, (string) $eduEncoded);
            }

            $validated['education_file'] = $eduName;
        }

        $mamaInformation->update($validated);
        $user = $mamaInformation->user;
        if ($user) {
            $selectedStatus = (int) $validated['status'];

            if ($selectedStatus === MamaInformationStatus::Active->value) {
                if ($user->hasRole('pending-mama')) {
                    $user->removeRole('pending-mama');
                }
                if (! $user->hasRole('mama')) {
                    $user->assignRole('mama');
                }
            }
        }

        return back()->with('success', 'مدارک و اطلاعات ماما با موفقیت به‌روزرسانی شد.');
    }

    public function mamaFields(User $user)
    {

        $existingTypes = MamaType::where('user_id', $user->id)
            ->get()
            ->keyBy(fn ($item) => $item->type->value);

        $fields = MamaFiled::cases();
        $statuses = Status::cases();

        return view('admin.user.mama.fields', compact('user', 'existingTypes', 'fields', 'statuses'));
    }

    public function updateMamaFields(Request $request, User $user)
    {
        $request->validate([
            'enabled_fields' => ['nullable', 'array'],
            'enabled_fields.*' => [new Enum(MamaFiled::class)],
            'status' => ['required', 'array'],
            'status.*' => [new Enum(Status::class)],
        ]);

        $enabledFields = $request->input('enabled_fields', []);
        $statuses = $request->input('status', []);

        // ۱. حوزه‌های فعال‌شده: ایجاد تقاضای جدید یا به‌روزرسانی وضعیت قبلی
        foreach ($enabledFields as $fieldValue) {
            $statusValue = $statuses[$fieldValue] ?? Status::Pending->value;

            MamaType::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'type' => $fieldValue,
                ],
                [
                    'status' => $statusValue,
                ]
            );
        }

        // ۲. حوزه‌هایی که تیک آنها برداشته شده: لغو/حذف تقاضا
        MamaType::where('user_id', $user->id)
            ->whereNotIn('type', $enabledFields)
            ->delete();

        return back()->with('success', 'حوزه‌ها و تقاضاهای فعالیت ماما با موفقیت به‌روزرسانی شدند.');
    }

    public function search(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $users = User::query()
            ->where(function ($query) use ($q) {
                $query->where('mobile', 'like', "%{$q}%")
                    ->orWhere('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$q}%"]);
            })
            ->select(['id', 'first_name', 'last_name', 'mobile'])
            ->limit(15)
            ->get()
            ->map(fn ($u) => [
                'value' => $u->id,
                'label' => "{$u->first_name} {$u->last_name} ({$u->mobile})",
            ]);

        return response()->json($users);
    }
}
