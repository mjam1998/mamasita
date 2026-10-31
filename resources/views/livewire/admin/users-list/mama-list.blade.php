<div >

    <div class="row mb-4 g-2">
        <div class="col-12 col-md-6">
            <input type="text"
                   wire:model.defer="searchInput"
                   wire:keydown.enter="applySearch"
                   class="form-control custom-input"
                   placeholder="جستجو بر اساس نام یا شماره موبایل...">
        </div>
        <div class="col-6 col-md-2">
            <button wire:click="applySearch" class="btn btn-theme-primary w-100">جستجو</button>
        </div>

    </div>

    {{-- جدول --}}
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
            <tr>
                <th class="text-center">نام</th>
                <th class="text-center">موبایل</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td class="text-center fw-medium" title="{{ $user->first_name .' '. $user->last_name }}">
                        {{ \Illuminate\Support\Str::limit($user->first_name .' '. $user->last_name, 25) }}
                    </td>
                    <td class="text-center text-muted">{{ $user->mobile }}</td>
                    <td class="text-center">
                        @if($user->is_active)
                            <span class="badge badge-status-active">فعال</span>
                        @else
                            <span class="badge badge-status-inactive">غیرفعال</span>
                        @endif
                    </td>
                    <td class="text-center align-middle">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-action dropdown-toggle"
                                    type="button"
                                    id="userActionDropdown-{{ $user->id }}"
                                    data-bs-toggle="dropdown"
                                    data-bs-popper-config='{"strategy":"fixed"}'
                                    aria-expanded="false">
                                عملیات
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu shadow-sm" aria-labelledby="userActionDropdown-{{ $user->id }}">
                                <li>
                                    <a href="{{ route('admin.users.edit', ['user' => $user]) }}" class="dropdown-item d-flex align-items-center">
                                        <i class="bi bi-pencil-square ms-2 me-1 fs-6"></i>
                                        <span>ویرایش</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.mamas.edit', ['user' => $user]) }}" class="dropdown-item d-flex align-items-center">
                                        <i class="bi bi-mortarboard ms-2 me-1 fs-6"></i>
                                        <span>مدارک</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.mamas.fields', ['user' => $user]) }}" class="dropdown-item d-flex align-items-center">
                                        <i class="bi bi-briefcase ms-2 me-1 fs-6"></i>
                                        <span>حوزه فعالیت</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.wallets.user', $user->id) }}" class="dropdown-item d-flex align-items-center">
                                        <i class="bi bi-wallet2 ms-2 me-1 fs-6"></i>
                                        <span>کیف پول</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        مامایی با این مشخصات یافت نشد.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- صفحه‌بندی --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>



