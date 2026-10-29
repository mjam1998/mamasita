@extends('admin.layout.master')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: var(--primary);">
            <i class="bi bi-plus-circle ms-1"></i> ایجاد پلن جدید
        </h5>
        <a href="{{ route('admin.plan.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت به لیست
        </a>
    </div>

    <form action="{{ route('admin.plan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            {{-- عنوان پلن --}}
            <div class="col-12 col-md-6">
                <label class="form-label">عنوان پلن <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="form-control custom-input @error('name') is-invalid @enderror" placeholder="مثلاً: اشتراک ۳ ماهه نقره‌ای">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>


            <div class="col-12 col-md-6">
                <label class="form-label"> اسلاگ (Slug)<span class="text-danger">*</span></label>
                <input type="text" name="slug" value="{{ old('slug') }}"
                       class="form-control custom-input @error('slug') is-invalid @enderror" >
                @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- نوع پلن --}}
            <div class="col-12 col-md-6">
                <label class="form-label">نوع پلن <span class="text-danger">*</span></label>
                <select name="type" id="plan-type" class="form-select custom-select @error('type') is-invalid @enderror">
                    <option value="">انتخاب کنید...</option>
                    @foreach($types as $type)
                        <option value="{{ $type->value }}" @selected(old('type') == $type->value)>
                            {{ $type->label() }}
                        </option>
                    @endforeach
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- دوره زمانی (ماه) --}}
            <div class="col-12 col-md-6" id="month-period-wrapper" style="{{ old('type') == \App\Enums\MamaFiled::MonthlySubscription->value ? '' : 'display:none;' }}">
                <label class="form-label">دوره زمانی (به ماه) <span class="text-danger">*</span></label>
                <input type="number" name="month_period" id="month_period" value="{{ old('month_period', 1) }}" min="1" max="120"
                       class="form-control custom-input @error('month_period') is-invalid @enderror">
                @error('month_period') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- قیمت اصلی (تومان) --}}
            <div class="col-12 col-md-4">
                <label class="form-label">قیمت پلن (تومان) <span class="text-danger">*</span></label>
                <input type="number" name="price" value="{{ old('price') }}" min="0" step="1000"
                       class="form-control custom-input @error('price') is-invalid @enderror">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- مبلغ تخفیف (تومان) --}}
            <div class="col-12 col-md-4">
                <label class="form-label">مبلغ تخفیف (تومان)</label>
                <input type="number" name="discount_amount" value="{{ old('discount_amount', 0) }}" min="0" step="1000"
                       class="form-control custom-input @error('discount_amount') is-invalid @enderror">
                @error('discount_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- سهم ماما (تومان) --}}
            <div class="col-12 col-md-4">
                <label class="form-label">سهم پرداختی به ماما (تومان) <span class="text-danger">*</span></label>
                <input type="number" name="mama_share" value="{{ old('mama_share') }}" min="0" step="1000"
                       class="form-control custom-input @error('mama_share') is-invalid @enderror">
                @error('mama_share') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- تصویر شاخص --}}
            <div class="col-12 col-md-4">
                <label class="form-label">تصویر پلن</label>
                <input type="file" name="image" class="form-control custom-input @error('image') is-invalid @enderror" accept="image/*">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- ویژگی Alt تصویر --}}
            <div class="col-12 col-md-4">
                <label class="form-label">Alt تصویر</label>
                <input type="text" name="image_alt" value="{{ old('image_alt') }}" class="form-control custom-input @error('image_alt') is-invalid @enderror">
                @error('image_alt') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- عنوان Title تصویر --}}
            <div class="col-12 col-md-4">
                <label class="form-label">Title تصویر</label>
                <input type="text" name="image_title" value="{{ old('image_title') }}" class="form-control custom-input @error('image_title') is-invalid @enderror">
                @error('image_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- وضعیت فعال/غیرفعال --}}
            <div class="col-12 d-flex align-items-center mt-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold me-2" for="is_active">پلن فعال باشد</label>
                </div>
            </div>

            <hr class="my-4" style="border-color: var(--border);">

            {{-- فیلدهای سئو --}}
            <div class="col-12 col-md-6">
                <label class="form-label">Meta Title (سئو)</label>
                <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="form-control custom-input @error('meta_title') is-invalid @enderror">
                @error('meta_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Meta Description (سئو)</label>
                <input type="text" name="meta_description" value="{{ old('meta_description') }}" class="form-control custom-input @error('meta_description') is-invalid @enderror">
                @error('meta_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- توضیحات پلن --}}
            <div class="col-12">
                <label class="form-label">توضیحات و امکانات پلن</label>
                @include('partial.editor',['value'=>''])
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.plan.index') }}" class="btn btn-theme-cancel">انصراف</a>
                <button type="submit" class="btn btn-theme-primary px-4">ذخیره پلن</button>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('plan-type');
            const monthWrapper = document.getElementById('month-period-wrapper');

            function toggleMonthField() {
                // اگر نوع 2 (اشتراک ماهانه) باشد فیلد را نمایش بده
                if (typeSelect.value === '2') {
                    monthWrapper.style.display = 'block';
                } else {
                    monthWrapper.style.display = 'none';
                }
            }

            typeSelect.addEventListener('change', toggleMonthField);
            toggleMonthField();
        });
    </script>
@endpush
