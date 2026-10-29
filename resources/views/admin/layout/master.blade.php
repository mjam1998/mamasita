<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content=" پنل مدیریت ماماسیتا"/>
    <title>پنل مدیریت ماماسیتا</title>

    <link rel="stylesheet" href="{{asset('front/assets/bootstrap/bootstrap.rtl.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('front/assets/bootstrap/bootstrap-icons.css')}}"/>
    <link href="{{asset('front/assets/bootstrap/choices.min.css')}}" rel="stylesheet" />
    <link href="{{asset('front/assets/fonts/vazirmatn-font-face.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('admin/assets/css/persianDatepicker-default.css')}}"/>

    <link rel="stylesheet" href="{{asset('admin/assets/css/style.css')}}"/>

    @livewireStyles
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <div class=""><img src="{{asset('front/assets/img/logo.png')}}" style="width: 40px;height: 50px;"
                               alt="ماماسیتا"></div>
            <div><strong>ماماسیتا</strong><span>پنل مدیریت</span></div>
            <button
                class="icon-btn sidebar-close d-lg-none"
                id="closeSidebar"
                type="button"
                aria-label="بستن منو"
            >
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <nav class="sidebar-nav" aria-label="منوی مدیریت">
            <span class="nav-heading">منوی اصلی</span>
            <button
                class="sidebar-link "
                data-page="dashboard"
                data-title="داشبورد"
                type="button"
            >
                <i class="bi bi-grid-1x2-fill"></i><span>داشبورد</span>
            </button>
            <button class="sidebar-link"  data-page="orders" data-title="سفارش‌ها" type="button">
                <i class="bi bi-bag-heart"></i><span>سفارش‌ها</span> <span class="menu-badge">۱۲</span>
            </button>
            <button
                class="sidebar-link"
                data-page="courses"
                data-title="دوره‌های آموزشی"
                type="button"
            >
                <i class="bi  bi-play-btn"></i><span>دوره‌های آموزشی</span>
            </button>
            <a
                class="sidebar-link {{ request()->routeIs('admin.plan.*') ? 'active' : '' }}"
                data-page="courses"
                data-title=" پلن ها"
                type="button"
                href="{{route('admin.plan.index')}}"
            >
                <i class="bi bi-gem"></i><span>پلن ها </span>
            </a>
            <!-- آیتم بازشوی کاربران -->
            <div class="sidebar-dropdown">
                <button
                    class="sidebar-link w-100"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#usersSubmenu"
                    aria-expanded="false"
                    aria-controls="usersSubmenu"
                >
                    <i class="bi-person-hearts"></i>
                    <span>لیست کاربران</span>
                    <i class="bi bi-chevron-down submenu-arrow ms-auto"></i>
                </button>

                <div class="collapse sidebar-submenu" id="usersSubmenu">
                    <ul class="submenu-list">
                        <li>
                            <a href="{{route('admin.list.all')}}" class="sidebar-sublink ">
                                <span class="sublink-dot"></span>
                                <span>همه کاربران</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.list.mama')}}" class="sidebar-sublink">
                                <span class="sublink-dot"></span>
                                <span>ماماها</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.list.pending.mama')}}" class="sidebar-sublink">
                                <span class="sublink-dot"></span>
                                <span>ماماهای درانتظار بررسی</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <a
                class="sidebar-link {{ request()->routeIs('admin.pay.*') ? 'active' : '' }}"
                data-page="courses"
                data-title=" پرداخت ها"
                type="button"
                href="{{route('admin.pay.index')}}"
            >
                <i class="bi bi-cash-coin"></i><span>پرداخت‌های سایت </span>
            </a>
            <button class="sidebar-link" data-page="articles" data-title="مقالات" type="button">
                <i class="bi bi-file-earmark-text"> </i><span>مقالات</span>
            </button>
            <button class="sidebar-link" data-page="messages" data-title="پیام‌ها" type="button">
                <i class="bi bi-chat-dots"></i><span>پیام‌ ها</span><span class="menu-badge">۵</span>
            </button>
            <span class="nav-heading secondary-heading">مدیریت سیستم</span>
            <button class="sidebar-link"  data-page="reports" data-title="گزارش‌ها" type="button">
                <i class="bi bi-bar-chart-line"></i><span>گزارش‌ها</span>
            </button>
            <button class="sidebar-link" data-page="settings" data-title="تنظیمات" type="button">
                <i class="bi bi-gear"></i><span>تنظیمات</span>
            </button>
        </nav>

        <!-- <div class="sidebar-help">
          <div class="help-icon"><i class="bi bi-headset"></i></div>
          <strong>نیاز به راهنمایی دارید؟</strong>
          <span>تیم پشتیبانی کنار شماست</span>
          <button type="button">ارتباط با پشتیبانی</button>
        </div> -->
        <button class="logout-button" type="button">
            <i class="bi bi-box-arrow-right"></i>خروج از حساب
        </button>
    </aside>

    <button
        class="sidebar-backdrop d-none"
        id="sidebarBackdrop"
        type="button"
        aria-label="بستن منو"
    ></button>

    <div class="admin-main">
        <header class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button
                    class="icon-btn d-lg-none"
                    id="openSidebar"
                    type="button"
                    aria-label="باز کردن منو"
                >
                    <i class="bi bi-list"></i>
                </button>
{{--                <div class="search-box">--}}
{{--                    <i class="bi bi-search"></i>--}}
{{--                    <input--}}
{{--                        id="panelSearch"--}}
{{--                        type="search"--}}
{{--                        placeholder="جستجو در پنل..."--}}
{{--                        aria-label="جستجو"--}}
{{--                    />--}}
{{--                    <span class="search-shortcut">⌘ K</span>--}}
{{--                </div>--}}
            </div>
            <div class="topbar-actions">
                <button class="icon-btn notification-btn" type="button" aria-label="اعلان‌ها">
                    <i class="bi bi-bell"></i><span></span>
                </button>
                <div class="topbar-divider"></div>
                <button class="profile-button" type="button">
                    <span class="profile-avatar">م</span>
                    <span class="profile-copy"><strong>مریم محمدی</strong><small>مدیر کل</small></span>
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
        </header>
        <main class="content-area" >

            @yield('content')

        </main>

    </div>
</div>

<script src="{{asset('front/assets/bootstrap/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('admin/assets/js/script.js')}}"></script>
<script src="{{asset('front/assets/bootstrap/choices.min.js')}}"></script>
<script src="{{asset('front/assets/bootstrap/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('admin/assets/js/persianDatepicker.min.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#persianDate').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            observer: true,

            position: 'auto',
            toolbox: {
                calendarSwitch: { enabled: false }
            },
            altField: '#hiddenDate', // اختیاری
            navigator: {
                enabled: true
            },
            // مهم‌ترین بخش:
            initialValue: false,
            calendar: {
                persian: {
                    leapYearMode: 'astronomical'
                }
            },
            // موقعیت تقویم
            placement: 'bottom' // یا 'top' یا 'auto'
        });
    });
</script>
@livewireScripts

@stack('scripts')

</body>
</html>

