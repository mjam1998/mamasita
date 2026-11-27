@extends('admin.layout.master')

@section('content')
    <style>
        :root {
            --primary: #c0396e;
            --primary-light: #e8557f;
            --primary-soft: #fdf0f5;
            --purple: #9b5fe4;
            --purple-soft: #f3e8ff;
            --text-dark: #1a1a2e;
            --text-mid: #626275;
            --text-light: #9291a1;
            --border: #eeeaf0;
            --canvas: #faf8fb;
            --radius: 18px;
            --shadow: 0 8px 28px rgba(50,35,45,.055);
        }
        .ov-page  { background: var(--canvas); color: var(--text-dark); }
        .ov-card  { background:#fff; border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow); padding:1.5rem; height:100%; }
        .ov-title { font-weight:700; color:var(--text-dark); margin-bottom:1rem; font-size:1rem; }
        .ov-label { color:var(--text-light); font-size:.85rem; }
        .ov-value { font-weight:600; color:var(--text-dark); }
        .ov-row   { display:flex; justify-content:space-between; padding:.6rem 0; border-bottom:1px dashed var(--border); }
        .ov-row:last-child { border-bottom:none; }
        .ov-badge { background:var(--primary-soft); color:var(--primary); border-radius:999px; padding:.35rem .9rem; font-weight:600; font-size:.85rem; }
        .ov-badge-purple { background:var(--purple-soft); color:var(--purple); }
        .ov-avatar { width:48px; height:48px; border-radius:14px; background:linear-gradient(135deg,var(--primary-light),var(--purple)); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; }
        .ov-btn-primary { background:linear-gradient(135deg,var(--primary),var(--primary-light)); color:#fff; border:none; border-radius:12px; padding:.6rem 1.1rem; font-weight:600; }
        .ov-btn-primary:hover { color:#fff; opacity:.92; }
        .ov-btn-outline { border:1px solid var(--border); color:var(--text-mid); background:#fff; border-radius:12px; padding:.6rem 1.1rem; }
        .ov-total { background:var(--primary-soft); border-radius:14px; padding:1rem; }
        .ov-total-value { font-size:1.25rem; font-weight:800; color:var(--primary); }
        .ov-ltr { direction:ltr; unicode-bidi:plaintext; }
        .ov-licence-box { background:var(--purple-soft); border-radius:14px; padding:1rem 1.25rem; word-break:break-all; }
    </style>

    <div class="ov-page p-4">

        {{-- هدر --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <div class="ov-label">جزئیات سفارش دوره</div>
                <h4 class="fw-bold mb-0">
                    کد پیگیری:
                    <span class="ov-ltr" style="color:var(--primary)">{{ $courseOrder->track_number }}</span>
                </h4>
            </div>
            <div class="d-flex gap-2">
                <span class="ov-badge">{{ $courseOrder->status->label() }}</span>
                <a href="{{ route('admin.course-orders.index') }}" class="ov-btn-outline text-decoration-none">
                    <i class="bi bi-arrow-right"></i> بازگشت
                </a>
            </div>
        </div>

        <div class="row g-4">

            {{-- کاربر --}}
            <div class="col-12 col-md-6">
                <div class="ov-card">
                    <div class="ov-title">کاربر</div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="ov-avatar">{{ mb_substr($courseOrder->user->first_name, 0, 1) }}</div>
                        <div>
                            <div class="ov-value">{{ $courseOrder->user->first_name }} {{ $courseOrder->user->last_name }}</div>
                            <div class="ov-label ov-ltr">{{ $courseOrder->user->mobile }}</div>
                        </div>
                    </div>
                    <div class="ov-row">
                        <span class="ov-label">شماره تماس</span>
                        <a class="ov-value ov-ltr text-decoration-none" href="tel:{{ $courseOrder->user->mobile }}">
                            {{ $courseOrder->user->mobile }}
                        </a>
                    </div>
                    <div class="ov-row">
                        <span class="ov-label">کد ملی</span>
                        <span class="ov-value ov-ltr">{{ $courseOrder->user->national_code ?? '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- دوره --}}
            <div class="col-12 col-md-6">
                <div class="ov-card">
                    <div class="ov-title">دوره</div>
                    @if($courseOrder->course)
                        <div class="ov-row">
                            <span class="ov-label">نام دوره</span>
                            <span class="ov-value">{{ $courseOrder->course->name }}</span>
                        </div>
                        <div class="ov-row">
                            <span class="ov-label">قیمت دوره</span>
                            <span class="ov-value">{{ number_format($courseOrder->course->price) }} <small class="text-muted">تومان</small></span>
                        </div>
                        <div class="ov-row">
                            <span class="ov-label">کد SpotPlayer</span>
                            <span class="ov-value ov-ltr">{{ $courseOrder->course->spot_player_code ?? '—' }}</span>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.courses.edit', $courseOrder->course) }}"
                               class="ov-btn-primary text-decoration-none d-inline-block">
                                <i class="bi bi-play-circle"></i> مشاهده دوره
                            </a>
                        </div>
                    @else
                        <span class="ov-label">دوره‌ای یافت نشد.</span>
                    @endif
                </div>
            </div>

            {{-- اطلاعات سفارش --}}
            <div class="col-12 col-lg-6">
                <div class="ov-card">
                    <div class="ov-title">اطلاعات سفارش</div>
                    <div class="ov-row">
                        <span class="ov-label">وضعیت</span>
                        <span class="ov-value">{{ $courseOrder->status->label() }}</span>
                    </div>
                    <div class="ov-row">
                        <span class="ov-label">تاریخ ایجاد</span>
                        <span class="ov-value">
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($courseOrder->created_at)->format('Y/m/d H:i') }}
                    </span>
                    </div>
                    <div class="ov-row">
                        <span class="ov-label">آخرین بروزرسانی</span>
                        <span class="ov-value">
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($courseOrder->updated_at)->format('Y/m/d H:i') }}
                    </span>
                    </div>
                </div>
            </div>

            {{-- لایسنس SpotPlayer --}}
            <div class="col-12 col-lg-6">
                <div class="ov-card">
                    <div class="ov-title">لایسنس SpotPlayer</div>
                    @if($courseOrder->spotPlayerLicence)
                        @php $lic = $courseOrder->spotPlayerLicence; @endphp
                        <div class="ov-row">
                            <span class="ov-label">شناسه لایسنس</span>
                            <span class="ov-value ov-ltr">{{ $lic->spot_licence_id ?? '—' }}</span>
                        </div>
                        <div class="ov-row">
                            <span class="ov-label">کلید</span>
                            <span class="ov-value ov-ltr" style="color:var(--purple); font-size:.85rem">{{ $lic->spot_key ?? '—' }}</span>
                        </div>
                        @if($lic->spot_url)
                            <div class="mt-3">
                                <div class="ov-licence-box">
                                    <div class="ov-label mb-1">لینک دسترسی</div>
                                    <a href="{{ $lic->spot_url }}" target="_blank"
                                       class="ov-ltr text-decoration-none" style="color:var(--purple); font-size:.85rem">
                                        {{ $lic->spot_url }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="ov-label">
                            @if($courseOrder->status->value === 'paid' || $courseOrder->status->value === 'completed')
                                لایسنسی صادر نشده است.
                            @else
                                پس از پرداخت موفق، لایسنس صادر می‌شود.
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- مبالغ --}}
            <div class="col-12">
                <div class="ov-card">
                    <div class="ov-title">مبالغ</div>
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <div class="ov-total">
                                <div class="ov-label mb-1">مبلغ کل</div>
                                <div class="ov-total-value">{{ number_format($courseOrder->total_price) }} <small class="fs-6">تومان</small></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="ov-total" style="background:var(--purple-soft)">
                                <div class="ov-label mb-1">تخفیف</div>
                                <div class="ov-total-value" style="color:var(--purple)">{{ number_format($courseOrder->discount_amount) }} <small class="fs-6">تومان</small></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="ov-total">
                                <div class="ov-label mb-1">مبلغ پرداختی</div>
                                <div class="ov-total-value">{{ number_format($courseOrder->pay_amount) }} <small class="fs-6">تومان</small></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- پرداخت‌ها --}}
            <div class="col-12">
                <div class="ov-card">
                    <div class="ov-title">پرداخت‌ها</div>

                    @if($courseOrder->userPay->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                <tr class="text-center ov-label">
                                    <th>کد پیگیری پرداخت</th>
                                    <th>مبلغ</th>
                                    <th>کد مرجع بانک</th>
                                    <th>وضعیت</th>
                                    <th>تاریخ پرداخت</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($courseOrder->userPay as $pay)
                                    <tr class="text-center">
                                        <td class="ov-ltr fw-bold" style="color:var(--primary)">
                                            {{ $pay->track_number ?? '—' }}
                                        </td>
                                        <td class="fw-bold">
                                            {{ number_format($pay->amount) }}
                                            <small class="text-muted">تومان</small>
                                        </td>
                                        <td class="ov-ltr text-muted">{{ $pay->bank_ref_number ?? '—' }}</td>
                                        <td>
                                        <span class="ov-badge {{ $pay->is_paid ? '' : 'ov-badge-purple' }}">
                                            {{ $pay->is_paid ? 'پرداخت شده' : 'پرداخت نشده' }}
                                        </span>
                                        </td>
                                        <td class="text-muted">
                                            {{ $pay->paid_at
                                                ? \Morilog\Jalali\Jalalian::fromDateTime($pay->paid_at)->format('Y/m/d H:i')
                                                : '—' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.user-pays.show', $pay) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-receipt"></i> جزئیات
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="ov-label">پرداختی برای این سفارش ثبت نشده است.</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
