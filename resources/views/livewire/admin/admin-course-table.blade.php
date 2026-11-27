<div>
    {{-- باکس فیلترها --}}
    <div class="card mb-4 border-0 shadow-sm p-3">
        <div class="row g-2 align-items-end">
            {{-- کد پیگیری --}}
            <div class="col-12 col-md-4 col-lg-2">
                <label class="form-label small text-muted">نام دوره</label>
                <input type="text" wire:model.defer="searchInput" class="form-control custom-input" placeholder="مثال: T123...">
            </div>

            <div class="col-12 col-md-4 col-lg-2">
                <label class="form-label small text-muted">اسلاگ</label>
                <input type="text" wire:model.defer="slugInput" class="form-control custom-input" placeholder="مثال: T123...">
            </div>

            <div class="col-12 col-md-4 col-lg-2">
                <label class="form-label small text-muted">کد اسپات پلیر</label>
                <input type="text" wire:model.defer="spotCodeInput" class="form-control custom-input" placeholder="مثال: T123...">
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
                <th class="text-center">نام دوره</th>
                <th class="text-center">اسلاگ</th>
                <th class="text-center">کد اسپات</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($courses as $course)
                <tr>
                    <td class="text-center fw-medium" title="{{ $course->name }}">
                        {{ \Illuminate\Support\Str::limit($course->name, 25) }}
                    </td>

                    <td class="text-center fw-medium" title="{{ $course->slug }}">
                        {{ \Illuminate\Support\Str::limit($course->slug, 25) }}
                    </td>
                    <td class="text-center fw-medium" title="{{ $course->spot_player_code }}">
                        {{ \Illuminate\Support\Str::limit($course->spot_player_code, 25) }}
                    </td>

                    <td class="text-center">
                        @if($course->is_active)
                            <span class="badge badge-status-active">فعال</span>
                        @else
                            <span class="badge badge-status-inactive">غیرفعال</span>
                        @endif
                    </td>

                    <td class="text-center text-muted">
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($course->created_at)->format('Y/m/d') }}
                    </td>

                    <td class="text-center align-middle">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-action dropdown-toggle"
                                    type="button"
                                    id="userActionDropdown-{{ $course->id }}"
                                    data-bs-toggle="dropdown"
                                    data-bs-popper-config='{"strategy":"fixed"}'
                                    aria-expanded="false">
                                عملیات
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu shadow-sm" aria-labelledby="userActionDropdown-{{ $course->id }}">
                                <li>
                                    <a href="{{ route('admin.courses.edit', ['course' => $course]) }}" class="dropdown-item d-flex align-items-center">
                                        <i class="bi bi-pencil-square ms-2 me-1 fs-6"></i>
                                        <span>ویرایش</span>
                                    </a>
                                </li>


                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        دوره ای با این مشخصات یافت نشد.
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


            window.addEventListener('filters-reset', () => {

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


