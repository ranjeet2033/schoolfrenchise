@extends('layouts.centre')

@section('title', 'Landing Page Enquiries & Leads - ' . ($instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER'))

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">
            <i class="fa-solid fa-bullhorn text-warning me-2"></i>Landing Page Enquiries & Leads
        </h3>
        <p class="text-muted small mb-0">Prospective student enquiries and admission applications received from your dedicated landing page (<code>{{ $centre->centre_code }}</code>).</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ $centre->landing_page_url }}" target="_blank" class="btn btn-outline-primary rounded-2 px-3 fw-semibold shadow-sm">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>View Public Landing Page
        </a>
        <a href="{{ route('centre.landing.edit') }}" class="btn btn-primary rounded-2 px-3 fw-semibold shadow-sm">
            <i class="fa-solid fa-palette me-1"></i>Customize Page
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Stats Strip -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="stat-card p-3 bg-white border-start border-4 border-dark">
            <div class="text-muted small fw-semibold text-uppercase">Total Leads</div>
            <div class="fs-4 fw-bold text-dark my-1">{{ number_format($stats['total']) }}</div>
            <span class="small text-muted">All enquiries received</span>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="stat-card p-3 bg-white border-start border-4 border-danger">
            <div class="text-muted small fw-semibold text-uppercase">New / Uncontacted</div>
            <div class="fs-4 fw-bold text-danger my-1">{{ number_format($stats['new']) }}</div>
            <span class="small text-muted">Pending counselor response</span>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="stat-card p-3 bg-white border-start border-4 border-warning">
            <div class="text-muted small fw-semibold text-uppercase">Contacted</div>
            <div class="fs-4 fw-bold text-warning my-1">{{ number_format($stats['contacted']) }}</div>
            <span class="small text-muted">Follow-up in progress</span>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="stat-card p-3 bg-white border-start border-4 border-success">
            <div class="text-muted small fw-semibold text-uppercase">Converted</div>
            <div class="fs-4 fw-bold text-success my-1">{{ number_format($stats['converted']) }}</div>
            <span class="small text-muted">Admitted as students</span>
        </div>
    </div>
</div>

<!-- Filters Bar -->
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('centre.enquiries.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Search by student name, mobile, email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="course_id" class="form-select bg-light">
                    <option value="">All Applied Courses</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>{{ $c->course_code }} - {{ $c->course_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select bg-light">
                    <option value="">All Statuses</option>
                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New Leads</option>
                    <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>Converted</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold"><i class="fa-solid fa-filter me-1"></i>Filter</button>
                @if(request()->anyFilled(['search', 'course_id', 'status']))
                    <a href="{{ route('centre.enquiries.index') }}" class="btn btn-outline-secondary" title="Reset Filters"><i class="fa-solid fa-rotate-left"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Enquiries Table -->
<div class="card border-0 rounded-4 shadow-sm overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 140px;">Date & Time</th>
                        <th>Student Details</th>
                        <th>Interested Course</th>
                        <th>Query / Message</th>
                        <th class="text-center" style="width: 140px;">Status</th>
                        <th class="text-end pe-4" style="width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enquiries as $enquiry)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-semibold text-dark d-block">{{ $enquiry->created_at->format('d M, Y') }}</span>
                                <span class="small text-muted font-monospace">{{ $enquiry->created_at->format('h:i A') }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $enquiry->name }}</div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <a href="tel:{{ $enquiry->mobile }}" class="badge bg-light text-primary border text-decoration-none font-monospace">
                                        <i class="fa-solid fa-phone me-1"></i>{{ $enquiry->mobile }}
                                    </a>
                                    <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $enquiry->mobile) }}?text=Hello%20{{ urlencode($enquiry->name) }},%20greeting%20from%20{{ urlencode($centre->centre_name) }}%20regarding%20your%20course%20enquiry." target="_blank" class="badge bg-success text-white text-decoration-none" title="Chat on WhatsApp">
                                        <i class="fa-brands fa-whatsapp me-1"></i>WhatsApp
                                    </a>
                                </div>
                                @if(!empty($enquiry->email))
                                    <div class="small text-muted mt-1"><i class="fa-solid fa-envelope me-1"></i>{{ $enquiry->email }}</div>
                                @endif
                            </td>
                            <td>
                                @if($enquiry->course)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace px-2 py-1 mb-1 d-inline-block">
                                        {{ $enquiry->course->course_code }}
                                    </span>
                                    <div class="fw-semibold text-dark small">{{ $enquiry->course->course_name }}</div>
                                    <span class="text-muted small"><i class="fa-solid fa-clock me-1"></i>{{ $enquiry->course->duration }}</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">General Admission</span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-muted text-break" style="max-width: 280px;">
                                    {{ $enquiry->message ?? 'No specific message provided.' }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($enquiry->status === 'new')
                                    <span class="badge bg-danger px-3 py-1.5"><i class="fa-solid fa-star me-1"></i>New Lead</span>
                                @elseif($enquiry->status === 'contacted')
                                    <span class="badge bg-warning text-dark px-3 py-1.5"><i class="fa-solid fa-phone me-1"></i>Contacted</span>
                                @elseif($enquiry->status === 'converted')
                                    <span class="badge bg-success px-3 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Converted</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-1.5">Closed</span>
                                @endif

                                <!-- Status Dropdown Trigger -->
                                <div class="dropdown mt-1">
                                    <button class="btn btn-sm btn-link text-muted p-0 dropdown-toggle small text-decoration-none" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.72rem;">
                                        Change Status
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 small">
                                        <li>
                                            <form action="{{ route('centre.enquiries.status', $enquiry->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="new">
                                                <button type="submit" class="dropdown-item {{ $enquiry->status === 'new' ? 'active' : '' }}">Mark as New</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('centre.enquiries.status', $enquiry->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="contacted">
                                                <button type="submit" class="dropdown-item {{ $enquiry->status === 'contacted' ? 'active' : '' }}">Mark as Contacted</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('centre.enquiries.status', $enquiry->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="converted">
                                                <button type="submit" class="dropdown-item {{ $enquiry->status === 'converted' ? 'active' : '' }}">Mark as Converted</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('centre.enquiries.status', $enquiry->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="closed">
                                                <button type="submit" class="dropdown-item {{ $enquiry->status === 'closed' ? 'active' : '' }}">Mark as Closed</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('centre.students.create', ['name' => $enquiry->name, 'mobile' => $enquiry->mobile, 'course_id' => $enquiry->course_id]) }}" class="btn btn-sm btn-success rounded-pill fw-semibold px-3" title="Convert to Admission">
                                        <i class="fa-solid fa-user-plus me-1"></i>Admit Student
                                    </a>
                                    <form action="{{ route('centre.enquiries.destroy', $enquiry->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this enquiry?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2" title="Delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fa-solid fa-inbox text-muted fs-1 mb-3 d-block"></i>
                                    <h6 class="fw-bold text-dark">No Enquiries Found</h6>
                                    <p class="text-muted small mb-3">When prospective students submit the admission form on your landing page, their details will appear here immediately.</p>
                                    <a href="{{ $centre->landing_page_url }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Open My Landing Page
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($enquiries->hasPages())
        <div class="card-footer bg-white border-top p-3">
            {{ $enquiries->links() }}
        </div>
    @endif
</div>
@endsection
