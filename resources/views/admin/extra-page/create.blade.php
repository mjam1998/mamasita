@extends('admin.layout.master')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: var(--primary);">
            <i class="bi bi-plus-circle ms-1"></i> ایجاد صفحه جدید
        </h5>
        <a href="{{ route('admin.extra-pages.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت به لیست
        </a>
    </div>

    <form action="{{ route('admin.extra-pages.store') }}" method="POST">
        @csrf
        @include('admin.extra-page._form', ['page' => null])
    </form>

@endsection
