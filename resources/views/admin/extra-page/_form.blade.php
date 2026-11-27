<div class="row g-3">

    {{-- عنوان --}}
    <div class="col-12 col-md-6">
        <label class="form-label">عنوان صفحه <span class="text-danger">*</span></label>
        <input type="text" name="title" value="{{ old('title', $page?->title) }}"
               class="form-control custom-input @error('title') is-invalid @enderror"
               placeholder="مثلاً: درباره ما">
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- اسلاگ --}}
    <div class="col-12 col-md-6">
        <label class="form-label">اسلاگ (Slug) <span class="text-danger">*</span></label>
        <input type="text" name="slug" value="{{ old('slug', $page?->slug) }}"
               class="form-control custom-input @error('slug') is-invalid @enderror"
               placeholder="about-us">
        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- وضعیت --}}
    <div class="col-12 d-flex align-items-center mt-1">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                {{ old('is_active', $page?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold me-2" for="is_active">صفحه فعال باشد</label>
        </div>
    </div>

    <hr class="my-3" style="border-color: var(--border);">

    {{-- Meta Title --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Meta Title (سئو)</label>
        <input type="text" name="meta_title" value="{{ old('meta_title', $page?->meta_title) }}"
               class="form-control custom-input @error('meta_title') is-invalid @enderror">
        @error('meta_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Meta Description --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Meta Description (سئو)</label>
        <input type="text" name="meta_description" value="{{ old('meta_description', $page?->meta_description) }}"
               class="form-control custom-input @error('meta_description') is-invalid @enderror">
        @error('meta_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- محتوا --}}
    <div class="col-12">
        <label class="form-label">محتوای صفحه</label>
        @include('partial.editor', ['value' => old('description', $page?->description ?? '')])
        @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    {{-- دکمه‌ها --}}
    <div class="col-12 d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('admin.extra-pages.index') }}" class="btn btn-theme-cancel">انصراف</a>
        <button type="submit" class="btn btn-theme-primary px-4">
            {{ $page ? 'بروزرسانی صفحه' : 'ذخیره صفحه' }}
        </button>
    </div>

</div>
