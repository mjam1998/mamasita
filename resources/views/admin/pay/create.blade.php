@extends('admin.layout.master')


@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: var(--primary);">
            <i class="bi bi-cash-stack ms-1"></i> ثبت و واریز پرداخت جدید
        </h5>
        <a href="{{ route('admin.pay.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت به لیست
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.pay.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            {{-- نوع پرداخت --}}
            <div class="col-12 col-md-6">
                <label class="form-label">نوع پرداخت <span class="text-danger">*</span></label>
                <select name="type" id="pay_type" class="form-select custom-select @error('type') is-invalid @enderror">
                    <option value="1" @selected(old('type') == '1')>سهم ماما (کسر از کیف پول)</option>
                    <option value="2" @selected(old('type') == '2')>بازگشت وجه به کاربر (کسر از درآمد پلتفرم)</option>
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- شناسه کاربر انتخاب شده --}}
            <input type="hidden" name="user_id" id="hidden_user_id" value="{{ old('user_id') }}">

            {{-- انتخاب ماما (حالت ۱) --}}
            <div class="col-12 col-md-6" id="mama-section">
                <label class="form-label">انتخاب ماما <span class="text-danger">*</span></label>
                <select id="mama_select" class="form-control"></select>
                @error('user_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- انتخاب پرداخت کاربر (حالت ۲) --}}
            <div class="col-12 col-md-6" id="refund-section" style="display: none;">
                <label class="form-label">کد تراکنش پرداخت کاربر (Track Number) <span class="text-danger">*</span></label>
                <select name="user_pay_id" id="user_pay_select" class="form-control"></select>
                @error('user_pay_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- مبلغ پرداختی --}}
            <div class="col-12 col-md-6">
                <label class="form-label">مبلغ پرداختی (تومان) <span class="text-danger">*</span></label>
                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" min="1000" step="1000"
                       class="form-control custom-input @error('amount') is-invalid @enderror" placeholder="مثلاً: 500000">
                @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- تاریخ پرداخت --}}
            <div class="col-12 col-md-6">
                <label class="form-label">تاریخ پرداخت <span class="text-danger">*</span></label>
                <input  id="persianDate" name="paid_at" value="{{ old('paid_at') }}"
                       class="form-control custom-input @error('paid_at') is-invalid @enderror">
                @error('paid_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- نام بانک --}}
            <div class="col-12 col-md-4">
                <label class="form-label">نام بانک <span class="text-danger">*</span></label>
                <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                       class="form-control custom-input @error('bank_name') is-invalid @enderror" placeholder="مثلاً: بانک ملت">
                @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- شماره شبا --}}
            <div class="col-12 col-md-4">
                <label class="form-label">شماره شبا (بدون IR) <span class="text-danger">*</span></label>
                <input type="text" name="iban_number" value="{{ old('iban_number') }}" maxlength="24"
                       class="form-control custom-input @error('iban_number') is-invalid @enderror" placeholder="24 رقم">
                @error('iban_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- شماره کارت --}}
            <div class="col-12 col-md-4">
                <label class="form-label">شماره کارت <span class="text-danger">*</span></label>
                <input type="text" name="card_number" value="{{ old('card_number') }}" maxlength="16"
                       class="form-control custom-input @error('card_number') is-invalid @enderror" placeholder="16 رقم">
                @error('card_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.pay.index') }}" class="btn btn-theme-cancel">انصراف</a>
                <button type="submit" class="btn btn-theme-primary px-4">تایید و پرداخت نهایی</button>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const payType = document.getElementById('pay_type');
            const mamaSection = document.getElementById('mama-section');
            const refundSection = document.getElementById('refund-section');
            const hiddenUserId = document.getElementById('hidden_user_id');
            const amountInput = document.getElementById('amount');

            // سوییچ بین انواع پرداخت
            function toggleSections() {
                if (payType.value === '1') {
                    mamaSection.style.display = 'block';
                    refundSection.style.display = 'none';
                } else {
                    mamaSection.style.display = 'none';
                    refundSection.style.display = 'block';
                }
            }

            payType.addEventListener('change', toggleSections);
            toggleSections();

            const baseChoicesConfig = {
                searchEnabled: true,
                searchResultLimit: 15,
                shouldSort: false,
                removeItemButton: true,
                noResultsText: 'موردی یافت نشد',
                noChoicesText: 'برای جستجو حداقل ۲ کاراکتر تایپ کنید...',
                itemSelectText: 'انتخاب',
                loadingText: 'در حال بارگذاری...'
            };

            // ۱. تنظیم Choices برای انتخاب ماما
            const mamaChoices = new Choices('#mama_select', {
                ...baseChoicesConfig,
                placeholderValue: 'نام یا موبایل ماما را جستجو کنید...'
            });

            let mamaTimer = null;
            document.getElementById('mama_select').addEventListener('search', function (e) {
                const term = e.detail.value ? e.detail.value.trim() : '';
                if (term.length < 2) return;

                clearTimeout(mamaTimer);
                mamaTimer = setTimeout(() => {
                    fetch(`{{ route('admin.pay.search.mamas') }}?q=${encodeURIComponent(term)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(res => {
                            if (!res.ok) throw new Error('HTTP Error: ' + res.status);
                            return res.json();
                        })
                        .then(data => {
                            mamaChoices.setChoices(data, 'value', 'label', true);
                        })
                        .catch(err => console.error('خطای جستجوی ماما:', err));
                }, 300);
            });

            document.getElementById('mama_select').addEventListener('change', function (e) {
                hiddenUserId.value = e.detail.value || '';
            });

            // ۲. تنظیم Choices برای انتخاب پرداخت کاربر
            const userPayChoices = new Choices('#user_pay_select', {
                ...baseChoicesConfig,
                placeholderValue: 'کد رهگیری تراکنش را وارد کنید...'
            });

            let userPayTimer = null;
            document.getElementById('user_pay_select').addEventListener('search', function (e) {
                const term = e.detail.value ? e.detail.value.trim() : '';
                if (term.length < 2) return;

                clearTimeout(userPayTimer);
                userPayTimer = setTimeout(() => {
                    fetch(`{{ route('admin.pay.search.user-pays') }}?q=${encodeURIComponent(term)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(res => {
                            if (!res.ok) throw new Error('HTTP Error: ' + res.status);
                            return res.json();
                        })
                        .then(data => {
                            userPayChoices.setChoices(data, 'value', 'label', true);
                        })
                        .catch(err => console.error('خطای جستجوی پرداخت کاربر:', err));
                }, 300);
            });

            document.getElementById('user_pay_select').addEventListener('change', function () {
                const selected = userPayChoices.getValue();
                if (selected && selected.customProperties) {
                    hiddenUserId.value = selected.customProperties.user_id;
                    amountInput.value = selected.customProperties.amount;
                } else {
                    hiddenUserId.value = '';
                    amountInput.value = '';
                }
            });
        });
    </script>
@endpush

