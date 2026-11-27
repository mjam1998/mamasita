@extends('admin.layout.master')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h5 class="fw-bold m-0" style="color: var(--primary);">
            <i class="bi bi-pencil ms-1"></i> ویرایش صفحه
        </h5>
        <a href="{{ route('admin.extra-pages.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت به لیست
        </a>
    </div>

    <form action="{{ route('admin.extra-pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.extra-page._form', ['page' => $page])
    </form>

@endsection
