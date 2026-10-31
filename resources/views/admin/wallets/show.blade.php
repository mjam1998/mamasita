@extends('admin.layout.master')

@section('title', 'کیف پول کاربر: ' . ($user->first_name . ' ' . $user->last_name))

@section('content')


        {{-- هدر صفحه: نام، موبایل و دکمه ایجاد تراکنش --}}
        <div class="card border-0 shadow-sm p-3 mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold text-dark">
                            {{ $user->first_name . ' ' . $user->last_name }}
                        </h5>
                        <div class="text-muted small d-flex gap-3">
                            <span><i class="bi bi-phone me-1"></i>{{ $user->mobile ?? '---' }}</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.wallets.transactions.create', $user) }}" class="btn btn-theme-primary d-inline-flex align-items-center gap-1 shadow-sm">
                        <i class="bi bi-plus-lg"></i>
                        <span>افزودن تراکنش دستی</span>
                    </a>
                </div>
            </div>
        </div>
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- کارت‌های خلاصه وضعیت موجودی --}}
        <div class="row g-3 mb-4">
            {{-- کل موجودی --}}
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm p-3 h-100 border-start border-primary border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small fw-medium mb-1">کل موجودی (Total)</div>
                            <h4 class="mb-0 fw-bold text-dark">
                                {{ number_format($wallet->total_balance) }} <small class="fs-6 text-muted">تومان</small>
                            </h4>
                        </div>
                        <div class="text-primary bg-primary-subtle p-3 rounded-circle">
                            <i class="bi bi-cash-stack fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- موجودی در دسترس --}}
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm p-3 h-100 border-start border-success border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small fw-medium mb-1">موجودی در دسترس (Available)</div>
                            <h4 class="mb-0 fw-bold text-success">
                                {{ number_format($wallet->available_balance) }} <small class="fs-6 text-muted">تومان</small>
                            </h4>
                        </div>
                        <div class="text-success bg-success-subtle p-3 rounded-circle">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- موجودی مسدود شده --}}
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm p-3 h-100 border-start border-warning border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small fw-medium mb-1">موجودی مسدود شده (Blocked)</div>
                            <h4 class="mb-0 fw-bold text-warning">
                                {{ number_format($wallet->blocked_balance) }} <small class="fs-6 text-muted">تومان</small>
                            </h4>
                        </div>
                        <div class="text-warning bg-warning-subtle p-3 rounded-circle">
                            <i class="bi bi-lock fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- کامپوننت جدول تراکنش‌ها --}}
        @livewire('admin.admin-wallet-transaction-table', ['wallet' => $wallet])


@endsection
