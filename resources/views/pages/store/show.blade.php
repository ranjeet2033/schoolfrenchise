@extends('layouts.app')

@section('title', $product->title . ' | Digital Store | ' . ($instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER'))
@section('meta_description', Str::limit(strip_tags($product->short_description ?? $product->description), 160))

@section('content')
<!-- Breadcrumbs -->
<div class="bg-light border-bottom py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('store.index') }}" class="text-decoration-none">Store</a></li>
                <li class="breadcrumb-item"><a href="{{ route('store.index', ['type' => $product->type]) }}" class="text-decoration-none">{{ $product->type === 'video' ? 'Video Courses' : 'PDF E-Books' }}</a></li>
                <li class="breadcrumb-item active text-truncate" aria-current="page">{{ $product->title }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Product Detail Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-5">
            <!-- Left Column: Visual Stage / Thumbnail -->
            <div class="col-lg-5">
                <div class="position-sticky" style="top: 100px;">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                        <div class="position-relative" style="height: 320px; background-color: #0d2238;">
                            @if($product->thumbnail)
                                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->title }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white p-4 text-center">
                                    <i class="fa-solid {{ $product->type === 'video' ? 'fa-play-circle fa-4x text-danger' : 'fa-file-pdf fa-4x text-warning' }} mb-3"></i>
                                    <h4 class="fw-bold">{{ $product->title }}</h4>
                                </div>
                            @endif

                            <span class="position-absolute top-0 start-0 m-3 badge {{ $product->type === 'video' ? ($product->isPublicVideo() ? 'bg-success' : 'bg-danger') : 'bg-primary' }} text-white px-3 py-2 shadow-sm font-monospace text-uppercase">
                                <i class="fa-solid {{ $product->type === 'video' ? ($product->isPublicVideo() ? 'fa-globe' : 'fa-lock') : 'fa-file-lines' }} me-1"></i>
                                @if($product->type === 'video')
                                    {{ $product->isPublicVideo() ? 'Free Public Lecture' : 'Private Video Lecture' }}
                                @else
                                    PDF Digital Book
                                @endif
                            </span>

                            @if($product->has_discount && !$product->isPublicVideo())
                                <span class="position-absolute top-0 end-0 m-3 badge bg-success text-white px-3 py-2 shadow-sm">
                                    {{ $product->discount_percentage }}% OFF
                                </span>
                            @endif
                        </div>

                        <div class="card-body p-4 bg-light border-top">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <span class="text-muted small d-block">All-Inclusive Price:</span>
                                    @if($product->isPublicVideo() || $product->effective_price <= 0)
                                        <span class="display-6 fw-black text-success">FREE</span>
                                    @else
                                        <span class="display-6 fw-black text-primary">₹{{ number_format($product->effective_price, 2) }}</span>
                                        @if($product->has_discount)
                                            <span class="text-muted text-decoration-line-through ms-2">₹{{ number_format($product->price, 2) }}</span>
                                        @endif
                                    @endif
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                                    <i class="fa-solid fa-check-circle me-1"></i> {{ $product->isPublicVideo() ? 'Free to Watch' : 'In Stock / Instant' }}
                                </span>
                            </div>

                            @if($product->isPublicVideo())
                                <div class="alert alert-success py-2 px-3 small border-0 mb-3 rounded-3">
                                    <i class="fa-brands fa-youtube text-danger me-1"></i> <strong>Free Public Video:</strong> Ye video lecture sabhi ke liye open hai. Niche click karke bina payment/login ke directly dekhein.
                                </div>
                                <a href="#freeVideoPlayer" class="btn btn-success btn-lg w-100 py-3 rounded-pill fw-bold shadow-lg">
                                    <i class="fa-solid fa-circle-play me-2"></i> Watch Free Video Now
                                </a>
                            @elseif($product->type === 'video')
                                <div class="alert alert-info py-2 px-3 small border-0 mb-3 rounded-3">
                                    <i class="fa-brands fa-youtube text-danger me-1"></i> <strong>YouTube &amp; Drive Course:</strong> Access all video modules immediately after purchase through the "See Video" hub.
                                </div>
                                <button type="button" class="btn btn-primary btn-lg w-100 py-3 rounded-pill fw-bold shadow-lg" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                                    <i class="fa-solid fa-cart-shopping me-2"></i> Buy Now — ₹{{ number_format($product->effective_price, 2) }}
                                </button>
                            @else
                                <div class="alert alert-success py-2 px-3 small border-0 mb-3 rounded-3">
                                    <i class="fa-solid fa-cloud-arrow-down me-1"></i> <strong>Instant PDF Download:</strong> High-resolution printable PDF &amp; Google Drive files available immediately after checkout.
                                </div>
                                <button type="button" class="btn btn-primary btn-lg w-100 py-3 rounded-pill fw-bold shadow-lg" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                                    <i class="fa-solid fa-cart-shopping me-2"></i> Buy Now — ₹{{ number_format($product->effective_price, 2) }}
                                </button>
                            @endif

                            @if($product->has_demo)
                                <a href="{{ $product->effective_demo_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-warning btn-lg w-100 py-3 rounded-pill fw-bold text-dark shadow mt-3 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: 1px solid #b45309; color: #ffffff !important; font-size: 1.05rem;">
                                    <i class="fa-solid {{ $product->type === 'video' ? 'fa-circle-play' : 'fa-eye' }} fs-5"></i>
                                    <span>Watch / View Free Demo</span>
                                </a>
                            @endif

                            <div class="mt-3 text-center">
                                <span class="text-muted small"><i class="fa-solid fa-lock text-success me-1"></i> 100% Safe 256-bit Razorpay Checkout</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Product Content & Details -->
            <div class="col-lg-7">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fw-bold text-uppercase mb-2 font-monospace">
                    {{ $product->category ?? 'Study Material' }}
                </span>
                <h1 class="fw-black text-dark mb-3">{{ $product->title }}</h1>

                <!-- Highlights Strip -->
                <div class="row g-3 my-3">
                    @if($product->type === 'pdf')
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="text-muted small d-block"><i class="fa-solid fa-file-invoice text-danger me-1"></i> Total Volume:</span>
                                <strong class="fs-6">{{ $product->total_pages ? $product->total_pages . ' Pages' : 'Complete Study Material' }}</strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="text-muted small d-block"><i class="fa-solid fa-download text-success me-1"></i> Delivery:</span>
                                <strong class="fs-6">Instant Download (PDF)</strong>
                            </div>
                        </div>
                    @else
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="text-muted small d-block"><i class="fa-solid fa-clock text-info me-1"></i> Class Duration:</span>
                                <strong class="fs-6">{{ $product->duration ?? 'Full Course Lecture' }}</strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="text-muted small d-block"><i class="fa-brands fa-youtube text-danger me-1"></i> Platform:</span>
                                <strong class="fs-6">{{ $product->isPublicVideo() ? 'YouTube (Free Public)' : 'YouTube (Private Invited)' }}</strong>
                            </div>
                        </div>
                    @endif
                </div>

                @if($product->intro_video_id)
                    <!-- Course Introduction Video Section -->
                    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-light border-start border-4 border-info">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-bold text-dark mb-0"><i class="fa-brands fa-youtube text-danger me-2"></i> Course Introduction Video</h5>
                            <span class="badge bg-info text-dark font-monospace"><i class="fa-solid fa-play me-1"></i> Free Overview</span>
                        </div>
                        <p class="small text-muted mb-3">Watch this quick course overview to learn about chapters, topics covered, and practical lab projects.</p>
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm bg-black">
                            <iframe src="https://www.youtube.com/embed/{{ $product->intro_video_id }}?rel=0" title="Course Introduction" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        </div>
                    </div>
                @endif

                @if($product->isPublicVideo() && $product->youtube_video_id)
                    <!-- Free Embedded YouTube Player -->
                    <div id="freeVideoPlayer" class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-bold mb-0 text-dark"><i class="fa-brands fa-youtube text-danger me-2"></i> Free Public Video Lecture</h5>
                            <span class="badge bg-success font-monospace px-3 py-1.5"><i class="fa-solid fa-unlock me-1"></i> Open Access</span>
                        </div>
                        <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-lg border border-2 border-primary border-opacity-25 bg-black">
                            <iframe src="https://www.youtube.com/embed/{{ $product->youtube_video_id }}?rel=0&autoplay=0" title="{{ $product->title }}" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        </div>
                        <span class="small text-muted d-block mt-2"><i class="fa-solid fa-circle-info text-info me-1"></i> You can play this lecture directly in full 1080p HD or open full screen.</span>
                    </div>
                @endif

                <!-- Description -->
                <div class="mb-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">About this {{ $product->type === 'video' ? 'Video Class' : 'Study Notes' }}</h5>
                    <div class="lead fs-6 text-muted mb-4">
                        {{ $product->short_description }}
                    </div>

                    <div class="product-description-body text-dark lh-lg">
                        {!! $product->description ? nl2br(e($product->description)) : '<p>Comprehensive study material prepared by experienced computer educators.</p>' !!}
                    </div>
                </div>

                <!-- Video Lectures / Curriculum Breakdown (Requirement 9) -->
                @if(!empty($product->normalized_video_links))
                    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white border">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-list-check text-danger me-2"></i>Included Video Modules &amp; Lectures ({{ 1 + count($product->normalized_video_links) }})</h5>
                            <span class="badge bg-danger-subtle text-danger font-monospace">Full Curriculum</span>
                        </div>
                        <div class="list-group list-group-flush border rounded-3 overflow-hidden">
                            <div class="list-group-item d-flex align-items-center gap-3 p-3 bg-light">
                                <span class="badge bg-danger rounded-circle p-2"><i class="fa-solid fa-play"></i></span>
                                <div>
                                    <strong class="d-block text-dark">1. {{ $product->title }} (Main Lecture)</strong>
                                    <small class="text-muted">{{ $product->duration ?? 'Core Video' }} &bull; YouTube/Drive HD</small>
                                </div>
                            </div>
                            @foreach($product->normalized_video_links as $vIdx => $vItem)
                                <div class="list-group-item d-flex align-items-center gap-3 p-3">
                                    <span class="badge bg-secondary rounded-circle p-2"><i class="fa-solid {{ ($vItem['platform'] ?? 'youtube') === 'drive' ? 'fa-film' : 'fa-play' }}"></i></span>
                                    <div>
                                        <strong class="d-block text-dark">{{ $vIdx + 2 }}. {{ $vItem['title'] }}</strong>
                                        <small class="text-muted text-capitalize">{{ $vItem['platform'] ?? 'Video Module' }} &bull; Available after purchase via "See Video"</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Multiple Drive / PDF Resources (Requirement 6) -->
                @if(!empty($product->normalized_resource_links))
                    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white border">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="fa-brands fa-google-drive text-warning me-2"></i>Included Google Drive &amp; PDF Resources ({{ count($product->normalized_resource_links) }})</h5>
                            <span class="badge bg-warning-subtle text-dark font-monospace">Instant Access</span>
                        </div>
                        <div class="list-group list-group-flush border rounded-3 overflow-hidden">
                            @foreach($product->normalized_resource_links as $rIdx => $rItem)
                                <div class="list-group-item d-flex align-items-center justify-content-between p-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge bg-warning text-dark rounded-circle p-2"><i class="fa-brands fa-google-drive"></i></span>
                                        <div>
                                            <strong class="d-block text-dark">{{ $rItem['title'] }}</strong>
                                            <small class="text-muted">Cloud Resource Link &bull; Included with this product</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-muted border">Protected</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- How it works -->
                @if($product->isPublicVideo())
                    <div class="card border-0 bg-success bg-opacity-10 rounded-4 p-4 my-4 border border-success border-opacity-25">
                        <h5 class="fw-bold text-success mb-2"><i class="fa-solid fa-circle-check me-2"></i> 100% Free Public Lecture</h5>
                        <p class="mb-0 text-dark small lh-lg">This lecture is released under open access. You can watch it directly anytime on this page or through your student dashboard without needing any purchase or authorization code.</p>
                    </div>
                @else
                    <div class="card border-0 bg-light rounded-4 p-4 my-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-circle-question text-primary me-2"></i> How Access Works After Purchase:</h5>
                        @if($product->type === 'video')
                            <ol class="mb-0 ps-3 small text-muted lh-lg">
                                <li><strong>Provide your Gmail ID:</strong> Enter the exact Google/Gmail address you use to log in to YouTube.</li>
                                <li><strong>Instant Payment:</strong> Complete payment securely using UPI, Google Pay, PhonePe, Debit/Credit Card, or Net Banking.</li>
                                <li><strong>Invitation Delivery:</strong> Within 1-2 hours, an official YouTube Private Video invitation will be sent to your Gmail inbox.</li>
                                <li><strong>Watch Safely:</strong> Click the invite link in your Gmail while logged in to your YouTube account to watch the video in full 1080p HD.</li>
                                <li><strong>Access Protection:</strong> Only your invited Gmail ID can view the video. Even if the link is shared, unauthorized accounts will be blocked by YouTube.</li>
                            </ol>
                        @else
                            <ol class="mb-0 ps-3 small text-muted lh-lg">
                                <li><strong>Instant Payment:</strong> Complete checkout via Razorpay with UPI, QR, or Cards.</li>
                                <li><strong>Immediate Download:</strong> A direct download button appears on your confirmation receipt.</li>
                                <li><strong>Re-download Anytime:</strong> You can re-download your PDF at any time using our <a href="{{ route('store.purchases') }}">My Purchases</a> lookup tool using your email or phone number.</li>
                            </ol>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
@if($relatedProducts->isNotEmpty())
<section class="py-5 bg-light border-top">
    <div class="container">
        <h4 class="fw-bold mb-4">You May Also Like</h4>
        <div class="row g-4">
            @foreach($relatedProducts as $rel)
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-3 d-flex flex-column">
                            <span class="badge {{ $rel->type === 'video' ? 'bg-danger' : 'bg-primary' }} mb-2 w-auto align-self-start">{{ strtoupper($rel->type) }}</span>
                            <h6 class="fw-bold"><a href="{{ route('store.show', ['type' => $rel->type, 'slug' => $rel->slug]) }}" class="text-dark text-decoration-none">{{ $rel->title }}</a></h6>
                            <div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                                <span class="fw-bold text-primary">₹{{ number_format($rel->effective_price, 2) }}</span>
                                <a href="{{ route('store.show', ['type' => $rel->type, 'slug' => $rel->slug]) }}" class="btn btn-sm btn-outline-primary rounded-pill">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Razorpay Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="checkoutModalLabel">
                    <i class="fa-solid fa-shield-halved me-2"></i> Secure Checkout
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Order Summary -->
                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="d-block text-dark">{{ $product->title }}</strong>
                            <span class="badge {{ $product->type === 'video' ? 'bg-danger' : 'bg-primary' }} text-uppercase">{{ $product->type }}</span>
                        </div>
                        <div class="text-end">
                            <span class="fs-4 fw-black text-primary">₹{{ number_format($product->effective_price, 2) }}</span>
                        </div>
                    </div>
                </div>

                <form id="checkoutForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" id="custName" class="form-control" required placeholder="Enter your full name" value="{{ auth()->user()?->name ?? '' }}">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="customer_email" id="custEmail" class="form-control" required placeholder="name@example.com" value="{{ auth()->user()?->email ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Mobile Number (10 Digits) <span class="text-danger">*</span></label>
                            <input type="tel" name="customer_mobile" id="custMobile" maxlength="10" pattern="[0-9]{10}" class="form-control" required placeholder="e.g. 9876543210">
                        </div>
                    </div>

                    @if($product->type === 'video')
                        <!-- Gmail Address Required for YouTube Private Invitation -->
                        <div class="mb-3 p-3 bg-warning bg-opacity-10 border border-warning rounded-3">
                            <label class="form-label small fw-bold text-dark">
                                <i class="fa-brands fa-youtube text-danger me-1"></i> Your YouTube Gmail ID <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="customer_gmail" id="custGmail" class="form-control" required placeholder="yourname@gmail.com">
                            <div class="form-text small text-muted">
                                <strong>Important:</strong> Enter the exact Gmail address you use to sign in to YouTube. Your private invitation will be sent to this email only.
                            </div>
                        </div>
                    @endif

                    <div id="checkoutAlert" class="alert alert-danger d-none py-2 px-3 small"></div>

                    <button type="submit" id="payBtn" class="btn btn-primary btn-lg w-100 py-3 fw-bold rounded-pill shadow">
                        <span id="payBtnText"><i class="fa-solid fa-lock me-2"></i> Pay ₹{{ number_format($product->effective_price, 2) }}</span>
                        <span id="paySpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </form>

                <div class="mt-3 text-center">
                    <img src="https://razorpay.com/assets/razorpay-glyph.svg" alt="Razorpay" height="20" class="me-2 opacity-75">
                    <span class="text-muted small">Powered by Razorpay Secure Payments</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Razorpay Checkout JS -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    const payBtn = document.getElementById('payBtn');
    const payBtnText = document.getElementById('payBtnText');
    const paySpinner = document.getElementById('paySpinner');
    const alertBox = document.getElementById('checkoutAlert');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        alertBox.classList.add('d-none');
        payBtn.disabled = true;
        payBtnText.classList.add('d-none');
        paySpinner.classList.remove('d-none');

        const formData = new FormData(form);

        fetch("{{ route('store.checkout.initiate') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Unable to initiate checkout order.');
            }

            // If Sandbox or Key is test mock
            if (data.is_sandbox && (!data.key_id || data.key_id.includes('sandbox'))) {
                // Auto verify in sandbox mode for testing
                handlePaymentSuccess({
                    order_id: data.order_id,
                    razorpay_order_id: data.razorpay_order_id,
                    razorpay_payment_id: 'pay_test_' + Date.now(),
                    razorpay_signature: 'sandbox_test_signature'
                });
                return;
            }

            // Launch Real Razorpay Modal
            const options = {
                key: data.key_id,
                amount: data.amount_paise,
                currency: data.currency,
                name: "{{ $instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER' }}",
                description: data.product_title,
                image: "{{ asset('images/tech-era-logo.png') }}",
                order_id: data.razorpay_order_id,
                handler: function (response) {
                    handlePaymentSuccess({
                        order_id: data.order_id,
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature: response.razorpay_signature
                    });
                },
                prefill: {
                    name: data.customer_name,
                    email: data.customer_email,
                    contact: data.customer_mobile
                },
                theme: {
                    color: "#0d2238"
                },
                modal: {
                    ondismiss: function() {
                        resetPayBtn();
                    }
                }
            };

            const rzp = new Razorpay(options);
            rzp.on('payment.failed', function (response){
                alertBox.textContent = response.error.description || 'Payment was unsuccessful or cancelled.';
                alertBox.classList.remove('d-none');
                resetPayBtn();
            });
            rzp.open();
        })
        .catch(err => {
            alertBox.textContent = err.message || 'An error occurred during checkout.';
            alertBox.classList.remove('d-none');
            resetPayBtn();
        });
    });

    function handlePaymentSuccess(payload) {
        fetch("{{ route('store.checkout.verify') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(result => {
            if (result.success && result.redirect_url) {
                window.location.href = result.redirect_url;
            } else {
                throw new Error(result.message || 'Verification could not be confirmed.');
            }
        })
        .catch(err => {
            alertBox.textContent = err.message;
            alertBox.classList.remove('d-none');
            resetPayBtn();
        });
    }

    function resetPayBtn() {
        payBtn.disabled = false;
        payBtnText.classList.remove('d-none');
        paySpinner.classList.add('d-none');
    }
});
</script>
@endpush
