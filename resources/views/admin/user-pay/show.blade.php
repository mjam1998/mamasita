@extends('admin.layout.master')

@section('content')

    {{-- هدر صفحه --}}
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom" style="border-color: var(--border) !important;">
        <h5 class="fw-bold m-0" style="color: var(--primary);">
            <i class="bi bi-receipt-cutoff ms-1"></i> جزئیات پرداخت کاربر: #{{ $userPay->track_number }}
        </h5>
        <a href="{{ route('admin.user-pays.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت به لیست
        </a>
    </div>

    @php
        // نگاشت و استخراج اطلاعات مدل‌های پلی‌مورفیک با رنگ‌ها و کنتراست شفاف
        $ref = $userPay->referenceable;
        $refType = class_basename($userPay->referenceable_type);

        $typeInfo = match ($refType) {
            'CourseOrder' => [
                'title' => 'دوره ویدیویی',
                'style' => 'background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;',
                'icon'  => 'bi-play-btn-fill',
            ],
            'DoulaSubscription' => [
                'title' => 'اشتراک ماما همراه زایمان',
                'style' => 'background-color: var(--purple-soft); color: var(--purple); border: 1px solid rgba(155, 95, 228, 0.25);',
                'icon'  => 'bi-heart-pulse-fill',
            ],
            'MonthlySubscription' => [
                'title' => 'اشتراک ماهیانه',
                'style' => 'background-color: var(--primary-soft); color: var(--primary); border: 1px solid rgba(192, 57, 110, 0.25);',
                'icon'  => 'bi-calendar-check-fill',
            ],
            'OnlineVisit' => [
                'title' => 'ویزیت آنلاین',
                'style' => 'background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;',
                'icon'  => 'bi-camera-video-fill',
            ],
            default => [
                'title' => 'سایر موارد (' . $refType . ')',
                'style' => 'background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;',
                'icon'  => 'bi-box-seam',
            ],
        };

        // استخراج کد پیگیری مدل رفرنس
        $refTrackingCode = $ref?->track_number ?? 'ثبت نشده';
    @endphp

    <div class="row g-4">
        {{-- بخش اطلاعات پرداخت بانکی --}}
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: #ffffff; border: 1px solid var(--border) !important;">
                <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center" style="border-color: var(--border) !important;">
                    <span class="fw-bold" style="color: var(--text-dark);">
                        <i class="bi bi-credit-card-2-front ms-1" style="color: var(--primary);"></i> اطلاعات تراکنش مالی
                    </span>

                    {{-- بج وضعیت پرداخت با کنتراست بالا --}}
                    @if($userPay->is_paid)
                        <span class="badge rounded-pill px-3 py-2 fw-semibold"
                              style="background-color: #dcfce7; color: #15803d; border: 1px solid #86efac;">
                            <i class="bi bi-check-circle-fill ms-1"></i> پرداخت موفق
                        </span>
                    @else
                        <span class="badge rounded-pill px-3 py-2 fw-semibold"
                              style="background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5;">
                            <i class="bi bi-x-circle-fill ms-1"></i> ناموفق / در انتظار پرداخت
                        </span>
                    @endif
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label text-muted small">مبلغ پرداختی</label>
                            <div class="form-control-plaintext fs-5 fw-bold" style="color: var(--primary);">
                                {{ number_format($userPay->amount) }} <span class="fs-6 fw-normal text-muted">تومان</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label text-muted small">کد پیگیری تراکنش (سیستم)</label>
                            <div class="form-control-plaintext fw-semibold" style="color: var(--text-dark);">
                                <span class="user-select-all font-monospace">{{ $userPay->track_number }}</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label text-muted small">شماره پیگیری بانک (Bank Ref)</label>
                            <div class="form-control-plaintext fw-semibold font-monospace" style="color: var(--text-dark);">
                                {{ $userPay->bank_ref_number ?? '---' }}
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label text-muted small">تاریخ و زمان پرداخت</label>
                            <div class="form-control-plaintext" style="color: var(--text-mid);">
                                @if($userPay->paid_at)
                                    {{ jdate($userPay->paid_at)->format('Y/m/d - H:i:s') }}
                                @else
                                    ---
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label text-muted small">تاریخ ایجاد درخواست</label>
                            <div class="form-control-plaintext text-muted">
                                {{ jdate($userPay->created_at)->format('Y/m/d - H:i:s') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- سایدبار: مشخصات کاربر و مرجع خرید --}}
        <div class="col-12 col-lg-5">
            <div class="d-flex flex-column gap-4">

                {{-- اطلاعات کاربر پرداخت کننده --}}
                <div class="card border-0 shadow-sm rounded-4" style="background: #ffffff; border: 1px solid var(--border) !important;">
                    <div class="card-header bg-transparent border-bottom py-3" style="border-color: var(--border) !important;">
                        <span class="fw-bold" style="color: var(--text-dark);">
                            <i class="bi bi-person ms-1" style="color: var(--primary);"></i> کاربر پرداخت کننده
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">نام و نام خانوادگی:</span>
                                <span class="fw-bold" style="color: var(--text-dark);">
                                    {{ trim(($userPay->user?->first_name ?? '') . ' ' . ($userPay->user?->last_name ?? '')) ?: 'ثبت نشده' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">شماره موبایل:</span>
                                <span class="fw-bold font-monospace" style="color: var(--text-dark);">
                                    {{ $userPay->user?->mobile ?? '---' }}
                                </span>
                            </div>
                            @if($userPay->user)
                                <div class="pt-2 border-top text-end" style="border-color: var(--border) !important;">
                                    <a href="{{ route('admin.users.edit', $userPay->user_id) }}"
                                       class="btn btn-sm rounded-pill px-3"
                                       style="background-color: var(--primary-soft); color: var(--primary); border: 1px solid rgba(192, 57, 110, 0.2);">
                                        <i class="bi bi-person-lines-fill ms-1"></i> مشاهده پروفایل کاربر
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- اطلاعات مرجع (خدمت خریداری شده) --}}
                <div class="card border-0 shadow-sm rounded-4" style="background: #ffffff; border: 1px solid var(--border) !important;">
                    <div class="card-header bg-transparent border-bottom py-3" style="border-color: var(--border) !important;">
                        <span class="fw-bold" style="color: var(--text-dark);">
                            <i class="bi bi-bag-check ms-1" style="color: var(--purple);"></i> مرجع خرید (سرویس)
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            {{-- بج نوع مرجع اصلاح شده --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">نوع آیتم:</span>
                                <span class="badge px-3 py-2 rounded-pill fw-semibold" style="{{ $typeInfo['style'] }}">
                                    <i class="bi {{ $typeInfo['icon'] }} ms-1"></i> {{ $typeInfo['title'] }}
                                </span>
                            </div>

                            {{-- بج کد پیگیری مرجع اصلاح شده --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">کد پیگیری مرجع:</span>
                                <span class="badge px-3 py-2 rounded-pill font-monospace fw-bold"
                                      style="background-color: #f8fafc; color: var(--text-dark); border: 1px solid #cbd5e1;">
                                    {{ $refTrackingCode }}
                                </span>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
