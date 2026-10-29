@extends('admin.layout.master')

@section('content')

    {{-- هدر صفحه --}}
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h5 class="fw-bold m-0" style="color: var(--primary);">
                <i class="bi bi-grid-fill ms-1"></i> حوزه‌های فعالیت و تقاضای ماما: {{ $user->first_name . ' ' . $user->last_name }}
            </h5>
            <small class="text-muted">مشاهده، افزودن تقاضای جدید برای ماما و تعیین وضعیت هر حوزه</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.mamas.edit', $user) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-person-vcard"></i> ویرایش مدارک
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.mamas.fields.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            @foreach($fields as $field)
                @php
                    $currentRecord = $existingTypes->get($field->value);
                    $isRegistered  = !is_null($currentRecord);
                    $currentStatus = $currentRecord?->status ?? \App\Enums\Status::Pending;

                    $icon = match($field) {
                        \App\Enums\MamaFiled::OnlineVisit => 'bi-camera-video-fill',
                        \App\Enums\MamaFiled::MonthlySubscription => 'bi-calendar2-check-fill',
                        \App\Enums\MamaFiled::DoulaSubscription => 'bi-heart-pulse-fill',
                    };
                @endphp

                <div class="col-12 col-md-4">
                    <div class="card h-100 border-0 p-3"
                         style="background: #ffffff; border-radius: var(--radius); box-shadow: var(--shadow); border: 1px solid var(--border) !important;">

                        {{-- سربرگ کارت --}}
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center justify-content-center rounded-3"
                                     style="width: 44px; height: 44px; background: var(--primary-soft); color: var(--primary);">
                                    <i class="bi {{ $icon }} fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="color: var(--text-dark);">{{ $field->label() }}</h6>
                                </div>
                            </div>

                            {{-- وضعیت کنونی تقاضا --}}
                            @if($isRegistered)
                                @if($currentStatus === \App\Enums\Status::Active)
                                    <span class="badge bg-success-subtle text-success px-2 py-1">فعال</span>
                                @elseif($currentStatus === \App\Enums\Status::Inactive)
                                    <span class="badge bg-danger-subtle text-danger px-2 py-1">غیرفعال</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning px-2 py-1">در انتظار بررسی</span>
                                @endif
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1">بدون تقاضا</span>
                            @endif
                        </div>

                        {{-- سوییچ اضافه کردن / حذف تقاضا --}}
                        <div class="p-2 mb-3 rounded-2 d-flex justify-content-between align-items-center" style="background: var(--canvas);">
                            <label class="form-check-label small fw-bold mb-0 cursor-pointer" for="enable_field_{{ $field->value }}">
                                <i class="bi bi-plus-circle me-1" style="color: var(--primary);"></i> ثبت / وجود تقاضا
                            </label>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input field-toggle cursor-pointer"
                                       type="checkbox"
                                       role="switch"
                                       id="enable_field_{{ $field->value }}"
                                       name="enabled_fields[]"
                                       value="{{ $field->value }}"
                                       data-target="select-status-{{ $field->value }}"
                                    {{ old('enabled_fields') ? (in_array($field->value, old('enabled_fields', [])) ? 'checked' : '') : ($isRegistered ? 'checked' : '') }}>
                            </div>
                        </div>

                        {{-- تاریخچه --}}
                        <div class="mb-3 p-2 rounded-2" style="background: #fafafa; font-size: 0.8rem; color: var(--text-mid); border: 1px dashed var(--border);">
                            @if($isRegistered)
                                <div class="d-flex justify-content-between mb-1">
                                    <span>ثبت تقاضا:</span>
                                    <span class="fw-bold">{{ jdate($currentRecord->created_at)->format('Y/m/d - H:i') }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>آخرین تغییر وضعیت:</span>
                                    <span class="fw-bold">{{ jdate($currentRecord->updated_at)->format('Y/m/d - H:i') }}</span>
                                </div>

                            @else
                                <div class="text-muted text-center py-1">
                                    <small><i class="bi bi-info-circle me-1"></i> با فعال کردن سوئیچ بالا، تقاضا اضافه می‌شود.</small>
                                </div>
                            @endif
                        </div>

                        {{-- تعیین وضعیت --}}
                        <div class="mt-auto">
                            <label class="form-label small fw-bold" style="color: var(--text-mid);">تعیین وضعیت حوزه:</label>
                            <select name="status[{{ $field->value }}]"
                                    id="select-status-{{ $field->value }}"
                                    class="form-select custom-select">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->value }}"
                                        {{ (old('status.'.$field->value, $currentStatus->value) == $status->value) ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        {{-- دکمه‌ها --}}
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="{{ url()->previous() }}" class="btn btn-theme-cancel">انصراف</a>
            <button type="submit" class="btn btn-theme-primary px-4">
                <i class="bi bi-check-lg ms-1"></i> ذخیره تغییرات و تقاضاها
            </button>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggles = document.querySelectorAll('.field-toggle');

            function updateSelectState(checkbox) {
                const targetSelect = document.getElementById(checkbox.dataset.target);
                if (!targetSelect) return;

                if (checkbox.checked) {
                    targetSelect.removeAttribute('disabled');
                    targetSelect.classList.remove('opacity-50');
                } else {
                    targetSelect.setAttribute('disabled', 'disabled');
                    targetSelect.classList.add('opacity-50');
                }
            }

            toggles.forEach(toggle => {
                updateSelectState(toggle);
                toggle.addEventListener('change', function () {
                    updateSelectState(this);
                });
            });
        });
    </script>
@endpush
