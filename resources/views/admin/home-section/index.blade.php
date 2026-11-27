@extends('admin.layout.master')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: var(--primary);">
            <i class="bi bi-house ms-1"></i> تنظیمات صفحه اصلی
        </h5>
    </div>

    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.home-section.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">

            {{-- H1 --}}
            <div class="col-12 col-md-4">
                <label class="form-label">متن H1</label>
                <input type="text" name="h1_text" value="{{ old('h1_text', $section->h1_text) }}"
                       class="form-control custom-input @error('h1_text') is-invalid @enderror">
                @error('h1_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- H2 --}}
            <div class="col-12 col-md-4">
                <label class="form-label">متن H2</label>
                <input type="text" name="h2_text" value="{{ old('h2_text', $section->h2_text) }}"
                       class="form-control custom-input @error('h2_text') is-invalid @enderror">
                @error('h2_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- H3 --}}
            <div class="col-12 col-md-4">
                <label class="form-label">متن H3</label>
                <input type="text" name="h3_text" value="{{ old('h3_text', $section->h3_text) }}"
                       class="form-control custom-input @error('h3_text') is-invalid @enderror">
                @error('h3_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- دکمه --}}
            <div class="col-12 col-md-6">
                <label class="form-label">متن دکمه</label>
                <input type="text" name="btn_text" value="{{ old('btn_text', $section->btn_text) }}"
                       class="form-control custom-input @error('btn_text') is-invalid @enderror">
                @error('btn_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">لینک دکمه</label>
                <input type="text" name="btn_link" value="{{ old('btn_link', $section->btn_link) }}"
                       class="form-control custom-input @error('btn_link') is-invalid @enderror"
                       placeholder="https://...">
                @error('btn_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <hr class="my-2" style="border-color: var(--border);">

            {{-- تصویر --}}
            <div class="col-12 col-md-4">
                <label class="form-label">تصویر هیرو</label>
                <input type="file" name="image" accept="image/*"
                       class="form-control custom-input @error('image') is-invalid @enderror">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @if($section->image)
                    <div class="mt-2 d-flex align-items-center gap-2">
                        <img src="{{ asset('home-section/' . $section->image) }}"
                             alt="{{ $section->image_alt }}"
                             style="height:70px; border-radius:8px; object-fit:cover;">
                        <small class="text-muted">تصویر فعلی</small>
                    </div>
                @endif
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label">Alt تصویر</label>
                <input type="text" name="image_alt" value="{{ old('image_alt', $section->image_alt) }}"
                       class="form-control custom-input @error('image_alt') is-invalid @enderror">
                @error('image_alt') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label">Title تصویر</label>
                <input type="text" name="image_title" value="{{ old('image_title', $section->image_title) }}"
                       class="form-control custom-input @error('image_title') is-invalid @enderror">
                @error('image_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <hr class="my-2" style="border-color: var(--border);">

            {{-- سئو --}}
            <div class="col-12 col-md-6">
                <label class="form-label">Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $section->meta_title) }}"
                       class="form-control custom-input @error('meta_title') is-invalid @enderror">
                @error('meta_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Meta Description</label>
                <input type="text" name="meta_description" value="{{ old('meta_description', $section->meta_description) }}"
                       class="form-control custom-input @error('meta_description') is-invalid @enderror">
                @error('meta_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- دکمه ذخیره --}}
            <div class="col-12 d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-theme-primary px-5">
                    <i class="bi bi-floppy ms-1"></i> ذخیره تغییرات
                </button>
            </div>

        </div>
    </form>

@endsection
