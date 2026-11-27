<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.index');
        }

        return view('front.auth.login');
    }

    public function login(Request $request)
    {
        $request->merge([
            'mobile' => $this->toEnglishDigits($request->input('mobile')),
        ]);

        $request->validate([
            'mobile' => ['required', 'regex:/^09\d{9}$/'],
            'password' => ['required', 'string', 'min:4'],
        ], [
            'mobile.required' => 'شماره موبایل الزامی است.',
            'mobile.regex' => 'شماره موبایل باید ۱۱ رقم و با ۰۹ شروع شود.',
            'password.required' => 'رمز عبور الزامی است.',
            'password.min' => 'رمز عبور حداقل ۶ کاراکتر است.',
        ]);

        $credentials = [
            'mobile' => $request->input('mobile'),
            'password' => $request->input('password'),
            'is_active' => true,
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            // تشخیص پیام دقیقتر: کاربر هست ولی غیرفعال؟
            $inactive = User::where('mobile', $credentials['mobile'])
                ->where('is_active', false)
                ->exists();

            return back()
                ->withInput($request->only('mobile'))
                ->withErrors([
                    'mobile' => $inactive
                        ? 'حساب کاربری شما غیرفعال شده است. با پشتیبانی تماس بگیرید.'
                        : 'شماره موبایل یا رمز عبور اشتباه است.',
                ]);
        }

        $user = User::where('mobile', $credentials['mobile'])
            ->where('is_active', true)
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['mobile' => 'شماره موبایل یا رمز عبور اشتباه است.'])->withInput();
        }

        auth()->login($user);

        return redirect()->route('admin.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function toEnglishDigits(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return str_replace(
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            trim($value)
        );
    }
}
