<div>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>

            <small class="text-muted">مدیریت و مشاهده تاریخچه تراکنش‌ها و پرداخت‌های سایت</small>
        </div>
        <a href="{{ route('admin.pay.create') }}" class="btn btn-theme-primary d-inline-flex align-items-center gap-1 shadow-sm">
            <i class="bi bi-plus-lg"></i>
            <span>افزودن</span>
        </a>
    </div>
    <div class="card mb-4 border-0 shadow-sm p-3">
        <div class="row g-2 align-items-end">
            {{-- جستجو بر اساس کد پیگیری --}}
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted">کد پیگیری</label>
                <input type="text"
                       wire:model.defer="searchInput"
                       wire:keydown.enter="applySearch"
                       class="form-control custom-input"
                       placeholder="مثال: FS657G8...">
            </div>

            {{-- سلکت باکس کاربر با جستجوی ایجکسی Choices.js --}}
            <div class="col-12 col-md-3" wire:ignore>
                <label class="form-label small text-muted">کاربر (نام یا موبایل)</label>
                <select id="user-select-choices" class="form-select custom-select">
                    <option value="">همه کاربران...</option>
                </select>
            </div>

            {{-- فیلتر وضعیت پرداخت --}}
            <div class="col-12 col-md-2">
                <label class="form-label small text-muted">وضعیت</label>
                <select wire:model.defer="statusInput" class="form-select custom-select">
                    <option value="">همه وضعیت‌ها</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st->value }}">{{ $st->label() }}</option>
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

            {{-- دکمه‌های فیلتر --}}
            <div class="col-12 col-md-2 d-flex gap-2">
                <button wire:click="applySearch" class="btn btn-theme-primary w-100">
                    <i class="bi bi-search"></i> فیلتر
                </button>
                <button wire:click="resetFilters" class="btn btn-outline-secondary" title="پاکسازی فیلترها">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- جدول --}}
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
            <tr>
                <th class="text-center">کد پیگیری</th>
                <th class="text-center">نام کاربر</th>
                <th class="text-center">شماره موبایل</th>
                <th class="text-center">تاریخ پرداخت</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($adminPays as $adminPay)
                <tr>
                    <td class="text-center text-muted fw-bold">{{ $adminPay->track_number }}</td>

                    <td class="text-center fw-medium" title="{{  $adminPay->user?->first_name . ' ' . $adminPay->user?->last_name }}">
                        {{ \Illuminate\Support\Str::limit( $adminPay->user?->first_name . ' ' . $adminPay->user?->last_name, 25) }}
                    </td>

                    <td class="text-center text-muted" dir="ltr">
                        {{ $adminPay->user?->mobile ?? '---' }}
                    </td>

                    <td class="text-center text-muted">
                        {{ $adminPay->paid_at ? \Morilog\Jalali\Jalalian::fromCarbon($adminPay->paid_at)->format('Y/m/d H:i') : '---' }}
                    </td>

                    <td class="text-center">
                        <span class="badge badge-role">{{ $adminPay->status?->label() ?? 'نامشخص' }}</span>
                    </td>

                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> مشاهده
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        پرداختی با این مشخصات یافت نشد.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- صفحه‌بندی --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $adminPays->links() }}
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ۱. مقداردهی Choices.js با جستجوی ایجکسی
            const userSelectElem = document.getElementById('user-select-choices');
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

                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    fetch("{{ route('admin.users.search') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ q: query })
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
            if (dateInput.length) {
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
