<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ماماسیتا</title>

    <link rel="stylesheet" href="{{asset('front/assets/bootstrap/bootstrap.rtl.min.css')}}"/>

    <link rel="stylesheet" href="{{asset('front/assets/bootstrap/bootstrap-icons.css')}}"/>

    <link href="{{asset('front/assets/fonts/vazirmatn-font-face.css')}}" rel="stylesheet"/>

    <link rel="stylesheet" href="{{asset('front/assets/css/style.css')}}"/>

</head>
<body>

<!-- ===== NAVBAR – fixed-top keeps it pinned on all screen sizes during scroll ===== -->
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
            <div class="logo-icon">
                <img src="{{asset('front/assets/img/logo.png')}}" alt="ماماسیتا" title="ماماسیتا">
            </div>
            <div class="logo-text">
                <span class="logo-title">ماماسیتا</span>
                <span class="logo-sub">همراه آگاهانه در مسیر مادر شدن</span>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item"><a class="nav-link active" href="#">صفحه اصلی</a></li>
                <li class="nav-item"><a class="nav-link" href="#">دوره‌های آموزشی</a></li>
                <li class="nav-item"><a class="nav-link" href="#">ویزیت آنلاین</a></li>
                <li class="nav-item"><a class="nav-link" href="#">اشتراک مامای همراه</a></li>
                <li class="nav-item"><a class="nav-link" href="#">مامای همراه</a></li>
                <li class="nav-item"><a class="nav-link" href="#">وبلاگ</a></li>
                <li class="nav-item"><a class="nav-link" href="#">درباره ما</a></li>
            </ul>

            <div class="navbar-icons d-flex align-items-center gap-3">
                <a href="#" class="nav-icon"><i class="bi bi-search"></i></a>
                <a href="#" class="nav-icon"><i class="bi bi-person"></i></a>
                <a href="#" class="nav-icon position-relative">
                    <i class="bi bi-bag"></i>
                    <span class="cart-badge">0</span>
                </a>
            </div>
        </div>
    </div>
</nav>


@yield('content')



<!-- ===== FOOTER ===== -->
<footer class="site-footer">
    <div class="container">
        <div class="row g-5">

            <!-- Logo + desc -->
            <div class="col-12 col-md-4">
                <div class="footer-brand d-flex align-items-center gap-2 mb-3">
                    <div class="logo-icon "><img src="{{asset('front/assets/img/logo.png')}}" alt="ماماسیتا"></div>
                    <div>
                        <span class="logo-title text-white" style="color: #9b5fe4 !important;">ماماسیتا</span><br/>
                        <span class="logo-sub text-white-50" style="font-size:.75rem; color: #9b5fe4 !important;">همراه آگاهانه در مسیر مادر شدن</span>
                    </div>
                </div>
                <p class="footer-desc">
                    ماماسیتا بستری برای ارتباط مادران باردار با ماماهای متخصص است تا مسیر بارداری و زایمان را آگاهانه‌تر
                    طی کنند.
                </p>
                <div class="social-links d-flex gap-3 mt-3">
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-telegram"></i></a>
                </div>
            </div>

            <!-- Quick links -->
            <div class="col-6 col-md-2">
                <h5 class="footer-heading">دسترسی سریع</h5>
                <ul class="footer-links">
                    <li><a href="#">صفحه اصلی</a></li>
                    <li><a href="#">دوره‌های آموزشی</a></li>
                    <li><a href="#">ویزیت آنلاین</a></li>
                    <li><a href="#">اشتراک مامای همراه</a></li>
                    <li><a href="#">مامای همراه</a></li>
                </ul>
            </div>

            <!-- Useful links -->
            <div class="col-6 col-md-2">
                <h5 class="footer-heading">لینک‌های مفید</h5>
                <ul class="footer-links">
                    <li><a href="#">وبلاگ</a></li>
                    <li><a href="#">درباره ما</a></li>
                    <li><a href="#">تماس با ما</a></li>
                    <li><a href="#">سوالات متداول</a></li>
                </ul>
            </div>

            <!-- Contact / Newsletter -->
            <div class="col-12 col-md-4 mb-5">
                <h5 class="footer-heading">با ما در ارتباط باشید</h5>
                <p class="text-white-50 small mb-3" style="color: #9b5fe4 !important;">برای دریافت جدیدترین مطالب و
                    تخفیف‌ها، ایمیل خود را وارد کنید.</p>
                <div class="newsletter-form">
                    <input type="email" class="newsletter-input" placeholder="ایمیل شما"/>
                    <button class="newsletter-btn">عضویت</button>
                </div>
            </div>

        </div>

        <hr class="footer-divider"/>
        <p class="footer-copy text-center">
            © 1405-1406 تمامی حقوق مادی و معنوی این وبسایت متعلق به ماماسیتا است.
        </p>
    </div>
</footer>


<script src="{{asset('front/assets/bootstrap/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('front/assets/js/script.js')}}"></script>
@stack('scripts')

</body>
</html>

