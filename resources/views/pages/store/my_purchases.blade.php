@extends('layouts.app')

@section('title', 'My Purchases & PDF Downloads | ' . ($instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER'))

@section('content')
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Search Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-primary text-white py-4 px-4 text-center">
                        <i class="fa-solid fa-box-archive fa-3x mb-2 text-warning"></i>
                        <h3 class="fw-bold mb-1">Find Your Purchased Products</h3>
                        <p class="mb-0 opacity-85 small">Enter your Email or Mobile number used during checkout to retrieve your purchased PDFs and video access.</p>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <form action="{{ route('store.purchases') }}" method="GET">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-envelope text-muted"></i></span>
                                <input type="text" name="email" value="{{ $query }}" class="form-control" required placeholder="Enter Email, YouTube Gmail, or Mobile number">
                                <button type="submit" class="btn btn-primary px-4 fw-bold">
                                    <i class="fa-solid fa-magnifying-glass me-1"></i> Retrieve
                                </button>
                            </div>
                        </form>

                        @if(session('not_found'))
                            <div class="alert alert-warning mt-3 mb-0 small">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ session('not_found') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Orders List -->
                @if($orders->isNotEmpty())
                    <h5 class="fw-bold text-dark mb-3">Found {{ $orders->count() }} Purchase Record(s)</h5>
                    @foreach($orders as $ord)
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                            <div class="card-header bg-light py-3 px-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-success me-2">PAID</span>
                                    <strong class="font-monospace">#{{ $ord->order_number }}</strong>
                                </div>
                                <span class="text-muted small">{{ $ord->paid_at ? $ord->paid_at->format('d M Y, h:i A') : $ord->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="card-body p-4 bg-white">
                                @foreach($ord->accesses as $acc)
                                    @php $prod = $acc->product; @endphp
                                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center pb-3 mb-3 border-bottom gap-2">
                                        <div>
                                            <span class="badge {{ $prod->type === 'video' ? 'bg-danger' : 'bg-primary' }} text-uppercase mb-1">
                                                {{ $prod->type }}
                                            </span>
                                            <h6 class="fw-bold mb-1 text-dark">{{ $prod->title }}</h6>
                                            <span class="small text-muted">Amount: ₹{{ number_format($ord->total_amount, 2) }}</span>
                                        </div>
                                        <div>
                                            @if($prod->type === 'pdf')
                                                <a href="{{ route('store.download.pdf', $acc->access_token) }}" class="btn btn-sm btn-success rounded-pill px-3 fw-bold">
                                                    <i class="fa-solid fa-download me-1"></i> Download PDF
                                                </a>
                                            @else
                                                @if($acc->youtube_invite_status === 'invited')
                                                    <span class="badge bg-success p-2"><i class="fa-solid fa-check me-1"></i> YouTube Access Active</span>
                                                @else
                                                    <span class="badge bg-warning text-dark p-2"><i class="fa-solid fa-clock me-1"></i> Invite Queued ({{ $acc->customer_gmail }})</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
