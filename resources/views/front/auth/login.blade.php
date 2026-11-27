
@extends('front.layout.master')

@section('content')
    <style>
        :root {
            --primary:       #c0396e;
            --primary-light: #e8557f;
            --primary-bg:    #fdf0f5;
            --purple-bg:     #f3e8ff;
            --purple-accent: #9b5fe4;
            --text-dark:     #1a1a2e;
            --text-mid:      #555;
            --text-light:    #888;
            --card-radius:   16px;
            --btn-radius:    50px;
            --transition:    0.25s ease;
        }



        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: var(--card-radius);
            padding: 40px 32px 32px;
            box-shadow: 0 18px 45px rgba(26, 26, 46, .08);
            border: 1px solid rgba(192, 57, 110, .08);
        }

        .login-wrap {
            margin-top: 40px;
            min-height: calc(100vh - 200px); /* فضای هدر/فوتر رو در نظر نگیر */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-logo {
            width: 68px;
            height: 68px;
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            font-size: 30px;
            color: #fff;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            box-shadow: 0 10px 22px rgba(192, 57, 110, .28);
        }

        .login-title {
            text-align: center;
            font-weight: 800;
            font-size: 1.25rem;
            margin-bottom: 6px;
        }

        .login-sub {
            text-align: center;
            color: var(--text-light);
            font-size: .85rem;
            margin-bottom: 28px;
        }

        .form-label {
            font-size: .85rem;
            font-weight: 700;
            color: var(--text-mid);
            margin-bottom: 6px;
        }

        .field-wrap {
            position: relative;
        }

        .field-wrap > i {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1.05rem;
            pointer-events: none;
        }

        .field-wrap .form-control {
            height: 50px;
            padding-right: 42px;
            padding-left: 42px;
            border-radius: 12px;
            border: 1.5px solid #e8e8ee;
            background: #fbfbfd;
            font-size: .92rem;
            transition: var(--transition);
            text-align: right;
            letter-spacing: 0;
        }

        .field-wrap .form-control:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(192, 57, 110, .09);
        }

        .field-wrap .form-control.is-invalid {
            border-color: #e5484d;
            background: #fff5f5;
        }

        .toggle-pass {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: var(--text-light);
            font-size: 1.05rem;
            cursor: pointer;
            padding: 4px 6px;
            border-radius: 8px;
            transition: var(--transition);
        }

        .toggle-pass:hover { color: var(--primary); }

        .invalid-feedback { font-size: .78rem; }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 16px 0 22px;
        }

        .remember-row .form-check-label {
            font-size: .85rem;
            color: var(--text-mid);
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-login {
            width: 100%;
            height: 50px;
            border: 0;
            border-radius: var(--btn-radius);
            font-weight: 800;
            font-size: .95rem;
            color: #fff;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            box-shadow: 0 10px 24px rgba(192, 57, 110, .26);
            transition: var(--transition);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(192, 57, 110, .32);
        }

        .btn-login:active { transform: translateY(0); }

        .alert-soft {
            border-radius: 12px;
            background: #fff5f5;
            border: 1px solid #ffd9d9;
            color: #c0272d;
            font-size: .82rem;
            padding: 11px 14px;
            margin-bottom: 18px;
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }

        .card-footer-note {
            text-align: center;
            font-size: .75rem;
            color: var(--text-light);
            margin-top: 22px;
        }

        .card-footer-note a {
            color: var(--purple-accent);
            text-decoration: none;
            font-weight: 700;
        }
    </style>

<div class="login-wrap">

    <div class="login-card">

        <div class="login-logo">
            <i class="bi bi-shield-lock-fill"></i>
        </div>

        <div class="login-title">ورود به پنل </div>
        <div class="login-sub">برای ادامه، شماره موبایل و رمز عبور خود را وارد کنید</div>

        @if (session('error'))
            <div class="alert-soft">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-soft">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" novalidate>
            @csrf

            {{-- موبایل --}}
            <div class="mb-3">
                <label class="form-label" for="mobile">شماره موبایل</label>
                <div class="field-wrap">
                    <i class="bi bi-phone"></i>
                    <input type="tel"
                           id="mobile"
                           name="mobile"
                           value="{{ old('mobile') }}"
                           class="form-control @error('mobile') is-invalid @enderror"
                           placeholder="۰۹۱۲۳۴۵۶۷۸۹"
                           inputmode="numeric"
                           maxlength="11"
                           autocomplete="username"
                           autofocus>
                </div>
            </div>

            {{-- رمز عبور --}}
            <div class="mb-2">
                <label class="form-label" for="password">رمز عبور</label>
                <div class="field-wrap">
                    <i class="bi bi-lock"></i>
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="••••••••"
                           autocomplete="current-password">
                    <button type="button" class="toggle-pass" id="togglePass" aria-label="نمایش رمز">
                        <i class="bi bi-eye" id="togglePassIcon"></i>
                    </button>
                </div>
            </div>

            <div class="remember-row">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">مرا به خاطر بسپار</label>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-left ms-1"></i> ورود
            </button>
        </form>

        <div class="card-footer-note">
            <a href="{{ route('home') }}">بازگشت به سایت</a>
        </div>

    </div>
</div>


<script>
    // فقط عدد در فیلد موبایل
    document.getElementById('mobile').addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9۰-۹٠-٩]/g, '');
    });

    // toggle رمز
    document.getElementById('togglePass').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon  = document.getElementById('togglePassIcon');
        const show  = input.type === 'password';
        input.type  = show ? 'text' : 'password';
        icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
</script>

@endsection
