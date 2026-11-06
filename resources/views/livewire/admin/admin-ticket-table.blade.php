<div>
    {{-- باکس فیلترها --}}
    <div class="card mb-4 border-0 shadow-sm p-3">
        <div class="row g-2 align-items-end">
            {{-- کد پیگیری --}}
            <div class="col-12 col-md-4 col-lg-2">
                <label class="form-label small text-muted">کد پیگیری</label>
                <input type="text" wire:model.defer="searchInput" class="form-control custom-input" placeholder="مثال: T123...">
            </div>

            {{-- کاربر --}}
            <div class="col-12 col-md-4 col-lg-2" wire:ignore>
                <label class="form-label small text-muted">کاربر</label>
                <select id="user-select" class="form-select"></select>
            </div>

            {{-- ماما --}}
            <div class="col-12 col-md-4 col-lg-2" wire:ignore>
                <label class="form-label small text-muted">ماما</label>
                <select id="mama-select" class="form-select"></select>
            </div>

            {{-- وضعیت --}}
            <div class="col-12 col-md-3 col-lg-1">
                <label class="form-label small text-muted">وضعیت</label>
                <select wire:model.defer="statusInput" class="form-select custom-select">
                    <option value="">همه</option>
                    <option value="open">باز</option>
                    <option value="closed">بسته</option>
                </select>
            </div>

            {{-- حل شده؟ --}}
            <div class="col-12 col-md-3 col-lg-1">
                <label class="form-label small text-muted">حل شده؟</label>
                <select wire:model.defer="isResolvedInput" class="form-select custom-select">
                    <option value="">همه</option>
                    <option value="1">بله</option>
                    <option value="0">خیر</option>
                </select>
            </div>

            {{-- فیلتر تاریخ ایجاد --}}
            <div class="col-12 col-md-2" wire:ignore>
                <label class="form-label small text-muted">تاریخ ایجاد</label>
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

            <div class="col-12 col-md-2 d-flex gap-1">
                <button wire:click="applySearch" class="btn btn-theme-primary flex-grow-1"><i class="bi bi-search"></i></button>
                <button wire:click="resetFilters" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></button>
            </div>
        </div>
    </div>

    {{-- جدول --}}
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
            <tr>
                <th class="text-center">کد پیگیری</th>
                <th class="text-center">کاربر</th>
                <th class="text-center">ماما</th>
                <th class="text-center">عنوان</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($tickets as $ticket)
                <tr>
                    <td class="text-center fw-bold text-primary">{{ $ticket->track_number }}</td>

                    {{-- کاربر --}}
                    <td class="text-center">
                        <div class="fw-bold">{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</div>
                        <small class="text-muted d-block" dir="ltr">{{ $ticket->user->mobile }}</small>
                    </td>

                    {{-- ماما --}}
                    <td class="text-center">
                        @if($ticket->mama)
                            <div class="fw-bold">{{ $ticket->mama->first_name }} {{ $ticket->mama->last_name }}</div>
                            <small class="text-muted d-block" dir="ltr">{{ $ticket->mama->mobile }}</small>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">پشتیبانی سایت</span>
                        @endif
                    </td>

                    <td class="text-center" title="{{ $ticket->title }}">
                        {{ \Illuminate\Support\Str::limit($ticket->title, 20) }}
                    </td>

                    <td class="text-center">
                        <span class="badge {{ $ticket->status === 'open' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                            {{ $ticket->status === 'open' ? 'باز' : 'بسته' }}
                        </span>
                        @if($ticket->is_resolved)
                            <span class="badge bg-info-subtle text-info border">حل شده</span>
                        @endif
                    </td>

                    <td class="text-center text-muted">
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($ticket->created_at)->format('Y/m/d') }}
                    </td>

                    <td class="text-center">
                        <a href="{{route('admin.ticket.show',['ticket'=>$ticket])}}" class="btn btn-sm btn-outline-primary"><i class="bi bi-chat-dots"></i> مشاهده</a>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            تیکتی با این مشخصات یافت نشد.
                        </td>
                    </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function initChoices(elementId, wireProperty) {
                const el = document.getElementById(elementId);
                const instance = new Choices(el, { searchEnabled: true, placeholder: true });

                el.addEventListener('search', function (event) {
                    const query = event.detail.value;
                    if (query.length < 2) return;

                    fetch("{{ route('admin.users.search') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ q: query })
                    })
                        .then(res => res.json())
                        .then(data => instance.setChoices(data, 'value', 'label', true));
                });

                el.addEventListener('change', () => @this.set(wireProperty, el.value));
                return instance;
            }

            const userChoices = initChoices('user-select', 'selectedUserId');
            const mamaChoices = initChoices('mama-select', 'selectedMamaId');

            window.addEventListener('filters-reset', () => {
                // ۱. پاک کردن سلکت‌باکس‌ها
                userChoices.removeActiveItems();
                mamaChoices.removeActiveItems();

                // ۲. پاک کردن فیلد تاریخ
                const dateInput = document.getElementById('persian-datepicker-input');
                if (dateInput) {
                    dateInput.value = '';
                    dateInput.dispatchEvent(new Event('input')); // اطلاع به لایووایر
                    dateInput.dispatchEvent(new Event('change'));
                }
            });
            const dateInput = $('#persian-datepicker-input');
            if (dateInput.length) {
                dateInput.persianDatepicker({
                    format: 'YYYY/MM/DD',
                    autoClose: true,
                    initialValue: false,
                    onSelect: function (unix) {
                        const selectedVal = dateInput.val();
                    @this.set('dateInput', selectedVal);
                    }
                });

                $('#pdp-calendar-icon').on('click', function () {
                    dateInput.focus();
                });
            }

        });
    </script>
@endpush
