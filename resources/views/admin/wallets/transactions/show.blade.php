@extends('admin.layout.master')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: var(--primary);">
            <i class="bi bi-receipt-cutoff ms-1"></i> جزئیات تراکنش #{{ $transaction->track_number }}
        </h5>
        <a href="{{ route('admin.wallets.user', $user->id) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت به کیف پول کاربر
        </a>
    </div>

    {{-- وضعیت و جهت --}}
    <div class="card border-0 shadow-sm p-4 mb-4 text-center {{ $transaction->direction === \App\Enums\TransactionDirection::Credit ? 'bg-success-subtle' : 'bg-danger-subtle' }}">
        <div class="text-muted small mb-1">مبلغ عملیات</div>
        <h2 class="fw-bold mb-2 {{ $transaction->direction === \App\Enums\TransactionDirection::Credit ? 'text-success' : 'text-danger' }}">
            {{ number_format($transaction->amount) }} <span class="fs-5">تومان</span>
        </h2>
        <div>
            @if($transaction->direction === \App\Enums\TransactionDirection::Credit)
                <span class="badge bg-success px-3 py-2 fs-6">
                    <i class="bi bi-unlock me-1"></i> آزادسازی وجه (افزایش به موجودی آزاد)
                </span>
            @else
                <span class="badge bg-danger px-3 py-2 fs-6">
                    <i class="bi bi-lock me-1"></i> مسدودسازی وجه (کسر از موجودی آزاد)
                </span>
            @endif
        </div>
    </div>

    <div class="row g-3">
        {{-- مشخصات کاربر --}}
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-person-badge text-primary ms-1"></i> اطلاعات کاربر
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">نام کاربر:</span>
                            <strong class="text-dark">{{ $user->first_name . ' ' . $user->last_name }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">شماره موبایل:</span>
                            <span class="text-dark fw-bold">{{ $user->mobile ?? '---' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">موجودی کل پس از تراکنش:</span>
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ number_format($transaction->total_balance_after) }} تومان
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">موجودی آزاد پس از تراکنش:</span>
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ number_format($transaction->available_balance_after) }} تومان
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">موجودی مسدود پس از تراکنش:</span>
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ number_format($transaction->blocked_balance_after) }} تومان
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- مشخصات سیستمی --}}
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-info-circle text-primary ms-1"></i> مشخصات سیستمی
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">کد پیگیری سیستمی:</span>
                            <span class="badge bg-secondary-subtle text-dark border px-2 py-1 fs-6">{{ $transaction->track_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">نوع عملیات:</span>
                            <span class="badge badge-role">{{ $transaction->type?->label() ?? 'نامشخص' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">اپراتور / ثبت‌کننده:</span>
                            <span class="text-dark">
                                @if($transaction->referenceable_type === \App\Models\User::class)
                                    {{ $transaction->referenceable->first_name ?? '' }} {{ $transaction->referenceable->last_name ?? '' }} ({{ $transaction->referenceable->mobile }})
                                @else
                                    سیستمی
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">تاریخ و ساعت ثبت:</span>
                            <span class="text-dark">
                                {{ \Morilog\Jalali\Jalalian::fromDateTime($transaction->created_at)->format('H:i:s - Y/m/d') }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- توضیحات --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-card-text text-primary ms-1"></i> شرح / علت عملیات
                </div>
                <div class="card-body">
                    <div class="p-3 bg-light rounded text-dark lh-lg">
                        {!! !empty($transaction->description) ? $transaction->description : '<span class="text-muted">بدون توضیحات ثبت شده است.</span>' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
