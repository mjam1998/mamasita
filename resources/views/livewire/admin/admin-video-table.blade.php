<div>
    {{-- باکس فیلترها --}}
    <div class="card mb-4 border-0 shadow-sm p-3">
        <div class="row g-2 align-items-end">

            {{-- عنوان ویدئو --}}
            <div class="col-12 col-md-6 col-lg-3">
                <label class="form-label small text-muted">عنوان ویدئو</label>
                <input type="text" wire:model.defer="searchInput"
                       class="form-control custom-input" placeholder="مثال: T123...">
            </div>

            {{-- دوره --}}
            <div class="col-12 col-md-6 col-lg-3" wire:ignore>
                <label class="form-label small text-muted">دوره</label>
                <select id="course-select" class="form-select"></select>
            </div>

            {{-- نوع --}}
            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <label class="form-label small text-muted">نوع</label>
                <select wire:model.defer="typeInput" class="form-select custom-select">
                    <option value="">همه</option>
                    @foreach($types as $tp)
                        <option value="{{ $tp->value }}">{{ $tp->label() }}</option>
                    @endforeach
                </select>
            </div>

            {{-- تاریخ ایجاد --}}
            <div class="col-12 col-sm-6 col-md-4 col-lg-2" wire:ignore>
                <label class="form-label small text-muted">تاریخ ایجاد</label>
                <div class="input-group">
                    <input type="text"
                           id="persian-datepicker-input"
                           class="form-control custom-input"
                           placeholder="1403/01/01"
                           autocomplete="off">
                    <span class="input-group-text bg-white border-start-0 cursor-pointer"
                          id="pdp-calendar-icon">
                    <i class="bi bi-calendar-event"></i>
                </span>
                </div>
            </div>

            {{-- دکمه‌ها --}}
            <div class="col-12 col-md-4 col-lg-2 d-flex gap-1">
                <button wire:click="applySearch"
                        class="btn btn-theme-primary flex-grow-1">
                    <i class="bi bi-search"></i>
                </button>
                <button wire:click="resetFilters"
                        class="btn btn-outline-secondary">
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
                <th class="text-center">عنوان</th>
                <th class="text-center">دوره</th>
                <th class="text-center">نوع</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">تاریخ</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($videos as $video)
                <tr>

                    <td class="text-center" title="{{ $video->title }}">
                        {{ \Illuminate\Support\Str::limit($video->title, 20) }}
                    </td>


                    <td class="text-center" title="{{ $video->course?->name }}">
                        {{ \Illuminate\Support\Str::limit($video->course?->name, 20) }}
                    </td>

                    <td class="text-center" >
                        {{$video->type->label() }}
                    </td>

                    <td class="text-center">
                        @if($video->is_active)
                            <span class="badge badge-status-active">فعال</span>
                        @else
                            <span class="badge badge-status-inactive">غیرفعال</span>
                        @endif
                    </td>

                    <td class="text-center text-muted">
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($video->created_at)->format('Y/m/d') }}
                    </td>

                    <td class="text-center">
                        <a href="{{route('admin.videos.edit',['video'=>$video])}}" class="btn btn-sm btn-outline-primary"><i class="bi bi-chat-dots"></i> مشاهده</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        ویدئویی با این مشخصات یافت نشد.
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

                    fetch("{{ route('admin.courses.search') }}", {
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

            const courseChoices = initChoices('course-select', 'selectedCourseId');

            window.addEventListener('filters-reset', () => {
                // ۱. پاک کردن سلکت‌باکس‌ها
                courseChoices.removeActiveItems();


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
