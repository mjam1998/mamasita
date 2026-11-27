@extends('admin.layout.master')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: var(--primary);">
            <i class="bi bi-pencil-square ms-1"></i> ویرایش دوره: {{ $course->name }}
        </h5>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت به لیست
        </a>
    </div>

    <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-3">

            {{-- عنوان دوره --}}
            <div class="col-12 col-md-6">
                <label class="form-label">عنوان دوره <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $course->name) }}"
                       class="form-control custom-input @error('name') is-invalid @enderror">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- اسلاگ --}}
            <div class="col-12 col-md-6">
                <label class="form-label">اسلاگ (Slug) <span class="text-danger">*</span></label>
                <input type="text" name="slug" value="{{ old('slug', $course->slug) }}"
                       class="form-control custom-input @error('slug') is-invalid @enderror">
                @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- کد اسپات پلیر --}}
            <div class="col-12 col-md-6">
                <label class="form-label">کد SpotPlayer</label>
                <input type="text" name="spot_player_code" value="{{ old('spot_player_code', $course->spot_player_code) }}"
                       class="form-control custom-input @error('spot_player_code') is-invalid @enderror">
                @error('spot_player_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- قیمت --}}
            <div class="col-12 col-md-4">
                <label class="form-label">قیمت دوره (تومان) <span class="text-danger">*</span></label>
                <input type="number" name="price" value="{{ old('price', $course->price) }}" min="0" step="1000"
                       class="form-control custom-input @error('price') is-invalid @enderror">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- تخفیف --}}
            <div class="col-12 col-md-4">
                <label class="form-label">مبلغ تخفیف (تومان)</label>
                <input type="number" name="discount_amount" value="{{ old('discount_amount', $course->discount_amount) }}" min="0" step="1000"
                       class="form-control custom-input @error('discount_amount') is-invalid @enderror">
                @error('discount_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- تصویر فعلی + آپلود جدید --}}
            <div class="col-12 col-md-4">
                <label class="form-label">تصویر دوره</label>
                @if($course->image)
                    <div class="mb-2">
                        <img src="{{ asset('courses/' . $course->image) }}"
                             alt="{{ $course->image_alt }}" class="img-thumbnail" style="max-height:80px;">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*"
                       class="form-control custom-input @error('image') is-invalid @enderror">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Alt تصویر --}}
            <div class="col-12 col-md-6">
                <label class="form-label">Alt تصویر</label>
                <input type="text" name="image_alt" value="{{ old('image_alt', $course->image_alt) }}"
                       class="form-control custom-input @error('image_alt') is-invalid @enderror">
                @error('image_alt') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Title تصویر --}}
            <div class="col-12 col-md-6">
                <label class="form-label">Title تصویر</label>
                <input type="text" name="image_title" value="{{ old('image_title', $course->image_title) }}"
                       class="form-control custom-input @error('image_title') is-invalid @enderror">
                @error('image_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- وضعیت --}}
            <div class="col-12 d-flex align-items-center mt-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                           id="is_active" {{ old('is_active', $course->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold me-2" for="is_active">دوره فعال باشد</label>
                </div>
            </div>

            <hr class="my-4" style="border-color: var(--border);">

            {{-- محدودیت دستگاه‌ها --}}
            <div class="col-12">
                <h6 class="fw-bold mb-3" style="color: var(--primary);">
                    <i class="bi bi-display ms-1"></i> محدودیت دستگاه‌ها
                </h6>
            </div>

            @php
                $limitFields = [
                    'p0_all_device' => 'همه دستگاه‌ها',
                    'p1_windows'    => 'ویندوز',
                    'p2_macos'      => 'macOS',
                    'p3_linux'      => 'لینوکس',
                    'p4_android'    => 'اندروید',
                    'p5_ios'        => 'iOS',
                    'p6_webapp'     => 'وب‌اپ',
                ];
                $limit = $course->courseLimit;
            @endphp

            @foreach($limitFields as $field => $label)
                <div class="col-6 col-md-3">
                    <label class="form-label">{{ $label }}</label>
                    <input type="number" name="{{ $field }}"
                           value="{{ old($field, $limit?->$field ?? 0) }}" min="0"
                           class="form-control custom-input @error($field) is-invalid @enderror">
                    @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            @endforeach

            <hr class="my-4" style="border-color: var(--border);">

            {{-- توضیحات --}}
            <div class="col-12">
                <label class="form-label">توضیحات دوره</label>
                @include('partial.editor', ['value' => old('description', $course->description)])
                @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.courses.index') }}" class="btn btn-theme-cancel">انصراف</a>
                <button type="submit" class="btn btn-theme-primary px-4">به‌روزرسانی دوره</button>
            </div>

        </div>
    </form>

@endsection
