<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $centre->centre_name }} | Authorized Study Centre ({{ $centre->centre_code }})</title>
    <meta name="description" content="{{ $landingPage->tagline ?? ($centre->centre_name . ' - Authorized Tech Era Computer Education Centre offering certified diploma and vocational courses.') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Dynamic Theme Styling -->
    <style>
        :root {
            --centre-primary: {{ $landingPage->theme_color ?? '#0d2238' }};
            --centre-accent: {{ $landingPage->accent_color ?? '#8f9f1b' }};
            --centre-dark: #071524;
        }

        html {
            scroll-behavior: smooth;
        }

        section {
            scroll-margin-top: 70px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        .bg-centre-primary {
            background-color: var(--centre-primary) !important;
        }

        .bg-centre-accent {
            background-color: var(--centre-accent) !important;
        }

        .text-centre-primary {
            color: var(--centre-primary) !important;
        }

        .text-centre-accent {
            color: var(--centre-accent) !important;
        }

        .btn-centre-primary {
            background-color: var(--centre-primary);
            color: #ffffff;
            border-color: var(--centre-primary);
        }
        .btn-centre-primary:hover {
            background-color: var(--centre-dark);
            color: #ffffff;
        }

        .btn-centre-accent {
            background-color: var(--centre-accent);
            color: #ffffff;
            border-color: var(--centre-accent);
        }
        .btn-centre-accent:hover {
            background-color: #7a8915;
            color: #ffffff;
        }

        .centre-nav {
            background: #ffffff;
            border-bottom: 3px solid var(--centre-accent);
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }

        .centre-hero-carousel .carousel-item {
            height: 400px;
            background-color: var(--centre-primary);
            position: relative;
        }
        .centre-hero-carousel .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.75);
        }
        .centre-slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(7, 21, 36, 0.88) 0%, rgba(7, 21, 36, 0.55) 55%, transparent 100%);
            display: flex;
            align-items: center;
            padding: 28px 45px;
        }
        .centre-hero-carousel .carousel-control-prev,
        .centre-hero-carousel .carousel-control-next {
            width: 48px;
            height: 48px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(13, 34, 56, 0.75);
            border-radius: 50%;
            margin: 0 18px;
            backdrop-filter: blur(4px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.2s ease;
            opacity: 0.85;
        }
        .centre-hero-carousel .carousel-control-prev:hover,
        .centre-hero-carousel .carousel-control-next:hover {
            background: var(--centre-accent);
            border-color: #ffffff;
            opacity: 1;
            transform: translateY(-50%) scale(1.1);
        }
        .centre-hero-carousel .carousel-indicators [data-bs-target] {
            width: 32px;
            height: 6px;
            border-radius: 4px;
            background-color: #ffffff;
            opacity: 0.5;
            transition: all 0.3s ease;
        }
        .centre-hero-carousel .carousel-indicators .active {
            width: 48px;
            background-color: var(--centre-accent);
            opacity: 1;
        }

        @media (max-width: 767.98px) {
            .centre-hero-carousel .carousel-item {
                height: 270px;
            }
            .centre-slide-overlay {
                padding: 16px 20px;
                background: linear-gradient(180deg, rgba(7, 21, 36, 0.4) 0%, rgba(7, 21, 36, 0.85) 100%);
                align-items: flex-end;
                padding-bottom: 25px;
            }
            .centre-hero-carousel .carousel-control-prev,
            .centre-hero-carousel .carousel-control-next {
                display: none;
            }
        }

        .centre-facility-pill {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid var(--centre-accent);
            border-radius: 10px;
            padding: 10px 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .centre-facility-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        }

        .centre-gallery-img {
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s;
        }
        .centre-gallery-img:hover {
            transform: scale(1.03);
        }

        .floating-whatsapp {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 1090;
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background-color: #25d366;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 0 10px 25px rgba(37, 211, 102, 0.4);
            transition: transform 0.2s;
            text-decoration: none;
        }
        .floating-whatsapp:hover {
            transform: scale(1.1);
            color: #ffffff;
        }

        @media (max-width: 991.98px) {
            body {
                padding-bottom: 74px; /* Ensure mobile footer menu doesn't block content */
            }
            .floating-whatsapp {
                bottom: 84px !important; /* Move upward above mobile footer menu */
                right: 18px !important;
                width: 52px;
                height: 52px;
                font-size: 26px;
            }
        }

        /* Mobile Sticky Bottom Menu */
        .centre-mobile-bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1080;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            box-shadow: 0 -4px 16px rgba(0,0,0,0.08);
            padding: 6px 12px 8px;
        }
        .centre-mob-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #64748b;
            font-size: 0.68rem;
            font-weight: 700;
            line-height: 1.2;
            padding: 2px 4px;
        }
        .centre-mob-btn i {
            font-size: 1.15rem;
            margin-bottom: 2px;
        }
        .centre-mob-btn.active, .centre-mob-btn:hover {
            color: var(--centre-primary);
        }

        /* Public-Aligned Academic Course Card Styling */
        .dce-course-grid-card {
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
            position: relative;
        }
        .dce-course-grid-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.1);
            border-color: var(--centre-accent, #0284c7);
        }
        .dce-course-img-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            background: #0b1329;
            border-radius: 7px 7px 0 0;
        }
        .dce-course-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.35s ease;
        }
        .dce-course-grid-card:hover .dce-course-img {
            transform: scale(1.05);
        }
        .dce-course-code-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.35);
            font-size: 0.68rem;
            font-weight: 800;
            padding: 2.5px 7px;
            border-radius: 4px;
            letter-spacing: 0.4px;
            z-index: 2;
        }
        .dce-course-cat-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: var(--centre-accent, #0284c7);
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2.5px 7px;
            border-radius: 4px;
            z-index: 2;
        }
        .dce-course-body {
            padding: 0.9rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .dce-course-title-clamp {
            font-size: 0.92rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.35;
            margin-bottom: 0.45rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.65em;
            text-decoration: none;
            transition: color 0.2s ease;
            text-transform: capitalize;
        }
        .dce-course-title-clamp:hover {
            color: var(--centre-primary, #0284c7);
        }
        .dce-course-meta-row {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
        }
        .dce-course-pill {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .dce-course-footer {
            margin-top: auto;
            padding-top: 0.65rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .dce-course-pricing {
            display: flex;
            flex-direction: column;
        }
        .dce-course-price {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }
        .dce-course-price-lbl {
            font-size: 0.64rem;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: 700;
            display: block;
            margin-bottom: 2px;
        }
        .dce-course-btn {
            font-size: 0.78rem;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 6px !important;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            background: var(--centre-primary, #0284c7);
            border: 1px solid var(--centre-primary, #0284c7);
            color: #ffffff;
            transition: all 0.2s ease;
        }
        .dce-course-btn:hover {
            background: var(--centre-dark, #0369a1);
            color: #ffffff;
        }
        @media (max-width: 575.98px) {
            .centre-nav .navbar-brand {
                max-width: calc(100% - 65px);
            }
            .centre-nav .navbar-brand img {
                height: 38px;
            }
            .centre-nav .navbar-brand h5 {
                font-size: 0.86rem !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 200px;
            }
            .centre-nav .navbar-brand .text-centre-accent {
                font-size: 0.60rem !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 220px;
            }
            .centre-nav .navbar-brand span {
                font-size: 0.66rem !important;
            }
            .centre-nav .navbar-toggler {
                padding: 4px 8px;
                font-size: 0.85rem;
            }
            .dce-course-body {
                padding: 0.65rem 0.55rem 0.55rem;
            }
            .dce-course-title-clamp {
                font-size: 0.78rem;
                line-height: 1.25;
                min-height: 2.5em;
                margin-bottom: 0.35rem;
            }
            .dce-course-meta-row {
                gap: 3px;
                margin-bottom: 0.45rem;
            }
            .dce-course-pill {
                font-size: 0.62rem;
                padding: 1.5px 5px;
            }
            .dce-course-footer {
                padding-top: 0.45rem;
            }
            .dce-course-price {
                font-size: 0.88rem;
            }
            .dce-course-btn {
                font-size: 0.72rem;
                padding: 4px 9px;
            }
            .dce-store-thumb {
                height: 125px !important;
            }
            .dce-store-desc {
                display: none !important;
            }
            .dce-store-card .card-body {
                padding: 0.65rem 0.55rem 0.55rem !important;
            }
            .dce-store-card h6 {
                font-size: 0.80rem !important;
                margin-bottom: 0.25rem !important;
            }
        }
    </style>
</head>
<body>

    <!-- Top Info Bar -->
    <div class="bg-centre-primary text-white py-2 small">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <span class="badge bg-centre-accent text-white me-2 font-monospace">{{ $centre->centre_code }}</span>
                <span>Authorized Study Centre of <strong>{{ $instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER' }}</strong></span>
            </div>
            <div class="d-flex align-items-center gap-3">
                @if(!empty($landingPage->custom_phone))
                    <a href="tel:{{ $landingPage->custom_phone }}" class="text-white text-decoration-none">
                        <i class="fa-solid fa-phone me-1 text-centre-accent"></i> {{ $landingPage->custom_phone }}
                    </a>
                @endif
                @if(!empty($landingPage->custom_email))
                    <a href="mailto:{{ $landingPage->custom_email }}" class="text-white text-decoration-none d-none d-md-inline">
                        <i class="fa-solid fa-envelope me-1 text-centre-accent"></i> {{ $landingPage->custom_email }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Centre Navbar -->
    <nav class="navbar navbar-expand-lg centre-nav sticky-top py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url()->current() }}">
                <img src="{{ $landingPage->effective_logo_url }}" alt="{{ $landingPage->custom_centre_name ?? $centre->centre_name }}" height="48" class="rounded" onerror="this.onerror=null;this.src='{{ asset('images/tech-era-logo.png') }}';">
                <div>
                    <div class="text-centre-accent fw-bold text-uppercase d-block" style="font-size: 0.68rem; letter-spacing: 0.4px;">
                        <i class="fa-solid fa-shield-halved me-1"></i> Authorized Study Center of Tech Era Computer
                    </div>
                    <h5 class="fw-bold mb-0 text-centre-primary" style="letter-spacing: -0.3px;">{{ $landingPage->custom_centre_name ?? $centre->centre_name }}</h5>
                    <span class="small text-muted d-block font-monospace" style="font-size: 0.75rem;">{{ $landingPage->header_subtext ?? ('Code: ' . $centre->centre_code . ' | ' . $centre->city) }}</span>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#centreNavCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="centreNavCollapse">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-2 mt-3 mt-lg-0">
                    <li class="nav-item"><a class="nav-link fw-semibold" href="#about">About Centre</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="#courses">Courses</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="#digital-store">E-Books & Videos</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="#facilities">Facilities</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="#gallery">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="#contact">Contact</a></li>
                    <li class="nav-item ms-lg-2">
                        <button type="button" class="btn btn-centre-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#enquiryModal">
                            <i class="fa-solid fa-file-pen me-1"></i> Apply / Enquiry
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero / Multiple Banner Slider -->
    @if($centre->banners->isNotEmpty())
        <div id="centreHeroCarousel" class="carousel slide carousel-fade centre-hero-carousel" data-bs-ride="carousel" data-bs-interval="3500" data-bs-pause="hover">
            <!-- Slide Indicators -->
            <div class="carousel-indicators mb-3">
                @foreach($centre->banners as $idx => $banner)
                    <button type="button" data-bs-target="#centreHeroCarousel" data-bs-slide-to="{{ $idx }}" class="{{ $idx === 0 ? 'active' : '' }}" aria-current="{{ $idx === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $idx + 1 }}"></button>
                @endforeach
            </div>

            <!-- Slide Items -->
            <div class="carousel-inner h-100">
                @foreach($centre->banners as $idx => $banner)
                    <div class="carousel-item h-100 {{ $idx === 0 ? 'active' : '' }}" data-bs-interval="3500">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Centre Slide' }}" class="w-100 h-100 object-fit-cover">
                        <div class="centre-slide-overlay">
                            <div style="max-width: 680px;">
                                <span class="badge bg-centre-accent text-white font-monospace px-3 py-1.5 fw-bold text-uppercase mb-2 shadow-sm">
                                    {{ $centre->centre_name }}
                                </span>
                                @if(!empty($banner->title))
                                    <h1 class="display-6 fw-black text-white mb-2" style="text-shadow: 0 2px 8px rgba(0,0,0,0.5);">{{ $banner->title }}</h1>
                                @endif
                                @if(!empty($banner->subtitle))
                                    <p class="lead text-white opacity-90 mb-4" style="text-shadow: 0 1px 4px rgba(0,0,0,0.5);">{{ $banner->subtitle }}</p>
                                @endif
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-centre-accent btn-lg px-4 rounded-pill fw-bold shadow" data-bs-toggle="modal" data-bs-target="#enquiryModal">
                                        {{ $banner->button_text ?? 'Enroll in New Batch' }} <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </button>
                                    <a href="#courses" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                                        View Courses
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Controls (Prev / Next) -->
            @if($centre->banners->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#centreHeroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#centreHeroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>
    @else
        <!-- Default Hero Stage -->
        <section class="py-4 text-white" style="background: linear-gradient(135deg, var(--centre-primary) 0%, var(--centre-dark) 100%);">
            <div class="container">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <span class="badge bg-centre-accent text-white px-3 py-2 fw-bold text-uppercase mb-3 font-monospace">
                            <i class="fa-solid fa-award me-1"></i> Authorized Study Centre ({{ $centre->centre_code }})
                        </span>
                        <h1 class="display-5 fw-black text-white mb-3">{{ $centre->centre_name }}</h1>
                        <p class="lead opacity-90 mb-4">{{ $landingPage->tagline ?? 'Equipping students with industry-standard computer and software skills with 100% genuine certification.' }}</p>
                        <div class="d-flex flex-wrap gap-3">
                            <button type="button" class="btn btn-centre-accent btn-lg px-4 rounded-pill fw-bold shadow" data-bs-toggle="modal" data-bs-target="#enquiryModal">
                                <i class="fa-solid fa-file-pen me-2"></i> Join Our Batches
                            </button>
                            <a href="#courses" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                                View Courses
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="bg-white p-4 rounded-4 shadow-lg text-dark">
                            <h5 class="fw-bold text-centre-primary mb-3"><i class="fa-solid fa-paper-plane text-centre-accent me-2"></i> Fast Admission Enquiry</h5>
                            @if(session('enquiry_success'))
                                <div class="alert alert-success small py-2 px-3 mb-3">
                                    {{ session('enquiry_success') }}
                                </div>
                            @endif
                            <form action="{{ route('franchise.landing.enquiry', $centre->centre_code) }}" method="POST">
                                @csrf
                                <div class="mb-2">
                                    <input type="text" name="name" class="form-control form-control-sm" required placeholder="Student's Full Name">
                                </div>
                                <div class="mb-2">
                                    <input type="tel" name="mobile" maxlength="10" pattern="[0-9]{10}" class="form-control form-control-sm" required placeholder="10-Digit Mobile Number">
                                </div>
                                <div class="mb-2">
                                    <select name="course_id" class="form-select form-select-sm">
                                        <option value="">-- Interested Course --</option>
                                        @foreach($courses as $c)
                                            <option value="{{ $c->id }}">{{ $c->course_name }} ({{ $c->course_code }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <textarea name="message" rows="2" class="form-control form-control-sm" placeholder="Any question or preferred batch timing..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-centre-primary w-100 py-2 rounded-pill fw-bold shadow-sm">
                                    Submit Enquiry to Centre
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- About Centre Section -->
    <section id="about" class="py-4 bg-white">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="badge bg-light text-centre-primary border px-3 py-2 fw-bold text-uppercase mb-2 font-monospace">Authorized Campus</span>
                    <h2 class="fw-black text-centre-primary mb-3">{{ $landingPage->about_heading ?? ('About ' . $centre->centre_name) }}</h2>
                    <div class="text-muted lh-lg mb-4">
                        {!! $landingPage->about_content ? nl2br(e($landingPage->about_content)) : '<p>Welcome to our authorized computer education centre. We are committed to providing top-quality technical training.</p>' !!}
                    </div>

                    <!-- Director Profile Box -->
                    <div class="p-4 bg-light rounded-4 border-start border-4 border-centre-accent shadow-sm">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $landingPage->effective_owner_photo_url }}" alt="{{ $landingPage->owner_name ?? $centre->director_name }}" class="rounded-circle shadow-sm object-fit-cover" width="68" height="68" onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $landingPage->owner_name ?? $centre->director_name }}</h6>
                                <span class="small text-muted">{{ $landingPage->owner_title ?? 'Centre Director / Head' }}</span>
                            </div>
                        </div>
                        @if(!empty($landingPage->owner_message))
                            <p class="fst-italic text-muted small mt-3 mb-0">"{{ $landingPage->owner_message }}"</p>
                        @endif
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-centre-primary text-white py-3 px-4">
                            <h6 class="mb-0 fw-bold"><i class="fa-solid fa-id-card me-2 text-centre-accent"></i> Centre Affiliation Credential</h6>
                        </div>
                        <div class="card-body p-4 bg-white">
                            <table class="table table-borderless small mb-0">
                                <tr>
                                    <td class="text-muted" style="width: 130px;">Centre Code:</td>
                                    <td class="font-monospace fw-bold text-centre-primary">{{ $centre->centre_code }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Standing:</td>
                                    <td><span class="badge bg-success">AUTHORIZED BRANCH</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Head Office:</td>
                                    <td><strong>{{ $instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER' }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Direct Mobile:</td>
                                    <td class="font-monospace">{{ $centre->mobile }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Campus Address:</td>
                                    <td>{{ $centre->address }}, {{ $centre->city }} ({{ $centre->district?->name ?? 'Bihar' }})</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Facilities Section -->
    <section id="facilities" class="py-4 bg-light">
        <div class="container">
            <div class="text-center max-w-700 mx-auto mb-3 mb-md-4">
                <span class="badge bg-centre-accent text-white px-3 py-1.5 fw-bold text-uppercase mb-2 font-monospace">Campus Facilities</span>
                <h2 class="fw-black text-centre-primary mb-1">Modern Lab & Training Amenities</h2>
                <p class="text-muted small mb-0">Everything you need for intensive practice, speed building, and conceptual clarity.</p>
            </div>

            <div class="row g-2.5 g-md-3">
                @php
                    $facilitiesList = $landingPage->facilities ?? ['High-Speed Wi-Fi', '1:1 Computer Ratio', 'Air Conditioned Lab', 'Printed Study Materials', 'Doubt Clearing Sessions', 'Job Oriented Diploma'];
                @endphp
                @foreach($facilitiesList as $fac)
                    <div class="col-md-6 col-lg-4">
                        <div class="centre-facility-pill d-flex align-items-center gap-3">
                            <i class="fa-solid fa-circle-check text-centre-accent fs-5"></i>
                            <span class="fw-semibold text-dark">{{ $fac }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Courses Section -->
    <section id="courses" class="py-4 bg-white">
        <div class="container">
            <div class="text-center max-w-700 mx-auto mb-3 mb-md-4">
                <span class="badge bg-centre-primary text-white px-3 py-1.5 fw-bold text-uppercase mb-2 font-monospace">Academic Programs</span>
                <h2 class="fw-black text-centre-primary mb-1">Certified Courses Offered</h2>
                <p class="text-muted small mb-0">Nationally accredited certificates and diplomas valid for private and government job selections.</p>
            </div>

            <div class="row g-2.5 g-sm-3 g-lg-4">
                @forelse($courses as $course)
                    @php
                        $courseImg = $course->thumbnail_url;
                    @endphp
                    <div class="col-6 col-md-4 col-lg-3 {{ $loop->index >= 12 ? 'dce-extra-course d-none' : '' }}">
                        <div class="dce-course-grid-card">
                            <!-- Course Thumbnail Image with Floating Badges -->
                            <div class="dce-course-img-wrap">
                                <div class="d-block w-100 h-100 position-relative">
                                    <img src="{{ $courseImg }}" 
                                         alt="{{ $course->course_name }}" 
                                         class="dce-course-img"
                                         loading="lazy"
                                         onerror="this.onerror=null; this.src='{{ asset('images/courses/default-course.svg') }}';">
                                </div>
                                <span class="dce-course-code-badge">{{ $course->course_code }}</span>
                                <span class="dce-course-cat-badge">{{ $course->category->name ?? 'Certified' }}</span>
                            </div>

                            <!-- Course Body -->
                            <div class="dce-course-body">
                                <div class="dce-course-title-clamp" title="{{ $course->course_name }}">
                                    {{ ucwords(strtolower($course->course_name)) }}
                                </div>

                                <div class="dce-course-meta-row">
                                    <span class="dce-course-pill">
                                        <i class="fa-regular fa-clock text-primary"></i> {{ $course->duration }}
                                    </span>
                                    <span class="dce-course-pill d-none d-sm-inline-flex">
                                        <i class="fa-solid fa-laptop-code text-success"></i> Practical
                                    </span>
                                </div>

                                <div class="dce-course-footer">
                                    <div class="dce-course-pricing">
                                        <span class="dce-course-price-lbl">Fee</span>
                                        <span class="dce-course-price">₹{{ number_format($course->default_course_fee ?? $course->course_fee ?? 0, 0) }}</span>
                                    </div>
                                    <button type="button" class="btn btn-centre-primary dce-course-btn btn-apply-course shadow-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#enquiryModal"
                                            data-course-id="{{ $course->id }}"
                                            data-course-name="{{ $course->course_name }} ({{ $course->course_code }})">
                                        <span>Apply</span>
                                        <i class="fa-solid fa-paper-plane small ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4 text-muted">
                        No courses currently listed. Please contact the centre directly for batch details.
                    </div>
                @endforelse
            </div>

            @if($courses->count() > 12)
                <div class="text-center mt-4 pt-2">
                    <button type="button" id="btnToggleAllCourses" class="btn btn-outline-primary fw-bold rounded-pill px-4 py-2 shadow-sm" style="border-color: var(--centre-primary); color: var(--centre-primary);" onclick="toggleAllCourses()">
                        <i class="fa-solid fa-list-check me-1"></i> <span id="textToggleAllCourses">View All Courses ({{ $courses->count() }})</span>
                    </button>
                </div>
            @endif
        </div>
    </section>

    <!-- Digital Store: E-Books, PDF Notes & Video Lectures -->
    @if(isset($digitalProducts) && $digitalProducts->isNotEmpty())
        <section id="digital-store" class="py-4 bg-light border-top border-bottom">
            <div class="container">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-3 mb-md-4">
                    <div>
                        <span class="badge bg-centre-accent text-white px-3 py-1.5 fw-bold text-uppercase mb-2 font-monospace">E-Learning & Self-Study Hub</span>
                        <h2 class="fw-black text-centre-primary mb-1">E-Books, Notes & Video Lectures</h2>
                        <p class="text-muted small mb-0">Certified computer notes (PDF) and video lecture classes available for self-study.</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="{{ route('store.index') }}" class="btn btn-outline-primary fw-bold rounded-pill px-4 shadow-sm" style="border-color: var(--centre-primary); color: var(--centre-primary);">
                            <i class="fa-solid fa-store me-1"></i> View All Store Materials
                        </a>
                    </div>
                </div>

                <div class="row g-2.5 g-sm-3 g-lg-4">
                    @foreach($digitalProducts as $prod)
                        <div class="col-6 col-md-4 col-lg-3 {{ $loop->index >= 12 ? 'dce-extra-store d-none' : '' }}">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden d-flex flex-column transition-hover dce-store-card" style="border: 1px solid #e2e8f0 !important; background: #ffffff;">
                                <!-- Thumbnail & Floating Badges (Clickable to Details) -->
                                <a href="{{ route('store.show', ['type' => $prod->type, 'slug' => $prod->slug]) }}" class="d-block position-relative dce-store-thumb text-decoration-none" style="height: 160px; background-color: var(--centre-primary, #0d2238); overflow: hidden;">
                                    @if($prod->thumbnail)
                                        <img src="{{ $prod->thumbnail_url }}" alt="{{ $prod->title }}" class="w-100 h-100 object-fit-cover">
                                    @else
                                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white p-3 text-center">
                                            <i class="fa-solid {{ $prod->type === 'video' ? 'fa-play-circle fa-3x' : 'fa-file-pdf fa-3x' }} mb-2 text-warning"></i>
                                            <span class="small text-uppercase fw-bold opacity-75">{{ $prod->category ?? 'Study Material' }}</span>
                                        </div>
                                    @endif

                                    <!-- Product Type Badge -->
                                    <span class="position-absolute top-0 start-0 m-2 badge {{ $prod->type === 'video' ? ($prod->isPublicVideo() ? 'bg-success' : 'bg-danger') : 'bg-primary' }} text-white px-2 py-1 shadow-sm font-monospace" style="font-size: 0.65rem;">
                                        <i class="fa-solid {{ $prod->type === 'video' ? ($prod->isPublicVideo() ? 'fa-globe' : 'fa-lock') : 'fa-file-pdf' }} me-1"></i>
                                        @if($prod->type === 'video')
                                            {{ $prod->isPublicVideo() ? 'Free Lecture' : 'Private Video' }}
                                        @else
                                            PDF E-Book
                                        @endif
                                    </span>

                                    @if($prod->has_discount && !$prod->isPublicVideo())
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-success text-white px-2 py-1 shadow-sm font-monospace" style="font-size: 0.65rem;">
                                            {{ $prod->discount_percentage }}% OFF
                                        </span>
                                    @endif
                                </a>

                                <!-- Card Body -->
                                <div class="card-body p-3 d-flex flex-column flex-grow-1">
                                    <span class="text-muted text-uppercase fw-bold font-monospace" style="font-size: 0.68rem;">{{ $prod->category ?? 'Study Material' }}</span>
                                    <h6 class="fw-bold text-dark mt-1 mb-2 text-truncate" title="{{ $prod->title }}" style="font-size: 0.88rem;">
                                        <a href="{{ route('store.show', ['type' => $prod->type, 'slug' => $prod->slug]) }}" class="text-dark text-decoration-none">
                                            {{ $prod->title }}
                                        </a>
                                    </h6>
                                    <p class="text-muted small mb-3 flex-grow-1 dce-store-desc" style="font-size: 0.78rem; min-height: 2.4em; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit($prod->short_description ?? strip_tags($prod->description ?? ''), 70) }}
                                    </p>

                                    <!-- Meta Strip -->
                                    <div class="mb-2 pt-2 border-top d-flex justify-content-between align-items-center small text-muted" style="font-size: 0.72rem;">
                                        @if($prod->type === 'pdf')
                                            <span><i class="fa-solid fa-file-invoice text-danger me-1"></i> {{ $prod->total_pages ? $prod->total_pages . ' Pages' : 'PDF' }}</span>
                                            <span class="text-success"><i class="fa-solid fa-download me-1"></i> Instant</span>
                                        @else
                                            <span><i class="fa-solid fa-clock text-info me-1"></i> {{ $prod->duration ?? 'HD Lecture' }}</span>
                                            @if($prod->isPublicVideo())
                                                <span class="text-success fw-bold"><i class="fa-solid fa-unlock me-1"></i> Open Free</span>
                                            @else
                                                <span class="text-danger fw-bold"><i class="fa-solid fa-lock me-1"></i> Invite Only</span>
                                            @endif
                                        @endif
                                    </div>

                                    <!-- Price & Button -->
                                    <div class="pt-2 border-top d-flex justify-content-between align-items-center mt-auto flex-wrap gap-2">
                                        <div class="d-flex flex-column">
                                            <span class="text-muted" style="font-size: 0.62rem; text-transform: uppercase; font-weight: 700;">Price</span>
                                            <div class="d-flex align-items-baseline gap-1">
                                                @if($prod->isPublicVideo() || $prod->effective_price <= 0)
                                                    <span class="fw-bold text-success fs-6">FREE</span>
                                                @else
                                                    <span class="fw-bold text-dark fs-6">₹{{ number_format($prod->effective_price, 0) }}</span>
                                                    @if($prod->has_discount)
                                                        <small class="text-muted text-decoration-line-through" style="font-size: 0.7rem;">₹{{ number_format($prod->price, 0) }}</small>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                            @if($prod->has_demo)
                                                <a href="{{ $prod->effective_demo_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary rounded-2 px-2.5 fw-semibold shadow-sm" style="font-size: 0.72rem;" title="View Demo">
                                                    <i class="fa-solid fa-eye me-1 text-primary"></i>Demo
                                                </a>
                                            @endif
                                            @if($prod->isPublicVideo())
                                                <a href="{{ route('store.show', ['type' => $prod->type, 'slug' => $prod->slug]) }}" class="btn btn-sm btn-success rounded-2 px-3 fw-bold shadow-sm" style="font-size: 0.75rem;">
                                                    <i class="fa-solid fa-circle-play me-1"></i> Watch Free
                                                </a>
                                            @else
                                                <a href="{{ route('store.show', ['type' => $prod->type, 'slug' => $prod->slug]) }}" class="btn btn-sm btn-centre-primary rounded-2 px-3 fw-bold shadow-sm" style="font-size: 0.75rem;">
                                                    <i class="fa-solid fa-cart-shopping me-1"></i> Buy
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($digitalProducts->count() > 12)
                    <div class="text-center mt-4 pt-2">
                        <button type="button" id="btnToggleAllStore" class="btn btn-outline-primary fw-bold rounded-pill px-4 py-2 shadow-sm" style="border-color: var(--centre-primary); color: var(--centre-primary);" onclick="toggleAllStore()">
                            <i class="fa-solid fa-store me-1"></i> <span id="textToggleAllStore">View All Products ({{ $digitalProducts->count() }})</span>
                        </button>
                    </div>
                @endif
            </div>
        </section>
    @endif

    <!-- Photo Gallery -->
    @if($centre->galleries->isNotEmpty())
        <section id="gallery" class="py-4 bg-light">
            <div class="container">
                <div class="text-center max-w-700 mx-auto mb-3 mb-md-4">
                    <span class="badge bg-centre-accent text-white px-3 py-1.5 fw-bold text-uppercase mb-2 font-monospace">Campus Life</span>
                    <h2 class="fw-black text-centre-primary mb-1">Photo Gallery</h2>
                    <p class="text-muted small mb-0">Take a look inside our computer labs, classrooms, and student events.</p>
                </div>

                <div class="row g-2.5 g-md-3">
                    @foreach($centre->galleries as $photo)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <img src="{{ $photo->image_url }}" alt="{{ $photo->caption ?? 'Gallery' }}" class="w-100 centre-gallery-img">
                                @if(!empty($photo->caption))
                                    <div class="card-footer bg-white border-0 py-1.5 px-3 small text-muted text-truncate">
                                        {{ $photo->caption }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Contact & Map Section -->
    <section id="contact" class="py-4 bg-white border-top">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <span class="badge bg-centre-accent text-white px-3 py-1.5 fw-bold text-uppercase mb-2 font-monospace">Connect With Us</span>
                    <h2 class="fw-black text-centre-primary mb-2">Visit or Call Our Campus</h2>
                    <p class="text-muted small mb-3">Feel free to walk in to our centre for free career counseling, batch schedule details, and demo classes.</p>

                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-centre-primary text-white" style="width: 44px; height: 44px; flex-shrink: 0;">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <strong class="d-block text-dark">Address:</strong>
                            <span class="text-muted small">{{ $landingPage->address_display ?? ($centre->address . ', ' . $centre->city) }}</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-centre-accent text-white" style="width: 44px; height: 44px; flex-shrink: 0;">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <strong class="d-block text-dark">Phone Number:</strong>
                            <a href="tel:{{ $landingPage->custom_phone ?? $centre->mobile }}" class="text-decoration-none text-muted small font-monospace">{{ $landingPage->custom_phone ?? $centre->mobile }}</a>
                        </div>
                    </div>

                    @if(!empty($landingPage->social_links))
                        <div class="mt-4 pt-3 border-top">
                            <span class="d-block small text-muted mb-2 fw-bold">Follow Our Centre:</span>
                            <div class="d-flex gap-2">
                                @foreach($landingPage->social_links as $network => $url)
                                    @if(!empty($url))
                                        <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="fa-brands fa-{{ $network }}"></i>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Google Map Embed or Map Placeholder -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="height: 380px;">
                        @if(!empty($landingPage->google_map_embed))
                            {!! $landingPage->google_map_embed !!}
                        @else
                            <iframe src="https://maps.google.com/maps?q={{ urlencode($centre->centre_name . ' ' . $centre->city) }}&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fast Admission Enquiry & Counselor Helpline Section -->
    <section id="enquiry" class="py-4 bg-light border-top border-bottom">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <span class="badge bg-centre-accent text-white px-3 py-1.5 fw-bold text-uppercase mb-2 font-monospace">Admissions Open</span>
                    <h2 class="fw-black text-centre-primary mb-2">Direct Admission & Course Enquiry</h2>
                    <p class="text-muted small mb-3">Interested in enrolling or have questions regarding course curriculum, fees, and batch schedules? Fill in your details below and our branch counselor will contact you immediately.</p>

                    <div class="card border-0 rounded-4 shadow-sm p-3 bg-white mb-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-centre-primary text-white d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; flex-shrink: 0;">
                                <i class="fa-solid fa-headset fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0.5 text-dark">Need Immediate Help?</h6>
                                <p class="small text-muted mb-0">Call or WhatsApp our centre counselor directly for spot counseling.</p>
                                <a href="tel:{{ $landingPage->custom_phone ?? $centre->mobile }}" class="fw-bold text-centre-primary text-decoration-none font-monospace small">
                                    <i class="fa-solid fa-phone me-1 text-centre-accent"></i> {{ $landingPage->custom_phone ?? $centre->mobile }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 rounded-4 shadow-sm p-3 p-md-4 bg-white">
                        <h5 class="fw-black text-centre-primary mb-1">
                            <i class="fa-solid fa-paper-plane text-centre-accent me-2"></i>Quick Admission Enquiry
                        </h5>
                        <p class="small text-muted mb-3">Please submit your contact info to get full details and fee structure.</p>

                        @if(session('enquiry_success'))
                            <div class="alert alert-success alert-dismissible fade show rounded-3 small p-3 mb-3 shadow-sm" role="alert">
                                <i class="fa-solid fa-circle-check me-2"></i>{{ session('enquiry_success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(isset($errors) && $errors->any())
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 small p-3 mb-3 shadow-sm" role="alert">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i>Please check the required fields.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('franchise.landing.enquiry', $centre->centre_code) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Student's Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control rounded-3" required placeholder="Enter student's full name" value="{{ old('name') }}">
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-dark">Mobile Number <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">+91</span>
                                        <input type="tel" name="mobile" maxlength="10" pattern="[0-9]{10}" class="form-control" required placeholder="10-digit number" value="{{ old('mobile') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-dark">Email Address <small class="text-muted">(Optional)</small></label>
                                    <input type="email" name="email" class="form-control rounded-3" placeholder="email@example.com" value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Interested Course <span class="text-danger">*</span></label>
                                <select name="course_id" class="form-select rounded-3" required>
                                    <option value="">-- Choose Course --</option>
                                    @foreach($courses as $c)
                                        <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->course_code }} - {{ $c->course_name }} ({{ $c->duration }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Query / Preferred Timing <small class="text-muted">(Optional)</small></label>
                                <textarea name="message" rows="2" class="form-control rounded-3" placeholder="Morning / Evening batch preference, or questions...">{{ old('message') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-centre-primary w-100 py-2.5 rounded-pill fw-bold shadow-sm">
                                <i class="fa-solid fa-paper-plane me-2"></i> Submit Enquiry to Centre
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-centre-primary text-white py-3 border-top border-3 border-centre-accent">
        <div class="container text-center">
            <p class="mb-1 fw-bold">{{ $centre->centre_name }} (Centre Code: {{ $centre->centre_code }})</p>
            <p class="small text-white-50 mb-0">Affiliated with {{ $instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER' }} | Verified Study Branch</p>
        </div>
    </footer>

    <!-- WhatsApp Floating Action Button -->
    @if(!empty($landingPage->custom_whatsapp ?? $centre->mobile))
        <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $landingPage->custom_whatsapp ?? $centre->mobile) }}?text=Hello%20{{ urlencode($centre->centre_name) }},%20I%20want%20to%20know%20more%20about%20computer%20courses." target="_blank" class="floating-whatsapp" title="Chat on WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
    @endif

    <!-- Mobile Sticky Footer Navigation Bar -->
    <div class="centre-mobile-bottom-bar d-lg-none">
        <div class="d-flex justify-content-around align-items-center">
            <a href="#about" class="centre-mob-btn">
                <i class="fa-solid fa-school"></i>
                <span>About</span>
            </a>
            <a href="#courses" class="centre-mob-btn">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Courses</span>
            </a>
            @if(isset($digitalProducts) && $digitalProducts->isNotEmpty())
                <a href="#digital-store" class="centre-mob-btn">
                    <i class="fa-solid fa-book-open-reader"></i>
                    <span>Store</span>
                </a>
            @endif
            @if(!empty($landingPage->custom_phone ?? $centre->mobile))
                <a href="tel:{{ $landingPage->custom_phone ?? $centre->mobile }}" class="centre-mob-btn text-success">
                    <i class="fa-solid fa-phone"></i>
                    <span>Call</span>
                </a>
            @endif
            <button type="button" class="centre-mob-btn border-0 bg-transparent text-primary" data-bs-toggle="modal" data-bs-target="#enquiryModal">
                <i class="fa-solid fa-file-pen text-centre-accent"></i>
                <span>Apply</span>
            </button>
        </div>
    </div>

    <!-- Interactive Apply / Admission Enquiry Modal -->
    <div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                <div class="modal-header bg-centre-primary text-white py-3 px-4">
                    <div>
                        <h5 class="modal-title fw-bold" id="enquiryModalLabel">
                            <i class="fa-solid fa-graduation-cap text-centre-accent me-2"></i>Apply for Course Admission
                        </h5>
                        <p class="small text-white-50 mb-0 font-monospace">{{ $centre->centre_name }} ({{ $centre->centre_code }})</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <div id="modalSelectedCourseAlert" class="alert alert-primary py-2 px-3 small rounded-3 mb-3 d-none">
                        <i class="fa-solid fa-bookmark me-1"></i> Selected Course: <strong id="modalSelectedCourseName"></strong>
                    </div>

                    <form action="{{ route('franchise.landing.enquiry', $centre->centre_code) }}" method="POST" id="enquiryModalForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">Student's Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-3" required placeholder="Enter student's full name" value="{{ old('name') }}">
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark">Mobile Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light font-monospace">+91</span>
                                    <input type="tel" name="mobile" maxlength="10" pattern="[0-9]{10}" class="form-control" required placeholder="10-digit mobile" value="{{ old('mobile') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark">Email <small class="text-muted">(Optional)</small></label>
                                <input type="email" name="email" class="form-control rounded-3" placeholder="email@example.com" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">Interested Course <span class="text-danger">*</span></label>
                            <select name="course_id" id="modalCourseSelect" class="form-select rounded-3" required>
                                <option value="">-- Choose Course --</option>
                                @foreach($courses as $c)
                                    <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->course_code }} - {{ $c->course_name }} ({{ $c->duration }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">Message / Preferred Shift <small class="text-muted">(Optional)</small></label>
                            <textarea name="message" rows="2" class="form-control rounded-3" placeholder="Preferred timing or batch requirements...">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-centre-primary w-100 py-2.5 rounded-pill fw-bold shadow-sm">
                            <i class="fa-solid fa-paper-plane me-2"></i> Submit Application to Centre
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalEl = document.getElementById('enquiryModal');
            const courseSelect = document.getElementById('modalCourseSelect');
            const courseAlert = document.getElementById('modalSelectedCourseAlert');
            const courseNameSpan = document.getElementById('modalSelectedCourseName');

            // Wire up all "Apply" buttons on course cards
            document.querySelectorAll('.btn-apply-course').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const courseId = this.getAttribute('data-course-id');
                    const courseName = this.getAttribute('data-course-name');

                    if (courseSelect && courseId) {
                        courseSelect.value = courseId;
                    }
                    if (courseAlert && courseNameSpan && courseName) {
                        courseNameSpan.textContent = courseName;
                        courseAlert.classList.remove('d-none');
                    }
                });
            });

            // When modal closed, reset course alert
            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', function () {
                    if (courseAlert) {
                        courseAlert.classList.add('d-none');
                    }
                });
            }

            @if(session('enquiry_success'))
                // Smooth scroll to enquiry confirmation if returned
                const enqSection = document.getElementById('enquiry');
                if (enqSection) {
                    enqSection.scrollIntoView({ behavior: 'smooth' });
                }
            @endif
        });

        function toggleAllCourses() {
            const extraCourses = document.querySelectorAll('.dce-extra-course');
            const textSpan = document.getElementById('textToggleAllCourses');
            if (!extraCourses.length) return;
            
            const isHidden = extraCourses[0].classList.contains('d-none');
            extraCourses.forEach(function(el) {
                if (isHidden) {
                    el.classList.remove('d-none');
                } else {
                    el.classList.add('d-none');
                }
            });

            if (textSpan) {
                textSpan.textContent = isHidden ? 'Show Less Courses' : 'View All Courses ({{ $courses->count() }})';
            }
        }

        function toggleAllStore() {
            const extraStore = document.querySelectorAll('.dce-extra-store');
            const textSpan = document.getElementById('textToggleAllStore');
            if (!extraStore.length) return;

            const isHidden = extraStore[0].classList.contains('d-none');
            extraStore.forEach(function(el) {
                if (isHidden) {
                    el.classList.remove('d-none');
                } else {
                    el.classList.add('d-none');
                }
            });

            if (textSpan) {
                textSpan.textContent = isHidden ? 'Show Less Products' : 'View All Products ({{ isset($digitalProducts) ? $digitalProducts->count() : 0 }})';
            }
        }
    </script>
</body>
</html>
