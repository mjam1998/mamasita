{{-- resources/views/admin/calls/show.blade.php --}}
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
        .ov-card  { background: #fff; border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); padding: 1.5rem; height: 100%; }
        .ov-title { font-weight: 700; color: var(--text-dark); margin-bottom: 1rem; font-size: 1rem; }
        .ov-label { color: var(--text-light); font-size: .85rem; }
        .ov-value { font-weight: 600; color: var(--text-dark); }
        .ov-row   { display: flex; justify-content: space-between; padding: .6rem 0; border-bottom: 1px dashed var(--border); }
        .ov-row:last-child { border-bottom: none; }
        .ov-badge { background: var(--primary-soft); color: var(--primary); border-radius: 999px; padding: .35rem .9rem; font-weight: 600; font-size: .85rem; display: inline-block; }
        .ov-badge-purple { background: var(--purple-soft); color: var(--purple); }
        .ov-badge-success { background: #edf7f1; color: #1e7a45; }
        .ov-avatar { width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, var(--primary-light), var(--purple)); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; }
        .ov-btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: #fff; border: none; border-radius: 12px; padding: .6rem 1.1rem; font-weight: 600; }
        .ov-btn-primary:hover { color: #fff; opacity: .92; }
        .ov-btn-outline { border: 1px solid var(--border); color: var(--text-mid); background: #fff; border-radius: 12px; padding: .6rem 1.1rem; }
        .ov-total { background: var(--primary-soft); border-radius: 14px; padding: 1rem; }
        .ov-total-value { font-size: 1.25rem; font-weight: 800; color: var(--primary); }
        .ov-ltr { direction: ltr; unicode-bidi: plaintext; }
        .ov-track-link { color: var(--primary); font-weight: 700; text-decoration: none; }
        .ov-track-link:hover { text-decoration: underline; color: var(--primary-light); }
    </style>

    <div class="ov-page p-4">

        {{-- هدر --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <div class="ov-label">جزئیات تماس</div>

            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="ov-badge">{{ $call->status->label() }}</span>
                <a href="{{ route('admin.calls.index') }}" class="ov-btn-outline text-decoration-none">
                    <i class="bi bi-arrow-right"></i> بازگشت
                </a>
            </div>
        </div>

        <div class="row g-4">

            {{-- اطلاعات تماس --}}
            <div class="col-12 col-lg-6">
                <div class="ov-card">
                    <div class="ov-title">اطلاعات تماس</div>
                    <div class="ov-row">
                        <span class="ov-label">وضعیت</span>
                        <span class="ov-badge">{{ $call->status->label() }}</span>
                    </div>
                    <div class="ov-row">
                        <span class="ov-label">مبلغ پرداختی به ماما</span>
                        <span class="ov-total-value">{{ number_format($call->pay_amount) }} <small class="fs-6 fw-normal text-muted">تومان</small></span>
                    </div>
                    @if($call->rate)
                        <div class="ov-row">
                            <span class="ov-label">امتیاز</span>
                            <span class="ov-value">{{ $call->rate->label() }}</span>
                        </div>
                    @endif
                    @if($call->user_comment)
                        <div class="ov-row">
                            <span class="ov-label">نظر کاربر</span>
                            <span class="ov-value" style="max-width: 60%; text-align: left">{{ $call->user_comment }}</span>
                        </div>
                    @endif
                    @if($call->called_at)
                        <div class="ov-row">
                            <span class="ov-label">زمان تماس</span>
                            <span class="ov-value ov-ltr">
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($call->called_at)->format('Y/m/d H:i') }}
                    </span>
                        </div>
                    @endif

                    @if($call->expire_at)
                        <div class="ov-row">
                            <span class="ov-label">انقضا</span>
                            <span class="ov-value ov-ltr">
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($call->expire_at)->format('Y/m/d H:i') }}
                    </span>
                        </div>
                    @endif
                    <div class="ov-row">
                        <span class="ov-label">تاریخ ثبت</span>
                        <span class="ov-value ov-ltr">
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($call->created_at)->format('Y/m/d H:i') }}
                    </span>
                    </div>
                </div>
            </div>

            {{-- اشتراک ماهانه --}}
            <div class="col-12 col-lg-6">
                <div class="ov-card">
                    <div class="ov-title">اشتراک ماهانه مرتبط</div>
                    <div class="ov-row">
                        <span class="ov-label">کد پیگیری</span>
                        <a href="{{ route('admin.monthly-subscriptions.show', $call->monthlySubscription) }}"
                           class="ov-track-link ov-ltr">
                            {{ $call->monthlySubscription->track_number }}
                            <i class="bi bi-box-arrow-up-left ms-1" style="font-size:.8rem"></i>
                        </a>
                    </div>
                    <div class="ov-row">
                        <span class="ov-label">وضعیت اشتراک</span>
                        <span class="ov-badge ov-badge-purple">{{ $call->monthlySubscription->status->label() }}</span>
                    </div>
                    <div class="ov-row">
                        <span class="ov-label">پلن</span>
                        <span class="ov-value">{{ $call->monthlySubscription->plan?->name ?? '—' }}</span>
                    </div>
                    <div class="ov-row">
                        <span class="ov-label">دوره ماهانه</span>
                        <span class="ov-value">{{ $call->monthlySubscription->month_period }} ماه</span>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.monthly-subscriptions.show', $call->monthlySubscription) }}"
                           class="ov-btn-primary text-decoration-none d-inline-block">
                            <i class="bi bi-card-list"></i> مشاهده جزئیات اشتراک
                        </a>
                    </div>
                </div>
            </div>

            {{-- کاربر --}}
            <div class="col-12 col-md-6">
                <div class="ov-card">
                    <div class="ov-title">کاربر</div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="ov-avatar">
                            {{ mb_substr($call->monthlySubscription->user->first_name, 0, 1) }}
                        </div>
                        <div>
                            <div class="ov-value">
                                {{ $call->monthlySubscription->user->first_name }}
                                {{ $call->monthlySubscription->user->last_name }}
                            </div>
                            <div class="ov-label ov-ltr">{{ $call->monthlySubscription->user->mobile }}</div>
                        </div>
                    </div>
                    <div class="ov-row">
                        <span class="ov-label">شماره تماس</span>
                        <a class="ov-value ov-ltr text-decoration-none"
                           href="tel:{{ $call->monthlySubscription->user->mobile }}">
                            {{ $call->monthlySubscription->user->mobile }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- ماما --}}
            <div class="col-12 col-md-6">
                <div class="ov-card">
                    <div class="ov-title">ماما</div>
                    @if($call->monthlySubscription->mama)
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="ov-avatar"
                                 style="background: linear-gradient(135deg, var(--purple), var(--primary-light))">
                                {{ mb_substr($call->monthlySubscription->mama->first_name, 0, 1) }}
                            </div>
                            <div>
                                <div class="ov-value">
                                    {{ $call->monthlySubscription->mama->first_name }}
                                    {{ $call->monthlySubscription->mama->last_name }}
                                </div>
                                <div class="ov-label ov-ltr">{{ $call->monthlySubscription->mama->mobile }}</div>
                            </div>
                        </div>
                        <div class="ov-row">
                            <span class="ov-label">شماره تماس</span>
                            <a class="ov-value ov-ltr text-decoration-none"
                               href="tel:{{ $call->monthlySubscription->mama->mobile }}">
                                {{ $call->monthlySubscription->mama->mobile }}
                            </a>
                        </div>
                    @else
                        <div class="ov-label">ماما تعیین نشده است.</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
