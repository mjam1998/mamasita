<div>
    <div class="row mb-4 g-2">
        <div class="col-12 col-md-8">
            <input type="text"
                   wire:model.defer="searchInput"
                   wire:keydown.enter="applySearch"
                   class="form-control custom-input"
                   placeholder="جستجو بر اساس نام یا اسلاگ...">
        </div>

        <div class="col-6 col-md-2">
            <button wire:click="applySearch" class="btn btn-theme-primary w-100">جستجو</button>
        </div>
        <div class="col-6 col-md-2">
            <a href="{{route('admin.plan.create')}}" class="btn btn-theme-add w-100">+ افزودن</a>
        </div>
    </div>

    {{-- جدول --}}
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
            <tr>
                <th class="text-center">نام</th>
                <th class="text-center">اسلاگ</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($plans as $plan)
                <tr>
                    <td class="text-center fw-medium" title="{{ $plan->name }}">
                        {{ \Illuminate\Support\Str::limit($plan->name, 25) }}
                    </td>
                    <td class="text-center text-muted">{{ $plan->slug }}</td>


                    <td class="text-center">
                        @if($plan->is_active)
                            <span class="badge badge-status-active">فعال</span>
                        @else
                            <span class="badge badge-status-inactive">غیرفعال</span>
                        @endif
                    </td>
                    <td class="text-center align-middle">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-action dropdown-toggle"
                                    type="button"
                                    id="userActionDropdown-{{ $plan->id }}"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                عملیات
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu shadow-sm"
                                aria-labelledby="userActionDropdown-{{ $plan->id }}">
                                <li>
                                    <a href="{{ route('admin.plan.edit', ['plan' => $plan]) }}"
                                       class="dropdown-item d-flex align-items-center">
                                        <i class="bi bi-pencil-square ms-2 me-1 fs-6"></i>
                                        <span>ویرایش</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        پلنی با این مشخصات یافت نشد.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- صفحه‌بندی --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $plans->links() }}
    </div>
</div>


