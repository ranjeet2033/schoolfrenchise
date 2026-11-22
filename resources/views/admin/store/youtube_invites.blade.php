@extends('layouts.admin')

@section('title', 'YouTube Private Video Invitations Hub - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="fa-brands fa-youtube text-danger me-2"></i>YouTube Private Video Invitations Hub</h3>
        <p class="text-muted small mb-0">Manage customer Gmail invitations for private YouTube lecture courses (Max 50 emails per video).</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success rounded-3 px-3 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#grantAccessModal">
            <i class="fa-solid fa-user-check me-1"></i> Give Access to Student / Gmail
        </button>
        <a href="{{ route('admin.store.orders.index') }}" class="btn btn-outline-secondary rounded-3 px-3 shadow-sm fw-semibold">
            <i class="fa-solid fa-receipt me-1"></i> Store Orders
        </a>
        <a href="{{ route('admin.store.products.index') }}" class="btn btn-outline-primary rounded-3 px-3 shadow-sm fw-semibold">
            <i class="fa-solid fa-boxes-stacked me-1"></i> Products
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- YouTube 50-Invite Workflow Instruction Banner -->
<div class="card border-0 rounded-4 shadow-sm mb-4 bg-danger text-white">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <span class="badge bg-white text-danger font-monospace px-2 py-1 fw-bold mb-2">QUICK WORKFLOW (YOUTUBE STUDIO)</span>
                <h5 class="fw-bold mb-2">How to Invite Paid Customers on YouTube:</h5>
                <ol class="mb-0 ps-3 small opacity-90 lh-lg">
                    <li>Click the <strong>"Copy Pending Gmails"</strong> button below to copy all customer Gmail IDs to clipboard.</li>
                    <li>Open <strong>YouTube Studio</strong> &rarr; Videos &rarr; Select your Private Course Video.</li>
                    <li>Click <strong>Visibility: Private &rarr; Share Privately</strong> &rarr; Paste the copied emails &rarr; Check "Notify via email" &rarr; Save.</li>
                    <li>Come back here and click <strong>"Batch Mark as Invited"</strong> to update customer records.</li>
                </ol>
            </div>
            <div class="col-lg-4 text-lg-end">
                @if(!empty($pendingGmails))
                    <button type="button" class="btn btn-light text-danger fw-bold rounded-pill px-4 py-2 shadow" onclick="copyPendingGmails()">
                        <i class="fa-solid fa-copy me-1"></i> Copy Pending Gmails (Max 50)
                    </button>
                    <textarea id="hiddenGmailsText" class="visually-hidden">{{ $pendingGmails }}</textarea>
                @else
                    <span class="badge bg-white text-success px-3 py-2 fs-6">
                        <i class="fa-solid fa-check-circle me-1"></i> All Invites Sent!
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Filter Strip -->
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.store.youtube.invites') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <select name="video_id" class="form-select">
                    <option value="">All Video Courses</option>
                    @foreach($videoProducts as $v)
                        <option value="{{ $v->id }}" {{ $videoId == $v->id ? 'selected' : '' }}>
                            {{ $v->title }} ({{ $v->current_invites }}/{{ $v->max_invites }} Invites)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="pending_invite" {{ $status === 'pending_invite' ? 'selected' : '' }}>Pending Invites ({{ $pendingCount }})</option>
                    <option value="invited" {{ $status === 'invited' ? 'selected' : '' }}>Invited ({{ $invitedCount }})</option>
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Records</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-bold">Filter</button>
                <a href="{{ route('admin.store.youtube.invites') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Invites Table -->
<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
        <h6 class="fw-bold mb-0 text-dark">Customer Invitations Queue ({{ $accesses->total() }})</h6>
        @if($status === 'pending_invite' && $accesses->isNotEmpty())
            <form action="{{ route('admin.store.youtube.batch-mark') }}" method="POST" onsubmit="return confirm('Mark all displayed pending invitations as invited on YouTube?');">
                @csrf
                @if(!empty($videoId))
                    <input type="hidden" name="digital_product_id" value="{{ $videoId }}">
                @endif
                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 fw-bold shadow-sm">
                    <i class="fa-solid fa-check-double me-1"></i> Batch Mark Displayed as Invited
                </button>
            </form>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Customer Name</th>
                        <th>YouTube Gmail ID</th>
                        <th>Course Video</th>
                        <th>Order #</th>
                        <th>Status</th>
                        <th>Invited Date</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accesses as $acc)
                        <tr>
                            <td class="ps-4">
                                <strong class="text-dark d-block">{{ $acc->order->customer_name ?? 'Customer' }}</strong>
                                <span class="small text-muted">{{ $acc->order->customer_mobile ?? '' }}</span>
                            </td>
                            <td>
                                <span class="font-monospace fw-bold text-danger">{{ $acc->customer_gmail }}</span>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ Str::limit($acc->product->title ?? 'Video', 35) }}</span>
                                <span class="small text-muted d-block">Quota: {{ $acc->product?->current_invites ?? 0 }}/{{ $acc->product?->max_invites ?? 50 }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.store.orders.show', $acc->digital_order_id) }}" class="font-monospace small text-primary text-decoration-none">
                                    {{ $acc->order->order_number ?? '#' . $acc->digital_order_id }}
                                </a>
                            </td>
                            <td>
                                @if($acc->youtube_invite_status === 'pending_invite')
                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i> Pending Invite</span>
                                @elseif($acc->youtube_invite_status === 'invited')
                                    <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i> Invited</span>
                                @else
                                    <span class="badge bg-secondary">{{ strtoupper($acc->youtube_invite_status) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="small text-muted">{{ $acc->youtube_invited_at ? $acc->youtube_invited_at->format('d M Y, h:i A') : 'Not yet' }}</span>
                            </td>
                            <td class="pe-4 text-end">
                                @if($acc->youtube_invite_status === 'pending_invite')
                                    <form action="{{ route('admin.store.youtube.mark-invited', $acc->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 fw-bold" title="Mark as Invited">
                                            <i class="fa-solid fa-check me-1"></i> Mark Invited
                                        </button>
                                    </form>
                                @endif

                                @if($acc->youtube_invite_status !== 'revoked')
                                    <form action="{{ route('admin.store.youtube.revoke', $acc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Revoke YouTube video access for this customer?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle ms-1" title="Revoke Access">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                No video access records found for the selected filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($accesses->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $accesses->links() }}
        </div>
    @endif
</div>

<!-- Modal: Give Manual Access to Student / Gmail -->
<div class="modal fade" id="grantAccessModal" tabindex="-1" aria-labelledby="grantAccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-danger text-white py-3 px-4">
                <h5 class="modal-title fw-bold" id="grantAccessModalLabel">
                    <i class="fa-brands fa-youtube me-2"></i> Give Access to Student (Private Video)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('store.youtube.manual-grant') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div class="alert alert-info py-2 px-3 small rounded-3 mb-3">
                        <i class="fa-solid fa-circle-info me-1"></i> Admin yahan kisi bhi student ko private YouTube video lecture ka access bina payment ke directly de sakta hai.
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Select Video Course <span class="text-danger">*</span></label>
                        <select name="digital_product_id" class="form-select rounded-3" required>
                            <option value="">-- Choose Video Lecture --</option>
                            @foreach($videoProducts as $v)
                                <option value="{{ $v->id }}">
                                    {{ $v->title }} ({{ $v->current_invites }}/{{ $v->max_invites }} Invites)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Student / Customer Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control rounded-3" required placeholder="e.g. Raja Ram">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">YouTube Gmail ID <span class="text-danger">*</span></label>
                            <input type="email" name="customer_gmail" class="form-control rounded-3" required placeholder="student@gmail.com">
                            <span class="form-text small text-muted" style="font-size: 0.72rem;">YouTube invite is Gmail par send hoga.</span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Primary Email</label>
                            <input type="email" name="customer_email" class="form-control rounded-3" required placeholder="same or alternate email">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Mobile Number</label>
                            <input type="text" name="customer_mobile" class="form-control rounded-3" placeholder="10-digit mobile">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Invitation Status <span class="text-danger">*</span></label>
                            <select name="invite_status" class="form-select rounded-3" required>
                                <option value="invited">Mark as Invited Now</option>
                                <option value="pending_invite">Pending Invite (Queue)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Admin Remarks / Notes <small class="text-muted">(Optional)</small></label>
                        <input type="text" name="admin_notes" class="form-control rounded-3" placeholder="e.g. Free access granted by Admin for ADCA Batch">
                    </div>
                </div>
                <div class="modal-footer bg-light py-2.5 px-4 border-top">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fa-solid fa-key me-1"></i> Grant Access Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyPendingGmails() {
    const textEl = document.getElementById('hiddenGmailsText');
    if (!textEl) return;
    navigator.clipboard.writeText(textEl.value).then(() => {
        alert("Copied " + textEl.value.split(',').length + " Gmail address(es) to clipboard! You can now paste directly into YouTube Studio's Private Share box.");
    }).catch(err => {
        alert("Could not auto-copy, please copy manually: " + textEl.value);
    });
}
</script>
@endpush
