@extends('admin.layout.master')

@section('content')
    <div >
        <div class="custom-card">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="fw-bold m-0" style="color: var(--primary);">
                    <i class="bi bi-person-plus ms-1"></i> افزودن کاربر جدید
                </h5>
                <a href="{{ route('admin.list.all') }}" class="btn btn-sm btn-theme-cancel">بازگشت به لیست</a>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    {{-- نام --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">نام <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                               class="form-control custom-input @error('first_name') is-invalid @enderror" placeholder="مثال: سارا">
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- نام خانوادگی --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">نام خانوادگی <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                               class="form-control custom-input @error('last_name') is-invalid @enderror" placeholder="مثال: محمدی">
                        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- کد ملی --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">کد ملی (۱۰ رقم) <span class="text-danger">*</span></label>
                        <input type="text" name="national_code" value="{{ old('national_code') }}" maxlength="10"
                               class="form-control custom-input @error('national_code') is-invalid @enderror" placeholder="0012345678">
                        @error('national_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- شماره موبایل --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">شماره موبایل <span class="text-danger">*</span></label>
                        <input type="text" name="mobile" value="{{ old('mobile') }}" maxlength="11"
                               class="form-control custom-input @error('mobile') is-invalid @enderror" placeholder="09123456789">
                        @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- نقش کاربر --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">نقش کاربر <span class="text-danger">*</span></label>
                        @php
                            $roleTranslations = ['super-admin' => 'سوپر ادمین', 'user' => 'کاربر', 'mama' => 'ماما'];
                        @endphp
                        <select name="role" class="form-select custom-select @error('role') is-invalid @enderror">
                            <option value="">انتخاب نقش...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                    {{ $roleTranslations[$role->name] ?? $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- وضعیت کاربر --}}
                    <div class="col-12 col-md-6 d-flex align-items-center mt-md-4">
                        <div class="form-check form-switch pt-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold me-2" for="is_active">کاربر فعال باشد</label>
                        </div>
                    </div>

                    <hr class="my-4" style="border-color: var(--border);">

                    {{-- رمز عبور --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">کلمه عبور <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control custom-input @error('password') is-invalid @enderror">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- تکرار رمز عبور --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">تکرار کلمه عبور <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control custom-input">
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.list.all') }}" class="btn btn-theme-cancel">انصراف</a>
                        <button type="submit" class="btn btn-theme-primary px-4">ثبت کاربر</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

