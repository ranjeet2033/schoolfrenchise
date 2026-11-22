@extends('layouts.admin')

@section('title', 'Digital Store Products - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-store text-primary me-2"></i>Digital Products (PDFs &amp; Videos)</h3>
        <p class="text-muted small mb-0">Manage study material PDFs, question banks, and private YouTube video training.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.store.orders.index') }}" class="btn btn-outline-secondary rounded-3 px-3 shadow-sm fw-semibold">
            <i class="fa-solid fa-receipt me-1"></i> Orders &amp; Revenue
        </a>
        <a href="{{ route('admin.store.youtube.invites') }}" class="btn btn-outline-danger rounded-3 px-3 shadow-sm fw-semibold">
            <i class="fa-brands fa-youtube me-1"></i> YouTube Invites Hub
        </a>
        <a href="{{ route('admin.store.products.create') }}" class="btn btn-primary rounded-3 px-3 shadow-sm fw-semibold">
            <i class="fa-solid fa-plus me-1"></i> Add New Product
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Quick Metrics -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 rounded-4 shadow-sm p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3">
                    <i class="fa-solid fa-box-archive fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small">Total Products</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $products->total() }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 rounded-4 shadow-sm p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-3">
                    <i class="fa-solid fa-file-pdf fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small">PDF E-Books &amp; Notes</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $totalPdfs }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 rounded-4 shadow-sm p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-info bg-opacity-10 text-info rounded-3">
                    <i class="fa-brands fa-youtube fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted small">Private Video Courses</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $totalVideos }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Product</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Sales / Access</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 44px; height: 44px; background: #0d2238; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                        @if($p->thumbnail)
                                            <img src="{{ $p->thumbnail_url }}" alt="" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <i class="fa-solid {{ $p->type === 'video' ? 'fa-video text-danger' : 'fa-file-pdf text-warning' }}"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.store.products.edit', $p->id) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $p->title }}
                                        </a>
                                        <span class="small text-muted d-block font-monospace">/store/{{ $p->type }}/{{ $p->slug }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $p->type === 'video' ? 'bg-danger' : 'bg-primary' }} text-uppercase">
                                    {{ $p->type === 'video' ? 'Video' : 'PDF' }}
                                </span>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $p->category ?? 'General' }}</span></td>
                            <td>
                                <strong class="text-primary">₹{{ number_format($p->effective_price, 2) }}</strong>
                                @if($p->has_discount)
                                    <span class="text-muted text-decoration-line-through small ms-1">₹{{ number_format($p->price, 2) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">
                                    {{ $p->accesses_count }} orders
                                </span>
                                @if($p->type === 'video')
                                    <span class="small text-muted d-block mt-1">Invites: {{ $p->current_invites }}/{{ $p->max_invites }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $p->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('store.show', ['type' => $p->type, 'slug' => $p->slug]) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-circle me-1" title="View Public Page">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                                <a href="{{ route('admin.store.products.edit', $p->id) }}" class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.store.products.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                No digital products found. Click "Add New Product" to upload PDFs or set up Private YouTube videos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($products->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
