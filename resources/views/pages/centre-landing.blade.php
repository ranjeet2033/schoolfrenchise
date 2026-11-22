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
            height: 480px;
            background-color: var(--centre-primary);
        }
        .centre-hero-carousel .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.65);
        }

        @media (max-width: 767.98px) {
            .centre-hero-carousel .carousel-item {
                height: 300px;
            }
        }

        .centre-facility-pill {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid var(--centre-accent);
            border-radius: 12px;
            padding: 14px 18px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .centre-facility-pill:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .centre-gallery-img {
            height: 220px;
            object-fit: cover;
            border-radius: 12px;
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
    <nav class="navbar navbar-expand-lg centre-nav sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url()->current() }}">
                <img src="{{ $landingPage->effective_logo_url }}" alt="{{ $centre->centre_name }}" height="48" class="rounded" onerror="this.onerror=null;this.src='{{ asset('images/tech-era-logo.png') }}';">
                <div>
                    <h5 class="fw-bold mb-0 text-centre-primary" style="letter-spacing: -0.3px;">{{ $centre->centre_name }}</h5>
                    <span class="small text-muted d-block font-monospace" style="font-size: 0.75rem;">Code: {{ $centre->centre_code }} | {{ $centre->city }}</span>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#centreNavCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="centreNavCollapse">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-2 mt-3 mt-lg-0">
                    <li class="nav-item"><a class="nav-link fw-semibold" href="#about">About Centre</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="#courses">Courses</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="#facilities">Facilities</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="#gallery">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="#contact">Contact</a></li>
                    <li class="nav-item ms-lg-2">
                        <a href="#enquiry" class="btn btn-centre-primary rounded-pill px-4 fw-bold shadow-sm">
                            <i class="fa-solid fa-file-pen me-1"></i> Apply / Enquiry
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero / Banner Slider -->
    @if($centre->banners->isNotEmpty())
        <div id="centreHeroCarousel" class="carousel slide centre-hero-carousel" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($centre->banners as $idx => $banner)
                    <button type="button" data-bs-target="#centreHeroCarousel" data-bs-slide-to="{{ $idx }}" class="{{ $idx === 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($centre->banners as $idx => $banner)
                    <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title ?? 'Centre Slide' }}">
                        <div class="carousel-caption text-start pb-5" style="max-width: 650px;">
                            @if(!empty($banner->title))
                                <h1 class="display-6 fw-black text-white mb-2">{{ $banner->title }}</h1>
                            @endif
                            @if(!empty($banner->subtitle))
                                <p class="lead text-white opacity-90 mb-3">{{ $banner->subtitle }}</p>
                            @endif
                            @if(!empty($banner->button_text))
                                <a href="{{ $banner->button_link ?? '#enquiry' }}" class="btn btn-centre-accent btn-lg px-4 rounded-pill fw-bold shadow">
                                    {{ $banner->button_text }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @if($centre->banners->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#centreHeroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#centreHeroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
        </div>
    @else
        <!-- Default Hero Stage -->
        <section class="py-5 text-white" style="background: linear-gradient(135deg, var(--centre-primary) 0%, var(--centre-dark) 100%);">
            <div class="container py-4">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <span class="badge bg-centre-accent text-white px-3 py-2 fw-bold text-uppercase mb-3 font-monospace">
                            <i class="fa-solid fa-award me-1"></i> Authorized Study Centre ({{ $centre->centre_code }})
                        </span>
                        <h1 class="display-5 fw-black text-white mb-3">{{ $centre->centre_name }}</h1>
                        <p class="lead opacity-90 mb-4">{{ $landingPage->tagline ?? 'Equipping students with industry-standard computer and software skills with 100% genuine certification.' }}</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="#enquiry" class="btn btn-centre-accent btn-lg px-4 rounded-pill fw-bold shadow">
                                <i class="fa-solid fa-file-pen me-2"></i> Join Our Batches
                            </a>
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
    <section id="about" class="py-5 bg-white">
        <div class="container py-3">
            <div class="row align-items-center g-5">
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
    <section id="facilities" class="py-5 bg-light">
        <div class="container">
            <div class="text-center max-w-700 mx-auto mb-5">
                <span class="badge bg-centre-accent text-white px-3 py-2 fw-bold text-uppercase mb-2 font-monospace">Campus Facilities</span>
                <h2 class="fw-black text-centre-primary">Modern Lab & Training Amenities</h2>
                <p class="text-muted">Everything you need for intensive practice, speed building, and conceptual clarity.</p>
            </div>

            <div class="row g-3">
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
    <section id="courses" class="py-5 bg-white">
        <div class="container py-3">
            <div class="text-center max-w-700 mx-auto mb-5">
                <span class="badge bg-centre-primary text-white px-3 py-2 fw-bold text-uppercase mb-2 font-monospace">Academic Programs</span>
                <h2 class="fw-black text-centre-primary">Certified Courses Offered</h2>
                <p class="text-muted">Nationally accredited certificates and diplomas valid for private and government job selections.</p>
            </div>

            <div class="row g-4">
                @foreach($courses->take(8) as $course)
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden d-flex flex-column">
                            <div class="card-body p-4 d-flex flex-column">
                                <span class="badge bg-light text-centre-primary border mb-2 align-self-start font-monospace">{{ $course->course_code }}</span>
                                <h6 class="fw-bold text-dark mb-2">{{ $course->course_name }}</h6>
                                <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($course->short_description ?? $course->description, 70) }}</p>
                                <div class="pt-3 border-top d-flex justify-content-between align-items-center small text-muted">
                                    <span><i class="fa-solid fa-clock text-centre-accent me-1"></i> {{ $course->duration }}</span>
                                    <a href="#enquiry" class="btn btn-sm btn-outline-primary rounded-pill">Apply</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Photo Gallery -->
    @if($centre->galleries->isNotEmpty())
        <section id="gallery" class="py-5 bg-light">
            <div class="container py-3">
                <div class="text-center max-w-700 mx-auto mb-5">
                    <span class="badge bg-centre-accent text-white px-3 py-2 fw-bold text-uppercase mb-2 font-monospace">Campus Life</span>
                    <h2 class="fw-black text-centre-primary">Photo Gallery</h2>
                    <p class="text-muted">Take a look inside our computer labs, classrooms, and student events.</p>
                </div>

                <div class="row g-4">
                    @foreach($centre->galleries as $photo)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <img src="{{ $photo->image_url }}" alt="{{ $photo->caption ?? 'Gallery' }}" class="w-100 centre-gallery-img">
                                @if(!empty($photo->caption))
                                    <div class="card-footer bg-white border-0 py-2 px-3 small text-muted text-truncate">
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
    <section id="contact" class="py-5 bg-white border-top">
        <div class="container py-3">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <span class="badge bg-centre-accent text-white px-3 py-2 fw-bold text-uppercase mb-2 font-monospace">Connect With Us</span>
                    <h2 class="fw-black text-centre-primary mb-3">Visit or Call Our Campus</h2>
                    <p class="text-muted mb-4">Feel free to walk in to our centre for free career counseling, batch schedule details, and demo classes.</p>

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

    <!-- Footer -->
    <footer class="bg-centre-primary text-white py-4 border-top border-4 border-centre-accent">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
