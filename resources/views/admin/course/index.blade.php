@extends('admin.layout.master')

@section('content')



    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="section-title mb-0">
            <i class="bi bi-play-btn"> </i>لیست دوره های آموزشی
        </h3>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-theme-add">
            <i class="bi bi-plus-lg me-1"></i> افزودن
        </a>
    </div>

    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @livewire('admin.admin-course-table')



@endsection



