@extends('layouts.admin')

@section('title', 'Order #' . $order->order_number . ' Details - Admin Panel')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-file-invoice-dollar text-primary me-2"></i>Order #{{ $order->order_number }}</h3>
        <p class="text-muted small mb-0">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.store.orders.index') }}" class="btn btn-outline-secondary rounded-3 px-3 shadow-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Orders
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Order Items & Access -->
    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark">Purchased Digital Items</h5>
            </div>
            <div class="card-body p-4">
                @foreach($order->accesses as $access)
                    @php $product = $access->product; @endphp
                    <div class="p-3 bg-light rounded-3 mb-3 border d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <span class="badge {{ $product->type === 'video' ? 'bg-danger' : 'bg-primary' }} text-uppercase mb-1">
                                {{ $product->type }}
                            </span>
                            <h5 class="fw-bold mb-1 text-dark">{{ $product->title }}</h5>
                            <span class="small text-muted font-monospace">Access Token: {{ substr($access->access_token, 0, 16) }}...</span>
                        </div>
                        <div class="text-md-end">
                            @if($product->type === 'pdf')
                                <span class="badge bg-success mb-1">PDF Access Active</span>
                                <span class="d-block small text-muted">Downloaded {{ $access->pdf_download_count }} times</span>
                            @else
                                <span class="badge {{ $access->youtube_invite_status === 'invited' ? 'bg-success' : 'bg-warning text-dark' }} mb-1">
                                    {{ strtoupper(str_replace('_', ' ', $access->youtube_invite_status)) }}
                                </span>
                                <span class="d-block small text-danger font-monospace">Gmail: {{ $access->customer_gmail }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Column: Customer & Payment Particulars -->
    <div class="col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h6 class="fw-bold mb-0 text-dark">Customer Details</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <span class="text-muted small d-block">Full Name:</span>
                    <strong class="text-dark">{{ $order->customer_name }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Email Address:</span>
                    <strong class="text-dark">{{ $order->customer_email }}</strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Mobile:</span>
                    <strong class="font-monospace text-dark">{{ $order->customer_mobile }}</strong>
                </div>
                @if($order->customer_gmail)
                    <div class="mb-3">
                        <span class="text-muted small d-block">YouTube Gmail ID:</span>
                        <strong class="font-monospace text-danger">{{ $order->customer_gmail }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h6 class="fw-bold mb-0 text-dark">Payment Information</h6>
            </div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Amount:</span>
                    <strong class="fs-5 text-primary">₹{{ number_format($order->total_amount, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Payment Status:</span>
                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">{{ strtoupper($order->payment_status) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Gateway:</span>
                    <span class="text-uppercase fw-semibold">{{ $order->payment_gateway }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Razorpay Order ID:</span>
                    <span class="font-monospace small">{{ $order->razorpay_order_id ?? 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Razorpay Payment ID:</span>
                    <span class="font-monospace small">{{ $order->razorpay_payment_id ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
