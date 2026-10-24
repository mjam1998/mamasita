@extends('admin.layout.master')

@section('content')

    <div id="dashboardPage" >
        <section class="welcome-row">
            <div>
                <p class="eyebrow">سه‌شنبه، ۲۶ فروردین ۱۴۰۴</p>
                <h1>  سلام مریم، روزت بخیر! </h1>
                <p>اینجا می‌تونی خلاصه‌ای از وضعیت امروز ماماسیتا رو ببینی.</p>
            </div>
            <button class="primary-button" type="button">
                <i class="bi bi-plus-lg"></i>افزودن دوره جدید
            </button>
        </section>

        <section class="row g-3 g-xl-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="stat-card">
                    <div class="stat-icon pink"><i class="bi bi-wallet2"></i></div>
                    <div class="stat-copy">
                        <span>فروش کل </span><strong>۴۸,۶۲۰,۰۰۰</strong><small>تومان</small>
                    </div>
                    <div class="stat-change"> <i class="bi bi-arrow-up-left"></i>۱۲.۵٪</div>
                </article>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="stat-card">
                    <div class="stat-icon purple"><i class="bi bi-bag-check"></i></div>
                    <div class="stat-copy">
                        <span> سفارش‌های جدید</span> <strong>۱۲۸</strong><small>سفارش</small>
                    </div>
                    <div class="stat-change"> <i class="bi bi-arrow-up-left"></i>۸.۲٪</div>
                </article>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="stat-card">
                    <div class="stat-icon blue"><i class="bi bi-person-check"></i></div>
                    <div class="stat-copy">
                        <span>کاربران فعال</span> <strong>۱,۸۴۲</strong><small>نفر</small>
                    </div>
                    <div class="stat-change"> <i class="bi bi-arrow-up-left"></i>۵.۴٪</div>
                </article>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="stat-card">
                    <div class="stat-icon peach"><i class="bi bi-calendar2-heart"></i></div>
                    <div class="stat-copy">
                        <span>نوبت‌های امروز</span><strong>۲۴</strong><small>نوبت</small>
                    </div>
                    <div class="stat-change"><i class="bi bi-arrow-up-left"></i>۳ نوبت</div>
                </article>
            </div>
        </section>

        <section class="row g-3 g-xl-4 mb-4">
            <div class="col-12 col-xl-8">
                <article class="panel chart-panel">
                    <div class="panel-header">
                        <div><h2>گزارش فروش</h2>
                            <p>نمایش درآمد ۷ ماه اخیر</p></div>
                        <button class="filter-button" type="button">
                            ۷ ماه اخیر<i class="bi bi-chevron-down"></i>
                        </button>
                    </div>
                    <div class="chart-summary">
                        <strong>۲۸۴,۵۰۰,۰۰۰</strong><span>تومان درآمد کل</span>
                        <em><i class="bi bi-graph-up-arrow"></i>۱۸.۲٪ رشد</em>
                    </div>
                    <div class="sales-chart" aria-label="نمودار فروش ماهانه">
                        <div class="chart-y-axis">
                            <span>۸۰م</span><span>۶۰م</span><span>۴۰م</span><span>۲۰م</span><span>۰</span>
                        </div>
                        <div class="chart-stage">
                            <div class="chart-grid"><i></i><i></i><i></i><i></i><i></i></div>
                            <svg viewBox="0 0 700 210" preserveAspectRatio="none" role="img">
                                <defs>
                                    <linearGradient id="chartFill" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#c0396e" stop-opacity=".22"></stop>
                                        <stop offset="100%" stop-color="#c0396e" stop-opacity="0"></stop>
                                    </linearGradient>
                                </defs>
                                <path
                                    class="chart-area"
                                    d="M0,175 C50,160 65,135 116,143 C165,151 190,115 233,120 C285,126 310,69 350,84 C402,103 415,55 466,61 C520,68 540,31 583,44 C635,58 653,18 700,22 L700,210 L0,210 Z"
                                ></path>
                                <path
                                    class="chart-line"
                                    d="M0,175 C50,160 65,135 116,143 C165,151 190,115 233,120 C285,126 310,69 350,84 C402,103 415,55 466,61 C520,68 540,31 583,44 C635,58 653,18 700,22"
                                ></path>
                                <g class="chart-points">
                                    <circle cx="0" cy="175" r="5"></circle>
                                    <circle cx="116" cy="143" r="5"></circle>
                                    <circle cx="233" cy="120" r="5"></circle>
                                    <circle cx="350" cy="84" r="5"></circle>
                                    <circle cx="466" cy="61" r="5"></circle>
                                    <circle cx="583" cy="44" r="5"></circle>
                                    <circle cx="700" cy="22" r="5"></circle>
                                </g>
                            </svg>
                            <div class="chart-x-axis">
                                <span>مهر</span><span>آبان</span><span>آذر</span><span>دی</span>
                                <span>بهمن</span><span>اسفند</span><span>فروردین</span>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xl-4">
                <article class="panel category-panel">
                    <div class="panel-header">
                        <div><h2>دسته‌بندی فروش</h2>
                            <p>تفکیک درآمد این ماه</p></div>
                        <button class="more-button" type="button" aria-label="گزینه‌های بیشتر">
                            <i class="bi bi-three-dots"></i>
                        </button>
                    </div>
                    <div class="donut-wrap">
                        <div class="donut-chart">
                            <div><strong>۱۰۰٪</strong><span>کل فروش</span></div>
                        </div>
                    </div>
                    <div class="category-list">
                        <div><span><i class="dot pink"></i>دوره‌های آموزشی</span><strong>۴۸٪</strong></div>
                        <div><span><i class="dot purple"></i>مشاوره آنلاین</span><strong>۳۲٪</strong></div>
                        <div><span><i class="dot peach"></i>محصولات فروشگاه</span><strong>۲۰٪</strong></div>
                    </div>
                </article>
            </div>
        </section>

        <section class="panel orders-panel">
            <div class="panel-header">
                <div><h2>سفارش‌های اخیر</h2>
                    <p>آخرین تراکنش‌های ثبت شده در سایت</p></div>
                <button class="text-button" type="button">
                    مشاهده همه<i class="bi bi-arrow-left"></i>
                </button>
            </div>
            <div class="table-responsive">
                <table class="table align-middle admin-table">
                    <thead>
                    <tr>
                        <th>شماره سفارش</th>
                        <th>مشتری</th>
                        <th>محصول / خدمت</th>
                        <th>مبلغ</th>
                        <th>وضعیت</th>
                        <th aria-label="عملیات"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="order-id">#۳۴۸۹</td>
                        <td>
                            <div class="customer-cell"><span>نا</span><strong>نگار احمدی</strong></div>
                        </td>
                        <td>دوره آمادگی برای زایمان</td>
                        <td class="amount-cell">۱,۲۹۰,۰۰۰ تومان</td>
                        <td><span class="status-pill success"><i></i>تکمیل شده</span></td>
                        <td>
                            <button class="more-button" type="button"><i class="bi bi-three-dots-vertical"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="order-id">#۳۴۸۸</td>
                        <td>
                            <div class="customer-cell"><span>سم</span><strong>سارا محمدی</strong></div>
                        </td>
                        <td>پکیج مراقبت بارداری</td>
                        <td class="amount-cell">۸۹۰,۰۰۰ تومان</td>
                        <td><span class="status-pill pending"><i></i>در حال بررسی</span></td>
                        <td>
                            <button class="more-button" type="button"><i class="bi bi-three-dots-vertical"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="order-id">#۳۴۸۷</td>
                        <td>
                            <div class="customer-cell"><span>مر</span><strong>مریم رضایی</strong></div>
                        </td>
                        <td>مشاوره آنلاین با ماما</td>
                        <td class="amount-cell">۳۵۰,۰۰۰ تومان</td>
                        <td><span class="status-pill success"><i></i>تکمیل شده</span></td>
                        <td>
                            <button class="more-button" type="button"><i class="bi bi-three-dots-vertical"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="order-id">#۳۴۸۶</td>
                        <td>
                            <div class="customer-cell"><span>اک</span><strong>الهام کریمی</strong></div>
                        </td>
                        <td>دوره مراقبت از نوزاد</td>
                        <td class="amount-cell">۷۵۰,۰۰۰ تومان</td>
                        <td><span class="status-pill cancelled"><i></i>لغو شده</span></td>
                        <td>
                            <button class="more-button" type="button"><i class="bi bi-three-dots-vertical"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

@endsection
