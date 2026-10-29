@extends('admin.layout.master')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: var(--primary);">
            <i class="bi bi-person-vcard ms-1"></i>  مدارک و اطلاعات ماما: {{ $mamaInformation?->user->first_name . ' ' . $mamaInformation?->user->last_name }}
        </h5>
        <a href="{{ route('admin.list.mama') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت به لیست
        </a>
    </div>

    <form action="{{ route('admin.mamas.update', ['mama_information'=>$mamaInformation]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- بخش ۱: وضعیت پرونده و تعرفه --}}
        <div class="row g-3 mb-4">
            <div class="col-12">
                <h6 class="fw-bold" style="color: var(--text-dark);">
                    <i class="bi bi-shield-check me-1" style="color: var(--primary);"></i> وضعیت پرونده و تعرفه خدمات
                </h6>
            </div>

            {{-- وضعیت --}}
            <div class="col-12 col-md-6">
                <label class="form-label">وضعیت مدارک ماما<span class="text-danger">*</span></label>
                <select name="status" class="form-select custom-select @error('status') is-invalid @enderror">
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" {{ old('status', $mamaInformation->status instanceof \App\Enums\MamaInformationStatus ? $mamaInformation->status->value : $mamaInformation->status) == $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
                @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            {{-- دستمزد ماما همراه (Doula Price) --}}
            <div class="col-12 col-md-6">
                <label class="form-label">قیمت پیشنهادی ماما همراه (تومان)</label>
                <input type="number" name="doula_price" value="{{ old('doula_price', $mamaInformation->doula_price) }}"
                       class="form-control custom-input @error('doula_price') is-invalid @enderror" placeholder="مثال: 5000000">
                <small class="text-muted"> مبلغ پیشنهادی ماما برای پلن همراهی در زایمان (درصورت نیاز میتوانید قیمت مورد نظر خود را برای ماما در این بخش وارد کنید) </small>
                @error('doula_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

        </div>

            <label class="form-label">پاسخ / یادداشت بازبینی ادمین</label>
            <textarea name="admin_review"
                      rows="3"
                      class="form-control custom-input @error('admin_review') is-invalid @enderror"
                      placeholder="در صورت رد مدارک یا نیاز به ویرایش، علت یا توضیحات را برای ماما اینجا بنویسید...">{{ old('admin_review', $mamaInformation->admin_review) }}</textarea>
            @error('admin_review')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <div class="form-text text-muted fs-7">
                این یادداشت می‌تواند در پنل ماما به عنوان دلیل رد یا نیاز به اصلاح نمایش داده شود.
            </div>
        <hr style="border-color: var(--border);">

        {{-- بخش ۲: موقعیت مکانی --}}
        <div class="row g-3 mb-4">
            <div class="col-12">
                <h6 class="fw-bold" style="color: var(--text-dark);">
                    <i class="bi bi-geo-alt me-1" style="color: var(--primary);"></i> موقعیت فعالیت
                </h6>
            </div>

            {{-- استان --}}
            <div class="col-12 col-md-6">
                <label class="form-label">استان <span class="text-danger">*</span></label>
                <select name="province_id" id="province-select" class="form-select custom-select @error('province_id') is-invalid @enderror">
                    <option value="">انتخاب استان...</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}" {{ old('province_id', $mamaInformation->province_id) == $province->id ? 'selected' : '' }}>
                            {{ $province->name }}
                        </option>
                    @endforeach
                </select>
                @error('province_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            {{-- شهر --}}
            <div class="col-12 col-md-6">
                <label class="form-label">شهر <span class="text-danger">*</span></label>
                <select name="city_id" id="city-select" class="form-select custom-select @error('city_id') is-invalid @enderror">
                    <option value="">ابتدا استان را انتخاب کنید...</option>
                    {{-- در لود اولیه صفحه ویرایش، شهرهای استان فعلی را می‌گذاریم تا آماده باشد --}}
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ old('city_id', $mamaInformation->city_id) == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
                @error('city_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

        </div>

        <hr style="border-color: var(--border);">

        {{-- بخش ۳: اطلاعات مالی و حساب بانکی --}}
        <div class="row g-3 mb-4">
            <div class="col-12">
                <h6 class="fw-bold" style="color: var(--text-dark);">
                    <i class="bi bi-credit-card me-1" style="color: var(--primary);"></i> اطلاعات حساب بانکی
                </h6>
            </div>

            {{-- نام بانک --}}
            <div class="col-12 col-md-4">
                <label class="form-label">نام بانک <span class="text-danger">*</span></label>
                <input type="text" name="bank_name" value="{{ old('bank_name', $mamaInformation->bank_name) }}"
                       class="form-control custom-input @error('bank_name') is-invalid @enderror" placeholder="مثال: ملی، ملت">
                @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- شماره کارت --}}
            <div class="col-12 col-md-4">
                <label class="form-label">شماره کارت بانکی (۱۶ رقم) <span class="text-danger">*</span></label>
                <input type="text" name="card_number" value="{{ old('card_number', $mamaInformation->card_number) }}"
                       maxlength="16" class="form-control custom-input text-center @error('card_number') is-invalid @enderror" placeholder="6037xxxxxxxxxxxx" dir="ltr">
                @error('card_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- شماره شبا --}}
            <div class="col-12 col-md-4">
                <label class="form-label">شماره شبا (۲۴ رقم بدون IR) <span class="text-danger">*</span></label>
                <div class="input-group" dir="ltr">
                    <span class="input-group-text bg-light text-muted fw-bold">IR -</span>
                    <input type="text" name="iban_number" value="{{ old('iban_number', $mamaInformation->iban_number) }}"
                           maxlength="24" class="form-control custom-input text-start @error('iban_number') is-invalid @enderror" placeholder="012345678901234567890124">
                </div>
                @error('iban_number') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        </div>

        <hr style="border-color: var(--border);">

        {{-- بخش ۴: مدارک و فایل‌ها --}}
        <div class="row g-4 mb-4">
            <div class="col-12">
                <h6 class="fw-bold" style="color: var(--text-dark);">
                    <i class="bi bi-file-earmark-arrow-up me-1" style="color: var(--primary);"></i> بارگذاری مدارک و تصاویر
                </h6>
            </div>

            {{-- ۱. تصویر پرسونلی --}}
            <div class="col-12 col-md-4">
                <div class="p-3 border rounded-3 h-100 bg-white shadow-sm">
                    <label class="form-label fw-bold">تصویر پروفایل ماما</label>

                    {{-- پیش نمایش فایل فعلی --}}
                    @if($mamaInformation->image)
                        <div class="mb-3 text-center">
                            <a href="{{ asset('mamas/images/' . $mamaInformation->image) }}" target="_blank">
                                <img src="{{ asset('mamas/images/' . $mamaInformation->image) }}"
                                     alt="{{ $mamaInformation->image_alt }}"
                                     class="img-thumbnail rounded-circle object-fit-cover shadow-sm"
                                     style="width: 110px; height: 110px;">
                            </a>
                            <div class="mt-1"><small class="text-success"><i class="bi bi-check2-circle"></i> تصویر ثبت شده</small></div>
                        </div>
                    @endif

                    <input type="file" name="image" class="form-control custom-input @error('image') is-invalid @enderror" accept="image/*">
                    <small class="text-muted d-block mt-1">فرمت‌های مجاز: JPG, PNG, WEBP (حداکثر 4MB)</small>
                    @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                    {{-- سئو تصویر --}}
                    <div class="mt-3">
                        <label class="form-label small text-muted">Alt تصویر:</label>
                        <input type="text" name="image_alt" value="{{ old('image_alt', $mamaInformation->image_alt) }}" class="form-control form-control-sm">
                    </div>
                    <div class="mt-2">
                        <label class="form-label small text-muted">Title تصویر:</label>
                        <input type="text" name="image_title" value="{{ old('image_title', $mamaInformation->image_title) }}" class="form-control form-control-sm">
                    </div>
                </div>
            </div>

            {{-- ۲. عکس کارت ملی --}}
            <div class="col-12 col-md-4">
                <div class="p-3 border rounded-3 h-100 bg-white shadow-sm">
                    <label class="form-label fw-bold">تصویر کارت ملی</label>


                    {{-- پیش نمایش کارت ملی --}}
                    @if($mamaInformation->national_card_file)
                        <div class="mb-3 text-center">
                            <a href="{{ route('mamas.documents.view', [$mamaInformation, 'national_card_file']) }}" target="_blank">
                                <img src="{{ route('mamas.documents.view', [$mamaInformation, 'national_card_file']) }}"
                                     class="img-fluid rounded border shadow-sm"
                                     style="max-height: 110px; object-fit: contain;">
                            </a>
                            <div class="mt-1">
                                <a href="{{ route('mamas.documents.view', [$mamaInformation, 'national_card_file']) }}" target="_blank" class="small text-decoration-none" style="color: var(--primary);">
                                    <i class="bi bi-eye"></i> مشاهده فایل کارت ملی
                                </a>
                            </div>
                        </div>
                    @endif


                    <input type="file" name="national_card_file" class="form-control custom-input @error('national_card_file') is-invalid @enderror" accept="image/*">
                    <small class="text-muted d-block mt-1">فرمت‌های مجاز: JPG, PNG, WEBP (حداکثر 4MB)</small>
                    @error('national_card_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- ۳. مدرک تحصیلی (PDF یا عکس) --}}
            <div class="col-12 col-md-4">
                <div class="p-3 border rounded-3 h-100 bg-white shadow-sm">
                    <label class="form-label fw-bold">مدرک تحصیلی یا گواهی مامایی</label>

                    {{-- پیش نمایش فایل مدرک --}}
                    @if($mamaInformation->education_file)
                        @php
                            $isPdf = str_ends_with(strtolower($mamaInformation->education_file), '.pdf');
                        @endphp
                        <div class="mb-3 text-center">
                            @if($isPdf)
                                <div class="p-3 rounded bg-light border d-inline-block text-center">
                                    <i class="bi bi-file-earmark-pdf-fill text-danger fs-1"></i>
                                    <div class="mt-1">
                                        <a href="{{ route('mamas.documents.view', [$mamaInformation, 'education_file']) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-download"></i> دانلود / مشاهده PDF
                                        </a>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('mamas.documents.view', [$mamaInformation, 'education_file']) }}" target="_blank">
                                    <img src="{{ route('mamas.documents.view', [$mamaInformation, 'education_file']) }}"
                                         class="img-fluid rounded border shadow-sm"
                                         style="max-height: 110px; object-fit: contain;">
                                </a>
                                <div class="mt-1">
                                    <a href="{{ route('mamas.documents.view', [$mamaInformation, 'education_file']) }}" target="_blank" class="small text-decoration-none" style="color: var(--primary);">
                                        <i class="bi bi-eye"></i> مشاهده تصویر مدرک
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif


                    <input type="file" name="education_file" class="form-control custom-input @error('education_file') is-invalid @enderror" accept=".pdf,image/*">
                    <small class="text-muted d-block mt-1">فرمت‌های مجاز: PDF یا عکس (حداکثر 10MB)</small>
                    @error('education_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- دکمه‌ها --}}
        <div class="col-12 d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="{{ route('admin.list.mama') }}" class="btn btn-theme-cancel">انصراف</a>
            <button type="submit" class="btn btn-theme-primary px-4">
                <i class="bi bi-check-lg ms-1"></i> ذخیره تغییرات و مدارک
            </button>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const provinceSelectEl = document.getElementById('province-select');
            const citySelectEl     = document.getElementById('city-select');

            if (!provinceSelectEl || !citySelectEl) return;

            // ۱. راه‌اندازی Choices برای استان
            const provinceChoices = new Choices(provinceSelectEl, {
                searchEnabled: true,
                placeholderValue: 'انتخاب استان...',
                searchPlaceholderValue: 'جستجوی استان...',
                noResultsText: 'استانی یافت نشد',
                itemSelectText: '',
                shouldSort: false
            });

            // ۲. راه‌اندازی Choices برای شهر
            const cityChoices = new Choices(citySelectEl, {
                searchEnabled: true,
                placeholderValue: 'انتخاب شهر...',
                searchPlaceholderValue: 'جستجوی شهر...',
                noResultsText: 'شهری یافت نشد',
                itemSelectText: '',
                shouldSort: false
            });

            // ذخیره آیدی شهر انتخاب‌شده اولیه (در زمان ویرایش یا بعد از خطای ولیدیشن)
            let selectedCityId = "{{ old('city_id', $mamaInformation->city_id ?? '') }}";

            // ۳. تابع دریافت شهرهای استان از API
            async function fetchCities(provinceId, targetCityId = null) {
                if (!provinceId) {
                    cityChoices.clearStore();
                    cityChoices.setChoices([{ value: '', label: 'ابتدا استان را انتخاب کنید...', disabled: true, selected: true }], 'value', 'label', true);
                    return;
                }

                // نمایش وضعیت لودینگ
                cityChoices.clearStore();
                cityChoices.setChoices([{ value: '', label: 'در حال دریافت شهرها...', disabled: true, selected: true }], 'value', 'label', true);

                try {
                    const response = await fetch(`/locations/provinces/${provinceId}/cities`);
                    const result = await response.json();

                    if (result.status && result.data.length > 0) {
                        // آماده‌سازی آرایه برای Choices.js
                        const formattedCities = result.data.map(city => ({
                            value: String(city.id),
                            label: city.name,
                            selected: targetCityId ? String(city.id) === String(targetCityId) : false
                        }));

                        // اضافه کردن گزینه پیش‌فرض
                        formattedCities.unshift({
                            value: '',
                            label: 'انتخاب شهر...',
                            disabled: true,
                            selected: !targetCityId
                        });

                        // جایگزین کردن شهرها در Choices
                        cityChoices.clearStore();
                        cityChoices.setChoices(formattedCities, 'value', 'label', true);
                    } else {
                        cityChoices.clearStore();
                        cityChoices.setChoices([{ value: '', label: 'شهری برای این استان ثبت نشده', disabled: true, selected: true }], 'value', 'label', true);
                    }
                } catch (error) {
                    console.error('Error fetching cities:', error);
                    cityChoices.clearStore();
                    cityChoices.setChoices([{ value: '', label: 'خطا در برقراری ارتباط با سرور', disabled: true, selected: true }], 'value', 'label', true);
                }
            }

            // ۴. رویداد تغییر استان توسط کاربر
            provinceSelectEl.addEventListener('change', function (event) {
                const provinceId = event.detail ? event.detail.value : this.value;
                // هنگام تغییر دستی توسط کاربر، شهر انتخاب‌شده قبلی پاک می‌شود
                fetchCities(provinceId, null);
            });
        });
    </script>
@endpush

