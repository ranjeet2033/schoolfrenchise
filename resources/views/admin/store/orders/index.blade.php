@extends('layouts.admin')

@section('title', 'Digital Store Orders & Revenue - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-receipt text-primary me-2"></i>Digital Store Orders &amp; Revenue</h3>
        <p class="text-muted small mb-0">Track online customer payments, Razorpay transaction IDs, and product deliveries.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.store.youtube.invites') }}" class="btn btn-outline-danger rounded-3 px-3 shadow-sm fw-semibold">
            <i class="fa-brands fa-youtube me-1"></i> YouTube Invites Queue ({{ $pendingInvites }})
        </a>
        <a href="{{ route('admin.store.products.index') }}" class="btn btn-outline-primary rounded-3 px-3 shadow-sm fw-semibold">
            <i class="fa-solid fa-boxes-stacked me-1"></i> Manage Products
        </a>
    </div>
</div>

<!-- Revenue Metrics -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 rounded-4 shadow-sm p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-3">
                    <i class="fa-solid fa-indian-rupee-sign fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small">Total Store Revenue</span>
                    <h4 class="fw-bold mb-0 text-success">₹{{ number_format($totalRevenue, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 rounded-4 shadow-sm p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3">
                    <i class="fa-solid fa-circle-check fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small">Paid Digital Orders</span>
                    <h4 class="fw-bold mb-0 text-primary">{{ $totalPaidOrders }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 rounded-4 shadow-sm p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3">
                    <i class="fa-solid fa-hourglass-half fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small">Pending YouTube Invites</span>
                    <h4 class="fw-bold mb-0 text-warning">{{ $pendingInvites }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Filter -->
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.store.orders.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Search by order #, name, email, mobile, Razorpay ID...">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Payment Statuses</option>
                    <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-bold">Filter</button>
                <a href="{{ route('admin.store.orders.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Order #</th>
                        <th>Customer</th>
                        <th>Product Items</th>
                        <th>Amount</th>
                        <th>Razorpay ID</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $ord)
                        <tr>
                            <td class="ps-4">
                                <a href="{{ route('admin.store.orders.show', $ord->id) }}" class="fw-bold font-monospace text-primary text-decoration-none">
                                    {{ $ord->order_number }}
                                </a>
                            </td>
                            <td>
                                <strong class="d-block text-dark">{{ $ord->customer_name }}</strong>
                                <span class="small text-muted font-monospace">{{ $ord->customer_mobile }} | {{ $ord->customer_email }}</span>
                                @if($ord->customer_gmail)
                                    <span class="small text-danger d-block font-monospace">YT: {{ $ord->customer_gmail }}</span>
                                @endif
                            </td>
                            <td>
                                @foreach($ord->items as $item)
                                    <div>
                                        <span class="badge {{ $item->product_type === 'video' ? 'bg-danger' : 'bg-primary' }} text-uppercase" style="font-size: 0.68rem;">{{ $item->product_type }}</span>
                                        <span class="small text-dark">{{ Str::limit($item->product_title, 30) }}</span>
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                <strong class="text-dark">₹{{ number_format($ord->total_amount, 2) }}</strong>
                            </td>
                            <td>
                                <span class="font-monospace small text-muted">{{ $ord->razorpay_payment_id ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $ord->payment_status === 'paid' ? 'bg-success' : ($ord->payment_status === 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ strtoupper($ord->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="small text-muted">{{ $ord->created_at->format('d M Y, h:i A') }}</span>
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('admin.store.orders.show', $ord->id) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="View Order Details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
