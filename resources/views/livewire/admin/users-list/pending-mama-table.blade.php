<div>
    {{-- بخش فیلترها --}}
    <div class="card mb-4 border-0 shadow-sm p-3">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label small text-muted">جستجو</label>
                <input type="text"
                       wire:model.defer="searchInput"
                       wire:keydown.enter="applySearch"
                       class="form-control custom-input"
                       placeholder="جستجو بر اساس نام یا شماره موبایل...">
            </div>

            {{-- فیلتر وضعیت مدارک --}}
            <div class="col-12 col-md-4">
                <label class="form-label small text-muted">وضعیت مدارک</label>
                <select wire:model.defer="documentStatusInput" class="form-select custom-select">
                    <option value="">همه وضعیت‌های مدارک</option>
                    @foreach($documentStatuses as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            {{-- دکمه‌های عملیات فیلتر --}}
            <div class="col-12 col-md-3 d-flex gap-2">
                <button wire:click="applySearch" class="btn btn-theme-primary w-100">
                    <i class="bi bi-search"></i> جستجو
                </button>
                <button wire:click="resetFilters" class="btn btn-outline-secondary" title="پاکسازی فیلترها">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- جدول --}}
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
            <tr>
                <th class="text-center">نام</th>
                <th class="text-center">موبایل</th>
                <th class="text-center">وضعیت کاربر</th>
                <th class="text-center">وضعیت مدارک</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td class="text-center fw-medium" title="{{ $user->first_name .' '. $user->last_name }}">
                        {{ \Illuminate\Support\Str::limit($user->first_name .' '. $user->last_name, 25) }}
                    </td>
                    <td class="text-center text-muted" dir="ltr">{{ $user->mobile }}</td>

                    {{-- وضعیت حساب کاربری --}}
                    <td class="text-center">
                        @if($user->is_active)
                            <span class="badge badge-status-active">فعال</span>
                        @else
                            <span class="badge badge-status-inactive">غیرفعال</span>
                        @endif
                    </td>

                    {{-- وضعیت مدارک مامایی --}}
                    <td class="text-center">
                        @php
                            $docStatus = $user->mamaInformation?->status;
                        @endphp

                        @if($docStatus)
                            <span class="badge badge-role">
                                {{ $docStatus instanceof \App\Enums\MamaInformationStatus ? $docStatus->label() : (\App\Enums\MamaInformationStatus::tryFrom($docStatus)?->label() ?? 'نامشخص') }}
                            </span>
                        @else
                            <span class="badge bg-light text-muted border">فاقد پرونده</span>
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
                                        <span>ویرایش کاربر</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.mamas.edit', ['user' => $user]) }}" class="dropdown-item d-flex align-items-center">
                                        <i class="bi bi-mortarboard ms-2 me-1 fs-6"></i>
                                        <span>بررسی مدارک</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.mamas.fields', ['user' => $user]) }}" class="dropdown-item d-flex align-items-center">
                                        <i class="bi bi-briefcase ms-2 me-1 fs-6"></i>
                                        <span>حوزه فعالیت</span>
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
