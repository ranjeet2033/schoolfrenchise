@extends('layouts.app')

@section('title', 'Order Confirmation #' . $order->order_number . ' | ' . ($instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER'))

@section('content')
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Header Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-body p-4 p-md-5 text-center bg-white">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success text-white mb-3 shadow" style="width: 72px; height: 72px;">
                            <i class="fa-solid fa-check fa-3x"></i>
                        </div>
                        <h2 class="fw-black text-dark mb-2">Payment Successful!</h2>
                        <p class="text-muted lead fs-6 mb-3">Thank you, <strong>{{ $order->customer_name }}</strong>. Your digital order has been confirmed.</p>
                        <div class="d-inline-block px-3 py-1 bg-light border rounded-pill font-monospace text-primary fw-bold small">
                            Order Reference: #{{ $order->order_number }}
                        </div>
                    </div>
                </div>

                <!-- Purchased Items & Access Section -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-primary text-white py-3 px-4">
                        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-cloud-arrow-down me-2"></i> Your Digital Items & Access</h5>
                    </div>
                    <div class="card-body p-4 bg-white">
                        @foreach($order->accesses as $access)
                            @php
                                $prod = $access->product;
                            @endphp
                            <div class="p-3 mb-3 border rounded-3 bg-light d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                <div>
                                    <span class="badge {{ $prod->type === 'video' ? 'bg-danger' : 'bg-primary' }} text-uppercase mb-1">
                                        {{ $prod->type === 'video' ? 'YouTube Private Video' : 'PDF E-Book' }}
                                    </span>
                                    <h5 class="fw-bold mb-1 text-dark">{{ $prod->title }}</h5>
                                    <div class="small text-muted">
                                        Delivered to: <strong>{{ $order->customer_email }}</strong>
                                        @if($prod->type === 'video' && $access->customer_gmail)
                                            | YouTube Gmail: <strong class="text-danger">{{ $access->customer_gmail }}</strong>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-md-end flex-shrink-0">
                                    @if($prod->type === 'pdf')
                                        <!-- Instant Download Button -->
                                        <a href="{{ route('store.download.pdf', $access->access_token) }}" class="btn btn-success btn-lg px-4 rounded-pill fw-bold shadow-sm">
                                            <i class="fa-solid fa-download me-2"></i> Download PDF
                                        </a>
                                        <span class="d-block small text-muted mt-1">Downloads: {{ $access->pdf_download_count }} times</span>
                                    @else
                                        <!-- YouTube Invitation Status -->
                                        @if($access->youtube_invite_status === 'invited')
                                            <div class="badge bg-success px-3 py-2 fs-6">
                                                <i class="fa-solid fa-envelope-circle-check me-1"></i> Invitation Sent!
                                            </div>
                                            <span class="d-block small text-muted mt-1">Check your inbox at <strong>{{ $access->customer_gmail }}</strong></span>
                                        @else
                                            <div class="badge bg-warning text-dark px-3 py-2 fs-6">
                                                <i class="fa-solid fa-hourglass-half me-1"></i> Invitation Pending
                                            </div>
                                            <span class="d-block small text-muted mt-1">Will be delivered to <strong>{{ $access->customer_gmail }}</strong></span>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            @if($prod->type === 'video')
                                <div class="alert alert-info py-3 px-4 border-0 rounded-3 small mb-3">
                                    <h6 class="fw-bold text-dark mb-1"><i class="fa-brands fa-youtube text-danger me-1"></i> YouTube Invitation Instructions:</h6>
                                    <p class="mb-0">
                                        Your Gmail address (<strong>{{ $access->customer_gmail }}</strong>) has been queued for YouTube Private Video access. You will receive an official Google invitation email. Simply click <strong>"Accept Invitation"</strong> and view the video on YouTube with high speed and zero ads.
                                    </p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Order Receipt Particulars -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-light py-3 px-4 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark">Payment Details</h6>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <div class="row g-3 small">
                            <div class="col-sm-6">
                                <span class="text-muted d-block">Razorpay Payment ID:</span>
                                <strong class="font-monospace text-dark">{{ $order->razorpay_payment_id ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted d-block">Date & Time:</span>
                                <strong class="text-dark">{{ $order->paid_at ? $order->paid_at->format('d M Y, h:i A') : now()->format('d M Y, h:i A') }}</strong>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted d-block">Mobile:</span>
                                <strong class="text-dark">{{ $order->customer_mobile }}</strong>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted d-block">Total Paid:</span>
                                <strong class="text-primary fs-5">₹{{ number_format($order->total_amount, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light py-3 px-4 d-flex justify-content-between align-items-center">
                        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                            <i class="fa-solid fa-print me-1"></i> Print Receipt
                        </button>
                        <a href="{{ route('store.index') }}" class="btn btn-primary btn-sm px-4 rounded-pill">
                            <i class="fa-solid fa-arrow-left me-1"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
