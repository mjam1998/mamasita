<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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
            'role' => 'required|exists:roles,name',
            'is_active' => 'nullable|boolean',
        ], [
            'first_name.required' => 'وارد کردن نام الزامی است.',
            'first_name.string' => 'نام باید به‌صورت متن وارد شود.',
            'first_name.max' => 'نام نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            'last_name.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'last_name.string' => 'نام خانوادگی باید به‌صورت متن وارد شود.',
            'last_name.max' => 'نام خانوادگی نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            'national_code.required' => 'وارد کردن کد ملی الزامی است.',
            'national_code.digits' => 'کد ملی باید دقیقاً ۱۰ رقم باشد.',
            'national_code.unique' => 'این کد ملی قبلاً ثبت شده است.',

            'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
            'mobile.regex' => 'فرمت شماره موبایل معتبر نیست؛ مثال: 09123456789.',
            'mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است.',

            'password.required' => 'وارد کردن کلمه عبور الزامی است.',
            'password.string' => 'کلمه عبور باید به‌صورت متن وارد شود.',
            'password.min' => 'کلمه عبور باید حداقل ۶ کاراکتر باشد.',
            'password.confirmed' => 'تکرار کلمه عبور با کلمه عبور مطابقت ندارد.',

            'role.required' => 'انتخاب نقش کاربر الزامی است.',
            'role.exists' => 'نقش انتخاب‌شده معتبر نیست.',

            'is_active.boolean' => 'وضعیت فعال بودن کاربر معتبر نیست.',
        ]);


        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'national_code' => $validated['national_code'],
            'mobile' => $validated['mobile'],
            'password' => Hash::make($validated['password']),
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.list.all')->with('success', 'کاربر جدید با موفقیت ثبت شد.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $userRole = $user->roles->first()?->name;

        return view('admin.user.edit', compact('user', 'roles', 'userRole'));
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
            'role' => 'required|exists:roles,name',
            'is_active' => 'nullable|boolean',
        ], [
            'first_name.required' => 'وارد کردن نام الزامی است.',
            'first_name.string' => 'نام باید به‌صورت متن وارد شود.',
            'first_name.max' => 'نام نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            'last_name.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'last_name.string' => 'نام خانوادگی باید به‌صورت متن وارد شود.',
            'last_name.max' => 'نام خانوادگی نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            'national_code.required' => 'وارد کردن کد ملی الزامی است.',
            'national_code.digits' => 'کد ملی باید دقیقاً ۱۰ رقم باشد.',
            'national_code.unique' => 'این کد ملی قبلاً برای کاربر دیگری ثبت شده است.',

            'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
            'mobile.regex' => 'فرمت شماره موبایل معتبر نیست؛ مثال: 09123456789.',
            'mobile.unique' => 'این شماره موبایل قبلاً برای کاربر دیگری ثبت شده است.',

            'password.string' => 'کلمه عبور باید به‌صورت متن وارد شود.',
            'password.min' => 'کلمه عبور باید حداقل ۶ کاراکتر باشد.',
            'password.confirmed' => 'تکرار کلمه عبور با کلمه عبور مطابقت ندارد.',

            'role.required' => 'انتخاب نقش کاربر الزامی است.',
            'role.exists' => 'نقش انتخاب‌شده معتبر نیست.',

            'is_active.boolean' => 'وضعیت فعال بودن کاربر معتبر نیست.',
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
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.list.all')->with('success', 'اطلاعات کاربر با موفقیت به‌روزرسانی شد.');
    }
}
