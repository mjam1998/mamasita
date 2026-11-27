<div class="row g-3">

    {{-- عنوان --}}
    <div class="col-12 col-md-6">
        <label class="form-label">عنوان مقاله <span class="text-danger">*</span></label>
        <input type="text" name="title" value="{{ old('title', $blog?->title) }}"
               class="form-control custom-input @error('title') is-invalid @enderror"
               placeholder="مثلاً: راهنمای جامع تغذیه در بارداری">
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- اسلاگ --}}
    <div class="col-12 col-md-6">
        <label class="form-label">اسلاگ (Slug) <span class="text-danger">*</span></label>
        <input type="text" name="slug" value="{{ old('slug', $blog?->slug) }}"
               class="form-control custom-input @error('slug') is-invalid @enderror"
               placeholder="rahnama-taghzieh-bardari">
        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- تصویر --}}
    <div class="col-12 col-md-4">
        <label class="form-label">تصویر شاخص</label>
        <input type="file" name="image" accept="image/*"
               class="form-control custom-input @error('image') is-invalid @enderror">
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if($blog?->image)
            <div class="mt-2">
                <img src="{{ Storage::url('blogs/' . $blog->image) }}"
                     alt="{{ $blog->image_alt }}"
                     style="height:80px; border-radius:10px; object-fit:cover;">
                <div class="form-text">تصویر فعلی — آپلود جدید جایگزین می‌شود</div>
            </div>
        @endif
    </div>

    {{-- Alt تصویر --}}
    <div class="col-12 col-md-4">
        <label class="form-label">Alt تصویر</label>
        <input type="text" name="image_alt" value="{{ old('image_alt', $blog?->image_alt) }}"
               class="form-control custom-input @error('image_alt') is-invalid @enderror">
        @error('image_alt') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Title تصویر --}}
    <div class="col-12 col-md-4">
        <label class="form-label">Title تصویر</label>
        <input type="text" name="image_title" value="{{ old('image_title', $blog?->image_title) }}"
               class="form-control custom-input @error('image_title') is-invalid @enderror">
        @error('image_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- وضعیت --}}
    <div class="col-12 d-flex align-items-center mt-1">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                {{ old('is_active', $blog?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold me-2" for="is_active">مقاله فعال باشد</label>
        </div>
    </div>

    <hr class="my-3" style="border-color: var(--border);">

    {{-- Meta Title --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Meta Title (سئو)</label>
        <input type="text" name="meta_title" value="{{ old('meta_title', $blog?->meta_title) }}"
               class="form-control custom-input @error('meta_title') is-invalid @enderror">
        @error('meta_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Meta Description --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Meta Description (سئو)</label>
        <input type="text" name="meta_description" value="{{ old('meta_description', $blog?->meta_description) }}"
               class="form-control custom-input @error('meta_description') is-invalid @enderror">
        @error('meta_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- کلمات کلیدی (Tagify) --}}
    <div class="col-12">
        <label class="form-label">کلمات کلیدی</label>
        <input id="keywords-input" name="keywords"
               value="{{ old('keywords', $blog ? implode(',', $blog->keywords_array) : '') }}"
               class="form-control custom-input @error('keywords') is-invalid @enderror"
               placeholder="کلمه را تایپ کنید و Enter بزنید">
        @error('keywords') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- محتوا --}}
    <div class="col-12">
        <label class="form-label">محتوای مقاله</label>
        @include('partial.editor', ['value' => old('description', $blog?->description ?? '')])
        @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    {{-- دکمه‌ها --}}
    <div class="col-12 d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-theme-cancel">انصراف</a>
        <button type="submit" class="btn btn-theme-primary px-4">
            {{ $blog ? 'بروزرسانی مقاله' : 'ذخیره مقاله' }}
        </button>
    </div>

</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.querySelector('#keywords-input');
            var tagify = new Tagify(input, {
                delimiters: ",",
                maxTags: 50,
                placeholder: "کلمه را تایپ کنید و Enter بزنید",
                dropdown: { enabled: 0 }
            });

            document.querySelector('form').addEventListener('submit', function () {
                var tags = tagify.value.map(tag => tag.value).join(',');
                input.value = tags;
            });
        });
    </script>
@endpush
