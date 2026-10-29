@extends('admin.layout.master')

@section('content')



    <h3 class="section-title mb-4">
        <i class="bi bi-people"> </i>لیست تمام ماما های تازه ثبت نام کرده سایت
    </h3>
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

    @livewire('admin.users-list.pending-mama-table')



@endsection



