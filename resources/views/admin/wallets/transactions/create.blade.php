@extends('admin.layout.master')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: var(--primary);">
            <i class="bi bi-shield-lock ms-1"></i> مسدودسازی یا آزادسازی وجه: {{ $user->first_name . ' ' . $user->last_name }}
        </h5>
        <a href="{{ route('admin.wallets.user', $user->id) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت به کیف پول کاربر
        </a>
    </div>

    {{-- وضعیت زنده موجودی‌های کاربر --}}
    <div class="alert alert-light border shadow-sm p-3 mb-4 d-flex flex-wrap gap-4 align-items-center">
        <div>
            <span class="text-muted small">شماره تماس:</span>
            <strong class="text-dark">{{ $user->mobile ?? '---' }}</strong>
        </div>
        <div>
            <span class="text-muted small">موجودی آزاد :</span>
            <strong class="text-success">{{ number_format($wallet->available_balance) }} تومان</strong>
        </div>
        <div>
            <span class="text-muted small">موجودی مسدود شده :</span>
            <strong class="text-warning">{{ number_format($wallet->blocked_balance) }} تومان</strong>
        </div>
        <div>
            <span class="text-muted small">کل موجودی:</span>
            <strong class="text-dark">{{ number_format($wallet->total_balance) }} تومان</strong>
        </div>
    </div>

    <form action="{{ route('admin.wallets.transactions.store', $user->id) }}" method="POST">
        @csrf
        <div class="row g-3">
            {{-- انتخاب عملیات (Block / Unblock) --}}
            <div class="col-12 col-md-6">
                <label class="form-label">نوع عملیات <span class="text-danger">*</span></label>
                <select name="action" class="form-select custom-select @error('action') is-invalid @enderror">
                    <option value="">انتخاب کنید...</option>
                    <option value="block" @selected(old('action') === 'block')>مسدودسازی وجه (کاهش از آزاد و افزایش به مسدود شده)</option>
                    <option value="unblock" @selected(old('action') === 'unblock')>آزادسازی وجه (کاهش از مسدود شده و بازگشت به آزاد)</option>
                </select>
                @error('action') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- مبلغ --}}
            <div class="col-12 col-md-6">
                <label class="form-label">مبلغ (تومان) <span class="text-danger">*</span></label>
                <input type="number" name="amount" value="{{ old('amount') }}" min="1000" step="1000"
                       class="form-control custom-input @error('amount') is-invalid @enderror" placeholder="مثلاً: 100000">
                @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <hr class="my-4" style="border-color: var(--border);">

            {{-- توضیحات --}}
            <div class="col-12">
                <label class="form-label">توضیحات و علت مسدودسازی/آزادسازی <span class="text-danger">*</span></label>
                <textarea name="description" rows="4"
                          class="form-control custom-input @error('description') is-invalid @enderror"
                          placeholder="علت یا توضیحات مربوط به مسدودسازی یا آزادسازی وجه را وارد کنید...">{{ old('description') }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- دکمه‌ها --}}
            <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.wallets.user', $user->id) }}" class="btn btn-theme-cancel">انصراف</a>
                <button type="submit" class="btn btn-theme-primary px-4">ثبت عملیات</button>
            </div>
        </div>
    </form>

@endsection
