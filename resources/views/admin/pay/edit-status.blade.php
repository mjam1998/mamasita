@extends('admin.layout.master')

@section('content')
    <div >
        <div class="card border-0 shadow-sm rounded-4 p-4 mx-auto" ">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                <h5 class="fw-bold mb-0">تعیین وضعیت پرداخت</h5>
                <a href="{{ route('admin.pay.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-right"></i> بازگشت
                </a>
            </div>

            {{-- نمایش خلاصه پرداخت --}}
            <div class="bg-light rounded-3 p-3 mb-4">
                <div class="row g-2 text-sm">
                    <div class="col-sm-6">
                        <span class="text-muted">کد پیگیری:</span>
                        <strong class="text-dark">{{ $adminPay->track_number }}</strong>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-muted">نوع پرداخت:</span>
                        <span class="badge bg-secondary">{{ $adminPay->type?->label() }}</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-muted">کاربر:</span>
                        <strong>{{ $adminPay->user?->first_name . ' ' . $adminPay->user?->last_name }}</strong>
                        <small class="text-muted">({{ $adminPay->user?->mobile }})</small>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-muted">مبلغ:</span>
                        <strong class="text-success">{{ number_format($adminPay->amount) }} تومان</strong>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-muted">شماره شبا:</span>
                        <span dir="ltr">{{ $adminPay->iban_number ?? '---' }}</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-muted">شماره کارت:</span>
                        <span dir="ltr">{{ $adminPay->card_number ?? '---' }}</span>
                    </div>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger mb-4">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.pay.status.update', $adminPay) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- انتخاب وضعیت --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-medium">وضعیت جدید <span class="text-danger">*</span></label>
                        <select name="status" id="status-select" class="form-select @error('status') is-invalid @enderror">
                            @foreach($statuses as $st)
                                <option value="{{ $st->value }}" {{ old('status', $adminPay->status->value) == $st->value ? 'selected' : '' }}>
                                    {{ $st->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- فیلد تاریخ پرداخت (فقط برای پرداخت‌شده) --}}
                    <div class="col-12 col-md-6" id="paid-at-container">
                        <label class="form-label fw-medium">تاریخ پرداخت شمسی <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text"
                                   name="paid_at"
                                   id="paid-at-datepicker"
                                   class="form-control @error('paid_at') is-invalid @enderror"
                                   placeholder="مثال: 1403/8/15"
                                   value="{{ old('paid_at') }}"
                                   autocomplete="off">
                            <span class="input-group-text bg-white" id="calendar-icon">
                            <i class="bi bi-calendar-check"></i>
                        </span>
                        </div>
                        @error('paid_at')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mt-4 text-end">
                        <button type="submit" class="btn btn-theme-primary px-4">
                            <i class="bi bi-check2-circle me-1"></i> ثبت تغییرات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const statusSelect = $('#status-select');
            const paidAtContainer = $('#paid-at-container');
            const dateInput = $('#paid-at-datepicker');

            function togglePaidDateField() {
                // مقدار ۱ مربوط به Paid است
                if (statusSelect.val() == '1') {
                    paidAtContainer.slideDown(200);
                } else {
                    paidAtContainer.slideUp(200);
                }
            }

            statusSelect.on('change', togglePaidDateField);
            togglePaidDateField();

            if (dateInput.length) {
                dateInput.persianDatepicker({
                    format: 'YYYY/MM/DD',
                    autoClose: true,
                    initialValue: true
                });

                $('#calendar-icon').on('click', function() {
                    dateInput.focus();
                });
            }
        });
    </script>
@endpush
