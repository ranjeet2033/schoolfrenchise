@extends('layouts.app')

@section('title', 'E-Books, Study Notes & Video Lectures Store | ' . ($instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER'))
@section('meta_description', 'Purchase high-quality computer study notes, e-books, vocational PDFs, and specialized private video lectures with instant access.')

@section('content')
<!-- Header Banner -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, #0d2238 0%, #1a3a5c 100%);">
    <div class="container py-3">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-3"><i class="fa-solid fa-graduation-cap me-1"></i> Digital Learning Hub</span>
                <h1 class="display-5 fw-black mb-2">E-Books, Notes & Video Courses</h1>
                <p class="lead opacity-85 mb-0">High-scoring computer exam notes, PDFs, and exclusive private video training with verified certification preparation.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <a href="{{ route('store.purchases') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill shadow-sm">
                    <i class="fa-solid fa-bag-shopping me-2"></i> My Purchases / Downloads
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Filter Strip -->
<div class="bg-white border-bottom shadow-sm sticky-top" style="top: 70px; z-index: 1020;">
    <div class="container py-3">
        <div class="row align-items-center g-3">
            <div class="col-md-6 col-lg-7">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <a href="{{ route('store.index') }}" class="btn btn-sm rounded-pill {{ empty($type) ? 'btn-primary' : 'btn-outline-secondary' }} px-3">
                        <i class="fa-solid fa-border-all me-1"></i> All Products ({{ $products->total() }})
                    </a>
                    <a href="{{ route('store.index', ['type' => 'pdf']) }}" class="btn btn-sm rounded-pill {{ $type === 'pdf' ? 'btn-danger' : 'btn-outline-danger' }} px-3">
                        <i class="fa-solid fa-file-pdf me-1"></i> E-Books & Notes ({{ $totalPdfs }})
                    </a>
                    <a href="{{ route('store.index', ['type' => 'video']) }}" class="btn btn-sm rounded-pill {{ $type === 'video' ? 'btn-info' : 'btn-outline-info' }} px-3">
                        <i class="fa-solid fa-video me-1"></i> Video Courses ({{ $totalVideos }})
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-5">
                <form action="{{ route('store.index') }}" method="GET" class="d-flex gap-2">
                    @if(!empty($type))
                        <input type="hidden" name="type" value="{{ $type }}">
                    @endif
                    <div class="input-group">
                        <input type="text" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search by course, topic, notes...">
                        <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Products Grid -->
<section class="py-5 bg-light">
    <div class="container">
        @if($products->isEmpty())
            <div class="text-center py-5 bg-white rounded-4 shadow-sm p-5">
                <div class="mb-3">
                    <i class="fa-solid fa-box-open fa-4x text-muted opacity-50"></i>
                </div>
                <h4 class="fw-bold text-dark">No Products Found</h4>
                <p class="text-muted">No digital items currently match your selection. Check back soon or clear search filters.</p>
                <a href="{{ route('store.index') }}" class="btn btn-outline-primary rounded-pill px-4">Browse All Store Products</a>
            </div>
        @else
            <div class="row g-4">
                @foreach($products as $prod)
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden d-flex flex-column transition-hover" style="transition: transform 0.2s, box-shadow 0.2s;">
                            <!-- Thumbnail & Badges -->
                            <div class="position-relative" style="height: 190px; background-color: #0d2238; overflow: hidden;">
                                @if($prod->thumbnail)
                                    <img src="{{ $prod->thumbnail_url }}" alt="{{ $prod->title }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white p-3 text-center">
                                        <i class="fa-solid {{ $prod->type === 'video' ? 'fa-play-circle fa-3x' : 'fa-file-pdf fa-3x' }} mb-2 text-warning"></i>
                                        <span class="small text-uppercase fw-bold opacity-75">{{ $prod->category ?? 'Study Material' }}</span>
                                    </div>
                                @endif

                                <!-- Product Type Badge -->
                                <span class="position-absolute top-0 start-0 m-2 badge {{ $prod->type === 'video' ? ($prod->isPublicVideo() ? 'bg-success' : 'bg-danger') : 'bg-primary' }} text-white px-2 py-1 shadow-sm font-monospace">
                                    <i class="fa-solid {{ $prod->type === 'video' ? ($prod->isPublicVideo() ? 'fa-globe' : 'fa-lock') : 'fa-file-lines' }} me-1"></i>
                                    @if($prod->type === 'video')
                                        {{ $prod->isPublicVideo() ? 'Free Lecture' : 'Private Video' }}
                                    @else
                                        PDF E-Book
                                    @endif
                                </span>

                                @if($prod->has_discount && !$prod->isPublicVideo())
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-success text-white px-2 py-1 shadow-sm">
                                        {{ $prod->discount_percentage }}% OFF
                                    </span>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="card-body p-3 d-flex flex-column flex-grow-1">
                                <span class="text-muted text-uppercase fw-bold font-monospace small" style="font-size: 0.72rem;">{{ $prod->category ?? 'Study Material' }}</span>
                                <h6 class="fw-bold text-dark mt-1 mb-2 line-clamp-2" style="min-height: 2.6rem;">
                                    <a href="{{ route('store.show', ['type' => $prod->type, 'slug' => $prod->slug]) }}" class="text-dark text-decoration-none">
                                        {{ $prod->title }}
                                    </a>
                                </h6>
                                <p class="text-muted small mb-3 flex-grow-1 line-clamp-2">
                                    {{ $prod->short_description ?? Str::limit(strip_tags($prod->description), 85) }}
                                </p>

                                <!-- Meta tags -->
                                <div class="mb-3 pt-2 border-top d-flex justify-content-between align-items-center small text-muted">
                                    @if($prod->type === 'pdf')
                                        <span><i class="fa-solid fa-file-invoice text-danger me-1"></i> {{ $prod->total_pages ? $prod->total_pages . ' Pages' : 'Instant PDF' }}</span>
                                        <span class="text-success"><i class="fa-solid fa-download me-1"></i> Instant</span>
                                    @else
                                        <span><i class="fa-solid fa-clock text-info me-1"></i> {{ $prod->duration ?? 'HD Lecture' }}</span>
                                        @if($prod->isPublicVideo())
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"><i class="fa-solid fa-unlock me-1"></i> Free Public</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25"><i class="fa-solid fa-lock me-1"></i> Private Invite</span>
                                        @endif
                                    @endif
                                </div>

                                <!-- Price & Action -->
                                <div class="d-flex align-items-center justify-content-between mt-auto">
                                    <div>
                                        @if($prod->isPublicVideo() || $prod->effective_price <= 0)
                                            <span class="fs-5 fw-black text-success">FREE</span>
                                        @else
                                            <span class="fs-5 fw-black text-primary">₹{{ number_format($prod->effective_price, 2) }}</span>
                                            @if($prod->has_discount)
                                                <span class="text-muted text-decoration-line-through small ms-1">₹{{ number_format($prod->price, 2) }}</span>
                                            @endif
                                        @endif
                                    </div>
                                    @if($prod->isPublicVideo())
                                        <a href="{{ route('store.show', ['type' => $prod->type, 'slug' => $prod->slug]) }}" class="btn btn-sm btn-success px-3 rounded-pill fw-bold shadow-sm">
                                            Watch Free <i class="fa-solid fa-circle-play ms-1"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('store.show', ['type' => $prod->type, 'slug' => $prod->slug]) }}" class="btn btn-sm btn-primary px-3 rounded-pill fw-bold shadow-sm">
                                            Buy Now <i class="fa-solid fa-arrow-right ms-1"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</section>

<!-- Features Highlights -->
<section class="py-5 bg-white border-top">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary mb-3" style="width: 60px; height: 60px;">
                        <i class="fa-solid fa-bolt fa-2x"></i>
                    </div>
                    <h5 class="fw-bold">Instant Secure Delivery</h5>
                    <p class="text-muted small mb-0">PDF notes are available for download in your dashboard immediately upon Razorpay payment confirmation.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 text-danger mb-3" style="width: 60px; height: 60px;">
                    <i class="fa-brands fa-youtube fa-2x"></i>
                </div>
                <h5 class="fw-bold">Private YouTube Video Access</h5>
                <p class="text-muted small mb-0">Video training is delivered privately to your Gmail ID. Only invited accounts can watch, ensuring zero unauthorized distribution.</p>
            </div>
            <div class="col-md-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success mb-3" style="width: 60px; height: 60px;">
                    <i class="fa-solid fa-shield-halved fa-2x"></i>
                </div>
                <h5 class="fw-bold">100% Verified Study Material</h5>
                <p class="text-muted small mb-0">Curated by veteran computer faculty aligned with latest Beltron, SSC, CCC, and vocational diploma syllabi.</p>
            </div>
        </div>
    </div>
</section>
@endsection
