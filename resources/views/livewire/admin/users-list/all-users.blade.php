<div >
    {{-- جستجو و فیلتر --}}
    @php
        $roleTranslations = [
            'super-admin' => 'سوپر ادمین',
            'user' => 'کاربر ',
            'mama' => 'ماما',
        ];
    @endphp

    <div class="row mb-4 g-2">
        <div class="col-12 col-md-6">
            <input type="text"
                   wire:model.defer="searchInput"
                   wire:keydown.enter="applySearch"
                   class="form-control custom-input"
                   placeholder="جستجو بر اساس نام یا شماره موبایل...">
        </div>
        <div class="col-12 col-md-2">
            <select wire:model.live="selectedRole" class="form-select custom-select" id="role_id">
                <option value="">همه نقش‌ها</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}"> {{ $roleTranslations[$role->name] ?? $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <button wire:click="applySearch" class="btn btn-theme-primary w-100">جستجو</button>
        </div>
        <div class="col-6 col-md-2">
            <a href="{{route('admin.users.create')}}" class="btn btn-theme-add w-100">+ افزودن</a>
        </div>
    </div>

    {{-- جدول --}}
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
            <tr>
                <th class="text-center">نام</th>
                <th class="text-center">موبایل</th>
                <th class="text-center">نقش</th>
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
                        @foreach($user->roles as $role)
                            <span class="badge badge-role">{{ $roleTranslations[$role->name] ?? $role->name }}</span>
                        @endforeach
                    </td>
                    <td class="text-center">
                        @if($user->is_active)
                            <span class="badge badge-status-active">فعال</span>
                        @else
                            <span class="badge badge-status-inactive">غیرفعال</span>
                        @endif
                    </td>
                    <td class="text-center">
{{--                        <div class="dropdown">--}}
{{--                            <button class="btn btn-sm btn-action dropdown-toggle" type="button" data-bs-toggle="dropdown">--}}
{{--                                عملیات--}}
{{--                            </button>--}}
{{--                            <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu">--}}
{{--                                <li>--}}
{{--                                    <a href="{{ route('admin.edit', ['user' => $user]) }}" class="dropdown-item">--}}
{{--                                        <i class="bi bi-pencil ms-2"></i> ویرایش--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a href="{{ route('admin.order.user.list', ['user' => $user]) }}" class="dropdown-item">--}}
{{--                                        <i class="bi bi-cart ms-2"></i> سفارشات--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li><hr class="dropdown-divider" style="border-color: var(--border);"></li>--}}
{{--                                <li>--}}
{{--                                    <a href="#"--}}
{{--                                       class="dropdown-item {{ $user->is_active ? 'text-danger' : 'text-success' }}"--}}
{{--                                       onclick="toggleStatus('{{ route('admin.change.status', ['user' => $user]) }}', 'status-form-{{ $user->id }}'); return false;">--}}
{{--                                        <i class="bi {{ $user->is_active ? 'bi-toggle-off' : 'bi-toggle-on' }} ms-2"></i>--}}
{{--                                        {{ $user->is_active ? 'غیرفعال‌سازی' : 'فعال‌سازی' }}--}}
{{--                                    </a>--}}
{{--                                    <form id="status-form-{{ $user->id }}"--}}
{{--                                          action="{{ route('admin.change.status', ['user' => $user]) }}"--}}
{{--                                          method="POST"--}}
{{--                                          style="display:none;">--}}
{{--                                        @csrf--}}
{{--                                        @method('PATCH')--}}
{{--                                    </form>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        کاربری با این مشخصات یافت نشد.
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


