@extends('admin.layout.master')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: var(--primary);">
            <i class="bi bi-info-circle ms-1"></i> جزئیات پرداخت #{{ $adminPay->track_number }}
        </h5>
        <a href="{{ route('admin.pay.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت به لیست
        </a>
    </div>

    <div class="card p-4 border-0 shadow-sm">
        <div class="row g-4">
            <div class="col-md-4">
                <span class="text-muted d-block small">کاربر / ماما:</span>
                <strong>{{ $adminPay->user?->first_name . ' '. $adminPay->user?->last_name }} ({{ $adminPay->user?->mobile }})</strong>
            </div>

            <div class="col-md-4">
                <span class="text-muted d-block small">نوع تراکنش:</span>

                    <span class="badge bg-info text-dark">{{$adminPay->type->label()}}</span>

            </div>

            <div class="col-md-4">
                <span class="text-muted d-block small">مبلغ پرداختی:</span>
                <strong class="text-success">{{ number_format($adminPay->amount) }} تومان</strong>
            </div>

            <div class="col-md-4">
                <span class="text-muted d-block small">کد پیگیری سیستمی:</span>
                <code>{{ $adminPay->track_number }}</code>
            </div>

            <div class="col-md-4">
                <span class="text-muted d-block small">اطلاعات حساب و بانک:</span>
                <span>بانک {{ $adminPay->bank_name }} | کارت: {{ $adminPay->card_number }}</span>
            </div>

            <div class="col-md-4">
                <span class="text-muted d-block small">شماره شبا:</span>
                <span>IR{{ $adminPay->iban_number }}</span>
            </div>

            <div class="col-md-4">
                <span class="text-muted d-block small">وضعیت پرداخت:</span>
                <span class="badge badge-role">{{$adminPay->status->label()}}</span>
            </div>

            <div class="col-md-4">
                <span class="text-muted d-block small">تاریخ پرداخت:</span>
                <span>{{ $adminPay->paid_at ? \Carbon\Carbon::parse($adminPay->paid_at)->format('Y/m/d ') : '-' }}</span>
            </div>

            @if($adminPay->user_pay_id)
                <div class="col-md-4">
                    <span class="text-muted d-block small">تراکنش مرجع کاربر:</span>
                    <span>شناسه پرداخت:  {{ $adminPay->userPay?->track_number }}</span>
                </div>
            @endif
        </div>
    </div>

@endsection
