@extends('admin.layout.master')

@php
    $isEdit  = isset($video);
    $title   = $isEdit ? 'ویرایش ویدیو' : 'افزودن ویدیو';
    $action  = $isEdit
        ? route('admin.videos.update', $video)
        : route('admin.videos.store');
    $method  = $isEdit ? 'PUT' : 'POST';
@endphp

@section('content')
    <div class="container-fluid px-4 py-3">

        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('admin.videos.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-right"></i>
            </a>
            <h5 class="mb-0 fw-semibold">{{ $title }}</h5>
        </div>

        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method($method)

            <div class="row g-4">

                {{-- ستون اصلی --}}
                <div class="col-12 col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">

                            {{-- عنوان --}}
                            <div class="mb-3">
                                <label class="form-label fw-medium">عنوان <span class="text-danger">*</span></label>
                                <input type="text" name="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $video->title ?? '') }}">
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- توضیحات --}}
                            <div class="mb-3">
                                <label class="form-label fw-medium">توضیحات</label>
                                <textarea name="description" rows="4"
                                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $video->description ?? '') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- آپلود ویدیو --}}
                            <div class="mb-3">
                                <label class="form-label fw-medium">
                                    فایل ویدیو
                                    @if(!$isEdit) <span class="text-danger">*</span> @endif
                                </label>
                                <input type="file" name="video_file" accept="video/mp4,video/webm,video/ogg"
                                       class="form-control @error('video_file') is-invalid @enderror">
                                @error('video_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if($isEdit && $video->file_path)
                                    <div class="mt-3">
                                        <p class="form-text text-muted mb-1">ویدیوی فعلی:</p>

                                        @if($video->type === \App\Enums\VideoType::Course)
                                            {{-- public → مستقیم قابل پخش --}}
                                            <video controls
                                                   class="w-100 rounded"
                                                   style="max-height: 320px; background:#000;"
                                                   src="{{ Storage::disk('public')->url('videos/courses/' . $video->file_path) }}">
                                                مرورگر شما از پخش ویدیو پشتیبانی نمی‌کند.
                                            </video>
                                        @else
                                            {{-- local → از طریق stream route --}}
                                            <video controls
                                                   class="w-100 rounded"
                                                   style="max-height: 320px; background:#000;"
                                                   src="{{ route('admin.videos.stream', $video) }}">
                                                مرورگر شما از پخش ویدیو پشتیبانی نمی‌کند.
                                            </video>
                                        @endif

                                        <p class="form-text text-muted mt-1">
                                            در صورت عدم آپلود فایل جدید، فایل فعلی حفظ می‌شود.
                                        </p>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ستون جانبی --}}
                <div class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-4">

                            {{-- نوع --}}
                            <div class="mb-3">
                                <label class="form-label fw-medium">نوع <span class="text-danger">*</span></label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror">
                                    <option value="">انتخاب کنید</option>
                                    @foreach($types as $tp)
                                        <option value="{{ $tp->value }}"
                                            {{ old('type', $video->type->value ?? '') == $tp->value ? 'selected' : '' }}>
                                            {{ $tp->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- دوره --}}
                            <div class="mb-3">
                                <label class="form-label fw-medium">دوره</label>

                                {{-- hidden input که با فرم POST میره --}}
                                <input type="hidden" name="course_id" id="course_id_input"
                                       value="{{ old('course_id', $video->course_id ?? '') }}">

                                {{-- select بدون name — فقط برای Choices.js --}}
                                <select id="course-select"
                                        class="form-select @error('course_id') is-invalid @enderror">
                                    <option value="">جستجوی دوره...</option>
                                    @if(isset($video) && $video->course_id && $video->course)
                                        <option value="{{ $video->course_id }}" selected>
                                            {{ $video->course->name }}
                                        </option>
                                    @endif
                                </select>

                                @error('course_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>


                            {{-- ترتیب --}}
                            <div class="mb-3">
                                <label class="form-label fw-medium">ترتیب نمایش</label>
                                <input type="number" name="sort" min="0"
                                       class="form-control @error('sort') is-invalid @enderror"
                                       value="{{ old('sort', $video->sort ?? 0) }}">
                                @error('sort')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- وضعیت --}}
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                       name="is_active" id="is_active" value="1"
                                    {{ old('is_active', $video->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">فعال</label>
                            </div>

                        </div>
                    </div>

                    <button type="submit" class="btn btn-theme-primary w-100">
                        <i class="bi bi-check2-circle me-1"></i>
                        {{ $isEdit ? 'ذخیره تغییرات' : 'ثبت ویدیو' }}
                    </button>
                </div>

            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const courseEl = document.getElementById('course-select');
                const courseInput = document.getElementById('course_id_input');

                const courseChoices = new Choices(courseEl, {
                    searchEnabled: true,
                    searchResultLimit: 10,
                    placeholder: true,
                    placeholderValue: 'جستجوی دوره...',
                    noResultsText: 'نتیجه‌ای یافت نشد',
                    noChoicesText: 'برای جستجو تایپ کنید',
                    loadingText: 'در حال بارگذاری...',
                    shouldSort: false,
                });

                // AJAX search
                courseEl.addEventListener('search', function (event) {
                    const query = event.detail.value.trim();
                    if (query.length < 2) return;

                    fetch("{{ route('admin.courses.search') }}?q=" + encodeURIComponent(query), {
                        method: 'POST',
                        headers: { 'Accept': 'application/json' },
                        cache: 'no-store',
                    })
                        .then(res => res.json())
                        .then(data => courseChoices.setChoices(data, 'value', 'label', true))
                        .catch(() => {});
                });

                // sync انتخاب با hidden input
                courseEl.addEventListener('change', function () {
                    courseInput.value = courseEl.value ?? '';
                });

                // اگر مقدار اولیه (ویرایش) وجود داشت، hidden input رو هم set کن
                if (courseInput.value) {
                    // option از قبل در select هست (در Blade رندر شده)
                    // فقط مطمئن می‌شیم Choices.js اون رو selected می‌بینه
                    courseChoices.setChoiceByValue(courseInput.value);
                }

            });
        </script>
    @endpush

@endsection
