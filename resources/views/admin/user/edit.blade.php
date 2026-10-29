@extends('admin.layout.master')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: var(--primary);">
            <i class="bi bi-pencil-square ms-1"></i> ویرایش کاربر: {{ $user->first_name . ' ' . $user->last_name }}
        </h5>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3">
            {{-- نام --}}
            <div class="col-12 col-md-6">
                <label class="form-label">نام <span class="text-danger">*</span></label>
                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                       class="form-control custom-input @error('first_name') is-invalid @enderror">
                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- نام خانوادگی --}}
            <div class="col-12 col-md-6">
                <label class="form-label">نام خانوادگی <span class="text-danger">*</span></label>
                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                       class="form-control custom-input @error('last_name') is-invalid @enderror">
                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- کد ملی --}}
            <div class="col-12 col-md-6">
                <label class="form-label">کد ملی <span class="text-danger">*</span></label>
                <input type="text" name="national_code" value="{{ old('national_code', $user->national_code) }}" maxlength="10"
                       class="form-control custom-input @error('national_code') is-invalid @enderror">
                @error('national_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- شماره موبایل --}}
            <div class="col-12 col-md-6">
                <label class="form-label">شماره موبایل <span class="text-danger">*</span></label>
                <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}" maxlength="11"
                       class="form-control custom-input @error('mobile') is-invalid @enderror">
                @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- نقش‌های کاربر (Multi-Select با Choices) --}}
            <div class="col-12 col-md-6">
                <label class="form-label">نقش‌های کاربر <span class="text-danger">*</span></label>
                @php
                    $roleTranslations = ['super-admin' => 'سوپر ادمین', 'user' => 'کاربر', 'mama' => 'ماما'];
                    $currentRoles = old('roles', $userRoles);
                @endphp
                <select name="roles[]" id="roles-select" class="form-select custom-select @error('roles') is-invalid @enderror" multiple>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ in_array($role->name, $currentRoles) ? 'selected' : '' }}>
                            {{ $roleTranslations[$role->name] ?? $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('roles') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                @error('roles.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            {{-- وضعیت کاربر --}}
            <div class="col-12 col-md-6 d-flex align-items-center mt-md-4">
                <div class="form-check form-switch pt-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold me-2" for="is_active">کاربر فعال است</label>
                </div>
            </div>

            <hr class="my-4" style="border-color: var(--border);">
            <div class="col-12">
                <small class="text-muted">در صورتی که قصد تغییر کلمه عبور را ندارید، فیلدهای زیر را خالی بگذارید.</small>
            </div>

            {{-- رمز عبور جدید --}}
            <div class="col-12 col-md-6">
                <label class="form-label">کلمه عبور جدید</label>
                <input type="password" name="password" class="form-control custom-input @error('password') is-invalid @enderror">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- تکرار رمز عبور جدید --}}
            <div class="col-12 col-md-6">
                <label class="form-label">تکرار کلمه عبور جدید</label>
                <input type="password" name="password_confirmation" class="form-control custom-input">
            </div>

            <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.list.all') }}" class="btn btn-theme-cancel">انصراف</a>
                <button type="submit" class="btn btn-theme-primary px-4">ذخیره تغییرات</button>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleElement = document.getElementById('roles-select');
            if (roleElement) {
                new Choices(roleElement, {
                    removeItemButton: true,
                    placeholderValue: 'انتخاب نقش‌ها...',
                    searchPlaceholderValue: 'جستجو...',
                    noResultsText: 'نتیجه‌ای یافت نشد',
                    itemSelectText: 'انتخاب',
                });
            }
        });
    </script>
@endpush
