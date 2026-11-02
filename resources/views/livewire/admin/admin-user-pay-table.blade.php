<div>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 class="fw-bold mb-1">تراکنش‌ها و پرداخت‌های کاربران</h5>
            <small class="text-muted">مشاهده فاکتورها، خرید دوره‌ها، ویزیت‌ها و اشتراک‌ها</small>
        </div>
    </div>

    {{-- باکس فیلترها --}}
    <div class="card mb-4 border-0 shadow-sm p-3">
        <div class="row g-2 align-items-end">
            {{-- فیلتر کد پیگیری --}}
            <div class="col-12 col-md-2">
                <label class="form-label small text-muted">کد پیگیری</label>
                <input type="text"
                       wire:model.defer="searchInput"
                       wire:keydown.enter="applySearch"
                       class="form-control custom-input"
                       placeholder="مثلاً: TRK-98765">
            </div>

            {{-- فیلتر شماره پیگیری بانک --}}
            <div class="col-12 col-md-2">
                <label class="form-label small text-muted">شماره پیگیری بانک</label>
                <input type="text"
                       wire:model.defer="bankRefInput"
                       wire:keydown.enter="applySearch"
                       class="form-control custom-input"
                       placeholder="مثلاً: 1002345678">
            </div>

            {{-- سلکت باکس کاربر با جستجوی ایجکسی Choices.js --}}
            <div class="col-12 col-md-2" wire:ignore>
                <label class="form-label small text-muted">کاربر (نام یا موبایل)</label>
                <select id="user-select-choices" class="form-select custom-select">
                    <option value="">همه کاربران...</option>
                </select>
            </div>

            {{-- فیلتر نوع خرید --}}
            <div class="col-12 col-md-2">
                <label class="form-label small text-muted">نوع خرید</label>
                <select wire:model.defer="referenceableTypeInput" class="form-select custom-select">
                    <option value="">همه موارد</option>
                    @foreach($referenceableTypes as $class => $label)
                        <option value="{{ $class }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- فیلتر تاریخ پرداخت شمسی --}}
            <div class="col-12 col-md-2" wire:ignore>
                <label class="form-label small text-muted">تاریخ پرداخت</label>
                <div class="input-group">
                    <input type="text"
                           id="persian-datepicker-input"
                           class="form-control custom-input"
                           placeholder="1403/01/01"
                           autocomplete="off">
                    <span class="input-group-text bg-white border-start-0 cursor-pointer" id="pdp-calendar-icon">
                        <i class="bi bi-calendar-event"></i>
                    </span>
                </div>
            </div>

            {{-- دکمه‌های جستجو و ریست --}}
            <div class="col-12 col-md-2 d-flex gap-1">
                <button wire:click="applySearch" class="btn btn-theme-primary w-100" title="اعمال فیلتر">
                    <i class="bi bi-search me-1"></i> فیلتر
                </button>
                <button wire:click="resetFilters" class="btn btn-outline-secondary" title="پاکسازی فیلترها">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>

            {{-- چک‌باکس فقط پرداخت‌شده‌ها --}}
            <div class="col-12 pt-2">
                <div class="form-check form-switch d-inline-flex align-items-center gap-2">
                    <input class="form-check-input"
                           type="checkbox"
                           role="switch"
                           id="onlyPaidCheck"
                           wire:model.defer="onlyPaid">
                    <label class="form-check-label small user-select-none text-secondary" for="onlyPaidCheck">
                        فقط تراکنش‌های پرداخت‌شده (<span class="text-success fw-medium">موفق</span>)
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- جدول پرداخت‌ها --}}
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
            <tr>
                <th class="text-center">کد پیگیری</th>
                <th class="text-center">پیگیری بانک</th>
                <th class="text-center">نام کاربر</th>
                <th class="text-center">شماره موبایل</th>
                <th class="text-center">نوع خرید</th>
                <th class="text-center">مبلغ (تومان)</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">وضعیت پرداخت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($userPays as $userPay)
                <tr>
                    <td class="text-center text-muted fw-bold">
                        {{ $userPay->track_number ?? '---' }}
                    </td>

                    <td class="text-center text-monospace text-muted" dir="ltr">
                        {{ $userPay->bank_ref_number ?? '---' }}
                    </td>

                    <td class="text-center fw-medium">
                        {{ $userPay->user ? ($userPay->user->first_name . ' ' . $userPay->user->last_name) : '---' }}
                    </td>

                    <td class="text-center text-muted" dir="ltr">
                        {{ $userPay->user?->mobile ?? '---' }}
                    </td>

                    {{-- بج نوع خرید --}}
                    <td class="text-center">
                        @php
                            $refType = $userPay->referenceable_type;
                        @endphp
                        @if($refType === \App\Models\Course::class)
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                <i class="bi bi-play-circle me-1"></i> دوره آموزشی
                            </span>
                        @elseif($refType === \App\Models\OnlineVisit::class)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="bi bi-camera-video me-1"></i> ویزیت آنلاین
                            </span>
                        @elseif($refType === \App\Models\MonthlySubscription::class)
                            <span class="badge bg-info-subtle text-info border border-info-subtle">
                                <i class="bi bi-calendar-check me-1"></i> اشتراک ماهیانه
                            </span>
                        @elseif($refType === \App\Models\DoulaSubscription::class)
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                <i class="bi bi-heart me-1"></i> اشتراک ماما همراه
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border">سایر موارد</span>
                        @endif
                    </td>

                    <td class="text-center fw-bold text-dark">
                        {{ number_format($userPay->amount) }}
                    </td>

                    <td class="text-center text-muted">
                        {{ $userPay->paid_at ? \Morilog\Jalali\Jalalian::fromDateTime($userPay->paid_at)->format('Y/m/d H:i') : '---' }}
                    </td>

                    {{-- نمایش وضعیت بر اساس is_paid --}}
                    <td class="text-center">
                        @if($userPay->is_paid)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="bi bi-check-circle me-1"></i> پرداخت‌شده
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                <i class="bi bi-x-circle me-1"></i> پرداخت‌نشده
                            </span>
                        @endif
                    </td>

                    <td class="text-center align-middle">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-action dropdown-toggle"
                                    type="button"
                                    id="userPayAction-{{ $userPay->id }}"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                عملیات
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu shadow-sm"
                                aria-labelledby="userPayAction-{{ $userPay->id }}">
                                <li>
                                    <a href="{{ route('admin.user-pays.show', $userPay->id) }}"
                                       class="dropdown-item d-flex align-items-center">
                                        <i class="bi bi-eye ms-2 me-1 fs-6"></i>
                                        <span>مشاهده فاکتور</span>
                                    </a>
                                </li>
                                @if($userPay->user_id)
                                    <li>
                                        <a href="{{ route('admin.wallets.user', $userPay->user_id) }}"
                                           class="dropdown-item d-flex align-items-center">
                                            <i class="bi bi-wallet2 ms-2 me-1 fs-6"></i>
                                            <span>کیف پول کاربر</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        پرداختی با این مشخصات یافت نشد.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- صفحه‌بندی --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $userPays->links() }}
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ۱. مقداردهی Choices.js با جستجوی ایجکسی
            const userSelectElem = document.getElementById('user-select-choices');
            if (!userSelectElem) return;

            let choicesInstance = new Choices(userSelectElem, {
                searchEnabled: true,
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'نام یا موبایل را بنویسید...',
                noResultsText: 'کاربری یافت نشد',
                itemSelectText: 'کلیک جهت انتخاب',
                loadingText: 'در حال بارگذاری...',
            });

            let searchTimeout = null;
            userSelectElem.addEventListener('search', function (event) {
                const query = event.detail.value;
                if (!query || query.length < 2) return;

                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    fetch("{{ route('admin.users.search') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ q: query }) // کلید q دقیقاً مطابق کنترلر
                    })
                        .then(res => res.json())
                        .then(data => {
                            choicesInstance.setChoices(data, 'value', 'label', true);
                        })
                        .catch(err => console.error('خطا در دریافت کاربران:', err));
                }, 300);
            });

            // اتصال انتخاب Choices به متغیر لایووایر
            userSelectElem.addEventListener('change', function () {
            @this.set('selectedUserId', this.value);
            });

            // ۲. مقداردهی Persian Datepicker
            const dateInput = $('#persian-datepicker-input');
            if (dateInput.length && typeof dateInput.persianDatepicker === 'function') {
                dateInput.persianDatepicker({
                    format: 'YYYY/MM/DD',
                    autoClose: true,
                    initialValue: false,
                    onSelect: function (unix) {
                        const selectedVal = dateInput.val();
                    @this.set('paidDateJalali', selectedVal);
                    }
                });

                $('#pdp-calendar-icon').on('click', function () {
                    dateInput.focus();
                });
            }

            // ریسِت فیلترها از طرف لایووایر
            window.addEventListener('filters-reset', () => {
                choicesInstance.removeActiveItems();
                choicesInstance.setChoices([], 'value', 'label', true);
                dateInput.val('');
            });
        });
    </script>
@endpush

