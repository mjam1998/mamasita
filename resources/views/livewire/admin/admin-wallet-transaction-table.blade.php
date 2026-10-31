<div>
    {{-- باکس فیلترها --}}
    <div class="card mb-4 border-0 shadow-sm p-3">
        <div class="row g-2 align-items-end">
            {{-- جستجو بر اساس کد پیگیری --}}
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted">کد پیگیری تراکنش</label>
                <input type="text"
                       wire:model.defer="searchInput"
                       wire:keydown.enter="applySearch"
                       class="form-control custom-input"
                       placeholder="مثال: IYB0T8">
            </div>

            {{-- فیلتر نوع عملیات --}}
            <div class="col-12 col-md-3">
                <label class="form-label small text-muted">نوع عملیات (Type)</label>
                <select wire:model.defer="typeInput" class="form-select custom-select">
                    <option value="">همه انواع...</option>
                    @foreach($types as $tp)
                        <option value="{{ $tp->value }}">{{ $tp->label() }}</option>
                    @endforeach
                </select>
            </div>

            {{-- فیلتر جهت تراکنش --}}
            <div class="col-12 col-md-2">
                <label class="form-label small text-muted">جهت (Direction)</label>
                <select wire:model.defer="directionInput" class="form-select custom-select">
                    <option value="">همه جهات...</option>
                    @foreach($directions as $dir)
                        <option value="{{ $dir->value }}">{{ $dir->label() }}</option>
                    @endforeach
                </select>
            </div>

            {{-- فیلتر تاریخ ثبت --}}
            <div class="col-12 col-md-2" wire:ignore>
                <label class="form-label small text-muted">تاریخ ثبت</label>
                <div class="input-group">
                    <input type="text"
                           id="transaction-datepicker-input"
                           class="form-control custom-input"
                           placeholder="1403/01/01"
                           autocomplete="off">
                    <span class="input-group-text bg-white border-start-0 cursor-pointer" id="tx-calendar-icon">
                        <i class="bi bi-calendar-event"></i>
                    </span>
                </div>
            </div>

            {{-- دکمه‌ها --}}
            <div class="col-12 col-md-2 d-flex gap-1">
                <button wire:click="applySearch" class="btn btn-theme-primary w-100" title="فیلتر">
                    <i class="bi bi-search"></i> فیلتر
                </button>
                <button wire:click="resetFilters" class="btn btn-outline-secondary" title="پاکسازی فیلترها">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- جدول لیست تراکنش‌ها --}}
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
            <tr>
                <th class="text-center">کد تراکنش</th>
                <th class="text-center">مبلغ (تومان)</th>
                <th class="text-center">جهت</th>
                <th class="text-center">نوع تراکنش</th>
                <th class="text-center">موجودی آزاد پس از تراکنش</th>
                <th class="text-center">تاریخ تراکنش</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td class="text-center text-muted fw-bold">{{ $transaction->track_number }}</td>

                    {{-- مبلغ تراکنش --}}
                    <td class="text-center fw-bold {{ $transaction->direction === \App\Enums\TransactionDirection::Credit ? 'text-success' : ($transaction->direction === \App\Enums\TransactionDirection::Debit ? 'text-danger' : 'text-primary') }}">
                        {{ $transaction->direction === \App\Enums\TransactionDirection::Credit ? '+' : ($transaction->direction === \App\Enums\TransactionDirection::Debit ? '-' : '') }}
                        {{ number_format($transaction->amount) }}
                    </td>

                    {{-- جهت تراکنش --}}
                    <td class="text-center">
                        @if($transaction->direction === \App\Enums\TransactionDirection::Credit)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                <i class="bi bi-arrow-down-left me-1"></i>{{ $transaction->direction->label() }}
                            </span>
                        @elseif($transaction->direction === \App\Enums\TransactionDirection::Debit)
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                <i class="bi bi-arrow-up-right me-1"></i>{{ $transaction->direction->label() }}
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                <i class="bi bi-arrow-left-right me-1"></i>{{ $transaction->direction->label() }}
                            </span>
                        @endif
                    </td>

                    {{-- نوع تراکنش --}}
                    <td class="text-center">
                        <span class="badge badge-role">{{ $transaction->type?->label() ?? 'نامشخص' }}</span>
                    </td>

                    {{-- موجودی در دسترس پس از تراکنش --}}
                    <td class="text-center text-muted">
                        {{ number_format($transaction->available_balance_after) }}
                    </td>

                    {{-- تاریخ --}}
                    <td class="text-center text-muted">
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($transaction->created_at)->format('H:i - Y/m/d') }}
                    </td>

                    {{-- عملیات --}}
                    <td class="text-center align-middle">
                        <a href="{{ route('admin.wallets.transactions.show', $transaction) }}"
                           class="btn btn-sm btn-outline-primary"
                           title="مشاهده جزئیات تراکنش">
                            <i class="bi bi-eye"></i>
                            <span>مشاهده</span>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        هیچ تراکنشی برای کیف پول این کاربر ثبت نشده است.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- صفحه‌بندی --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $transactions->links() }}
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dateInput = $('#transaction-datepicker-input');
            if (dateInput.length) {
                dateInput.persianDatepicker({
                    format: 'YYYY/MM/DD',
                    autoClose: true,
                    initialValue: false,
                    onSelect: function (unix) {
                    @this.set('createdDateJalali', dateInput.val());
                    }
                });

                $('#tx-calendar-icon').on('click', function () {
                    dateInput.focus();
                });
            }

            window.addEventListener('filters-reset', () => {
                dateInput.val('');
            });
        });
    </script>
@endpush
