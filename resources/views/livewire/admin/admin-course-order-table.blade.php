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

            {{-- دوره --}}
            <div class="col-12 col-md-6 col-lg-3" wire:ignore>
                <label class="form-label small text-muted">دوره</label>
                <select id="course-select" class="form-select"></select>
            </div>


            {{-- وضعیت --}}
            <div class="col-12 col-md-3 col-lg-2">
                <label class="form-label small text-muted">وضعیت</label>
                <select wire:model.defer="statusInput" class="form-select custom-select">
                    <option value="">همه ...</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
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
                <th class="text-center">دوره</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($courseOrders as $courseOrder)
                <tr>
                    <td class="text-center fw-bold text-primary">{{ $courseOrder->track_number }}</td>

                    {{-- کاربر --}}
                    <td class="text-center">
                        <div class="fw-bold">{{ $courseOrder->user->first_name }} {{ $courseOrder->user->last_name }}</div>
                        <small class="text-muted d-block" dir="ltr">{{ $courseOrder->user->mobile }}</small>
                    </td>


                    <td class="text-center" title="{{ $courseOrder->course?->name }}">
                        {{ \Illuminate\Support\Str::limit($courseOrder->course?->name, 20) }}
                    </td>

                    <td class="text-center ">
                         <span class="badge badge-primary text-danger border border-danger-subtle">
                              {{ $courseOrder->status->label() }}
                            </span>

                    </td>

                    <td class="text-center text-muted">
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($courseOrder->created_at)->format('Y/m/d') }}
                    </td>

                    <td class="text-center align-middle">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-action dropdown-toggle"
                                    type="button"
                                    id="userActionDropdown-{{ $courseOrder->id }}"
                                    data-bs-toggle="dropdown"
                                    data-bs-popper-config='{"strategy":"fixed"}'
                                    aria-expanded="false">
                                عملیات
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu shadow-sm" aria-labelledby="userActionDropdown-{{ $courseOrder->id }}">
                                <li>
                                    <a href="{{ route('admin.course-orders.show', ['course_order' => $courseOrder]) }}" class="dropdown-item d-flex align-items-center">
                                        <i class="bi bi-pencil-square ms-2 me-1 fs-6"></i>
                                        <span>مشاهده</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        سفارش دوره با این مشخصات یافت نشد.
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
            function initChoices(elementId, wireProperty, fetchUrl) {
                const el = document.getElementById(elementId);
                const instance = new Choices(el, { searchEnabled: true, placeholder: true });

                el.addEventListener('search', function (event) {
                    const query = event.detail.value;
                    if (query.length < 2) return;

                    fetch(fetchUrl, {
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

            // کاربر
            const userChoices = initChoices(
                'user-select',
                'selectedUserId',
                "{{ route('admin.users.search') }}"
            );

            // دوره — همین بخش قبلاً نبود
            const courseChoices = initChoices(
                'course-select',
                'selectedCourseId',
                "{{ route('admin.courses.search') }}"
            );

            window.addEventListener('filters-reset', () => {
                userChoices.removeActiveItems();
                courseChoices.removeActiveItems(); // این هم اضافه شد

                const dateInput = document.getElementById('persian-datepicker-input');
                if (dateInput) {
                    dateInput.value = '';
                    dateInput.dispatchEvent(new Event('input'));
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

