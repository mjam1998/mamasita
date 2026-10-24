@extends('front.layout.master')

@section('content')

    <!-- ===== HERO SECTION ===== -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-80">
                <!-- Text (RTL: right side) -->
                <div class="col-lg-6 hero-text order-1 order-lg-1">
                    <h1 class="hero-title">آماده‌ی یک زایمان آگاهانه باش</h1>
                    <p class="hero-sub">با همراهی ماماهای متخصص و دوره‌های آموزشی معتبر</p>
                    <p class="hero-desc">
                        در ماماسیتا می‌توانید دوره‌های آموزشی زایمان، مشاوره و ویزیت آنلاین ماماها
                        و اشتراک مامای همراه را به‌راحتی تهیه کنید.
                    </p>
                    <a href="#" class="btn btn-primary-custom mb-4">
                        شروع کنید <i class="bi bi-arrow-left"></i>
                    </a>
                </div>

                <!-- Image (RTL: left side) -->
                <div class="col-lg-6 hero-image-col order-2 order-lg-2">
                    <div class="hero-image-wrap">
                        <div class="hero-blob"></div>
                        <img
                            src="{{asset('front/assets/img/mom.png')}}"
                            alt="مادر باردار"
                            class="hero-img"
                        />
                        <div class="hero-deco hero-deco-1"><i class="bi bi-heart-fill"></i></div>
                        <div class="hero-deco hero-deco-2"><i class="bi bi-stars"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FEATURES BAR ===== -->
    <section class="features-bar">
        <div class="container">
            <div class="row g-3 justify-content-center">
                <div class="col-6 col-md-3">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                        <div>
                            <p class="feature-title">امنیت در پرداخت</p>
                            <p class="feature-sub">و اطلاعات</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-phone"></i></div>
                        <div>
                            <p class="feature-title">دسترسی آسان</p>
                            <p class="feature-sub">از هر کجا</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-person-heart"></i></div>
                        <div>
                            <p class="feature-title">مشاوره با ماماهای</p>
                            <p class="feature-sub">متخصص و باتجربه</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-headset"></i></div>
                        <div>
                            <p class="feature-title">پشتیبانی و همراهی</p>
                            <p class="feature-sub">در تمام مسیر</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== COURSES SLIDER ===== -->
    <section class="section-courses py-5">
        <div class="container">
            <div class="section-header d-flex align-items-center justify-content-between mb-4">
                <div class="">
                    <h2 class="section-title">دوره‌های آموزشی آمادگی برای زایمان</h2>
                    <p class="section-sub">فقط توسط یک ماما</p>
                </div>
                <a href="#" class="btn-see-all"><i class="bi bi-arrow-right"></i> مشاهده همه دوره‌ها</a>
            </div>

            <div class="slider-wrapper position-relative">
                <button class="slider-btn slider-btn-prev" id="coursesPrev">
                    <i class="bi bi-chevron-right"></i>
                </button>
                <button class="slider-btn slider-btn-next" id="coursesNext">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="slider-track-outer">
                    <div class="slider-track" id="coursesTrack">

                        <div class="course-card">
                            <div class="course-thumb">
                                <img src="{{asset('front/assets/img/AdobeStock_183459483-768x512.jpeg')}}" alt="دوره" />
                                <div class="play-btn"><i class="bi bi-play-fill"></i></div>
                            </div>
                            <div class="course-body">
                                <h3 class="course-name">آمادگی جسمی برای زایمان</h3>
                                <p class="course-meta"><i class="bi bi-clock"></i> ۲ ساعت</p>
                                <p class="course-price">۳۹۰,۰۰۰ تومان</p>
                                <a href="#" class="btn btn-course">خرید دوره</a>
                            </div>
                        </div>

                        <div class="course-card">
                            <div class="course-thumb">
                                <img src="{{asset('front/assets/img/images (1).jfif')}}" alt="دوره" />
                                <div class="play-btn"><i class="bi bi-play-fill"></i></div>
                            </div>
                            <div class="course-body">
                                <h3 class="course-name">تنفس و آرامسازی در زایمان</h3>
                                <p class="course-meta"><i class="bi bi-clock"></i> ۱.۵ ساعت</p>
                                <p class="course-price">۳۹۰,۰۰۰ تومان</p>
                                <a href="#" class="btn btn-course">خرید دوره</a>
                            </div>
                        </div>

                        <div class="course-card">
                            <div class="course-thumb">
                                <img src="{{asset('front/assets/img/images (2).jfif')}}" alt="دوره" />
                                <div class="play-btn"><i class="bi bi-play-fill"></i></div>
                            </div>
                            <div class="course-body">
                                <h3 class="course-name">مراحل زایمان طبیعی</h3>
                                <p class="course-meta"><i class="bi bi-clock"></i> ۲ ساعت</p>
                                <p class="course-price">۳۹۰,۰۰۰ تومان</p>
                                <a href="#" class="btn btn-course">خرید دوره</a>
                            </div>
                        </div>

                        <div class="course-card">
                            <div class="course-thumb">
                                <img src="{{asset('front/assets/img/images.jfif')}}" alt="دوره" />
                                <div class="play-btn"><i class="bi bi-play-fill"></i></div>
                            </div>
                            <div class="course-body">
                                <h3 class="course-name">مراقبت‌های بعد از زایمان</h3>
                                <p class="course-meta"><i class="bi bi-clock"></i> ۱.۵ ساعت</p>
                                <p class="course-price">۳۹۰,۰۰۰ تومان</p>
                                <a href="#" class="btn btn-course">خرید دوره</a>
                            </div>
                        </div>



                    </div><!-- /slider-track -->
                </div><!-- /slider-track-outer -->
            </div>
        </div>
    </section>

    <!-- ===== SERVICES: SUBSCRIPTION + ONLINE VISIT ===== -->
    <section class="section-services py-5">
        <div class="container">
            <div class="row g-4">

                <!-- Online visit card -->
                <div class="col-md-6">
                    <div class="service-card service-card--purple">
                        <div class="service-text">
                            <h3>مامای همراه</h3>
                            <p>معرفی ماماهای همراه زایمان و متخصص ما</p>

                            <div class="mb-3">
                                <select id="provinceSelect" class="form-select mb-2" >
                                    <option value="">انتخاب استان</option>
                                </select>

                                <select id="citySelect" class="form-select" >
                                    <option value="">انتخاب شهرستان</option>
                                </select>
                            </div>

                            <a href="#" class="btn btn-primary-custom mt-3" style="background-color: #9b5fe4;">
                                <i class="bi bi-arrow-left"></i> مشاهده ماماها
                            </a>
                        </div>
                        <div class="service-illustration">
                            <img src="{{asset('front/assets/img/banner-left.png')}}" style="max-width: 220px; max-height: 220px;">
                        </div>
                    </div>
                </div>

                <!-- Subscription card -->
                <div class="col-md-6">
                    <div class="service-card service-card--pink">
                        <div class="service-text">
                            <h3>اشتراک ماهیانه مامای همراه</h3>
                            <p>پاسخ به سوالات، پشتیبانی و همراهی همیشگی</p>
                            <ul class="service-list">
                                <li><i class="bi bi-check-circle-fill"></i> مشاوره و پاسخ به سوالات در واتساپ</li>
                                <li><i class="bi bi-check-circle-fill"></i> دسترسی به محتوای ویژه و نکات کاربردی</li>
                                <li><i class="bi bi-check-circle-fill"></i> تخفیف در خدمات دیگر</li>
                            </ul>
                            <a href="#" class="btn btn-primary-custom mt-3" >
                                <i class="bi bi-arrow-left"></i> مشاهده جزئیات
                            </a>
                        </div>
                        <div class="service-illustration">
                            <img src="{{asset('front/assets/img/banner-right.png')}}" style="max-width: 220px; max-height: 220px;">
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </section>

    <!-- ===== MIDWIVES SECTION (SLIDER) ===== -->
    <section class="section-midwives py-5 bg-light-pink">
        <div class="container">
            <div class="section-header d-flex align-items-center justify-content-between mb-4">
                <div class="">
                    <h2 class="section-title"> ویزیت آنلاین ماما</h2>
                    <p class="section-sub"> مشاوره تخصصی با ماماهای مجرب،
                        در هر زمان و مکان</p>
                </div>
                <a href="#" class="btn-see-all"><i class="bi bi-arrow-right"></i> مشاهده همه ماماها</a>
            </div>

            <div class="slider-wrapper position-relative">
                <button class="slider-btn slider-btn-prev" id="midwivesPrev">
                    <i class="bi bi-chevron-right"></i>
                </button>
                <button class="slider-btn slider-btn-next" id="midwivesNext">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="slider-track-outer">
                    <div class="slider-track" id="midwivesTrack">

                        <div class="midwife-card">
                            <img src="{{asset('front/assets/img/doctor-1.jpg')}}" alt="مریم رضایی" class="midwife-avatar" />
                            <h4 class="midwife-name">مریم رضایی</h4>
                            <p class="midwife-role">ماما و مشاور تغذیه بارداری</p>
                            <div class="midwife-rating">
                                <i class="bi bi-star-fill text-warning"></i>
                                <span>۴.۹</span>
                            </div>
                            <a href="#" class="btn btn-outline-primary-custom mt-2">مشاهده پروفایل</a>
                        </div>

                        <div class="midwife-card">
                            <img src="{{asset('front/assets/img/doctor-2.jfif')}}" alt="سارا نادری" class="midwife-avatar" />
                            <h4 class="midwife-name">سارا نادری</h4>
                            <p class="midwife-role">ماما و متخصص زایمان طبیعی</p>
                            <div class="midwife-rating">
                                <i class="bi bi-star-fill text-warning"></i>
                                <span>۴.۸</span>
                            </div>
                            <a href="#" class="btn btn-outline-primary-custom mt-2">مشاهده پروفایل</a>
                        </div>

                        <div class="midwife-card">
                            <img src="{{asset('front/assets/img/doctor-3.webp')}}" alt="الهام کاظمی" class="midwife-avatar" />
                            <h4 class="midwife-name">الهام کاظمی</h4>
                            <p class="midwife-role">ماما و مشاور شیردهی</p>
                            <div class="midwife-rating">
                                <i class="bi bi-star-fill text-warning"></i>
                                <span>۴.۷</span>
                            </div>
                            <a href="#" class="btn btn-outline-primary-custom mt-2">مشاهده پروفایل</a>
                        </div>

                        <div class="midwife-card">
                            <img src="{{asset('front/assets/img/doctor-4.jpg')}}" alt="نرگس احمدی" class="midwife-avatar" />
                            <h4 class="midwife-name">نرگس احمدی</h4>
                            <p class="midwife-role">ماما و مشاور دوران بارداری</p>
                            <div class="midwife-rating">
                                <i class="bi bi-star-fill text-warning"></i>
                                <span>۴.۷</span>
                            </div>
                            <a href="#" class="btn btn-outline-primary-custom mt-2">مشاهده پروفایل</a>
                        </div>

                    </div><!-- /slider-track -->
                </div><!-- /slider-track-outer -->
            </div>
        </div>
    </section>

    <!-- ===== BLOG SLIDER ===== -->
    <section class="section-blog py-5">
        <div class="container">
            <div class="section-header d-flex align-items-center justify-content-between mb-4">
                <div class="">
                    <h2 class="section-title">وبلاگ</h2>
                    <p class="section-sub">مطالب مفید برای یک بارداری و زایمان سالم</p>
                </div>
                <a href="#" class="btn-see-all"><i class="bi bi-arrow-right"></i> مشاهده همه مقالات</a>
            </div>

            <div class="slider-wrapper position-relative">
                <button class="slider-btn slider-btn-prev" id="blogPrev">
                    <i class="bi bi-chevron-right"></i>
                </button>
                <button class="slider-btn slider-btn-next" id="blogNext">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="slider-track-outer">
                    <div class="slider-track" id="blogTrack">

                        <div class="blog-card">
                            <div class="blog-thumb">
                                <img src="{{asset('front/assets/img/AdobeStock_183459483-768x512.jpeg')}}" alt="مقاله" />
                            </div>
                            <div class="blog-body">
                                <h3 class="blog-title">چگونه با اضطراب زایمان کنار بیاییم؟</h3>
                                <p class="blog-date"><i class="bi bi-calendar3"></i> ۲۵ شهریور ۱۴۰۳</p>
                            </div>
                        </div>

                        <div class="blog-card">
                            <div class="blog-thumb">
                                <img src="{{asset('front/assets/img/ini.jpg')}}" class="img-fluid" alt="مقاله" />
                            </div>
                            <div class="blog-body">
                                <h3 class="blog-title">نکات مهم برای مراقبت از نوزاد در هفته‌های اول</h3>
                                <p class="blog-date"><i class="bi bi-calendar3"></i> ۲۰ شهریور ۱۴۰۳</p>
                            </div>
                        </div>

                        <div class="blog-card">
                            <div class="blog-thumb">
                                <img src="{{asset('front/assets/img/food.jfif')}}" alt="مقاله" />
                            </div>
                            <div class="blog-body">
                                <h3 class="blog-title">تغذیه مناسب در دوران بارداری</h3>
                                <p class="blog-date"><i class="bi bi-calendar3"></i> ۱۵ شهریور ۱۴۰۳</p>
                            </div>
                        </div>

                        <div class="blog-card">
                            <div class="blog-thumb">
                                <img src="{{asset('front/assets/img/images.jfif')}}" alt="مقاله" />
                            </div>
                            <div class="blog-body">
                                <h3 class="blog-title">نشانه‌های شروع زایمان طبیعی</h3>
                                <p class="blog-date"><i class="bi bi-calendar3"></i> ۱۰ شهریور ۱۴۰۳</p>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
