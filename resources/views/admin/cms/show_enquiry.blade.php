@extends('layouts.admin')

@section('title', 'Enquiry Dossier #' . $enquiry->id)

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.cms.enquiries') }}">Enquiries</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Enquiry #{{ $enquiry->id }}</li>
                </ol>
            </nav>
            <h2 class="h4 fw-bold mb-0 text-dark">
                <i class="fa-solid fa-envelope-open-text text-primary me-2"></i>Enquiry #{{ $enquiry->id }}
            </h2>
            <p class="text-muted small mb-0">Detailed inquiry particulars and lead conversion lifecycle.</p>
        </div>
        <div>
            <a href="{{ route('admin.cms.enquiries') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Enquiries
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Main Message Content -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-comment-dots me-2 text-primary"></i>Message & Particulars</h6>
                    <span class="badge bg-light text-muted border font-monospace">{{ $enquiry->created_at->format('d M, Y h:i A') }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase">Subject</label>
                        <h5 class="fw-bold text-dark mt-1">{{ $enquiry->subject }}</h5>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase">Message Content</label>
                        <div class="p-3 bg-light rounded-3 mt-1 text-dark" style="white-space: pre-wrap; line-height: 1.6;">
                            {{ $enquiry->message ?? 'No message text provided.' }}
                        </div>
                    </div>

                    <div class="row g-3 pt-3 border-top">
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase">State / Province</label>
                            <div class="fw-semibold text-dark">{{ $enquiry->state ? $enquiry->state->name : 'Not Specified' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase">District / City</label>
                            <div class="fw-semibold text-dark">{{ $enquiry->district ? $enquiry->district->name : 'Not Specified' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Target Branch & Course Association Card -->
            <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-code-branch me-2 text-warning"></i>Assigned Branch & Course Details</h6>
                    @if($enquiry->centre)
                        <span class="badge bg-success font-monospace"><i class="fa-solid fa-check-circle me-1"></i>Franchise Lead</span>
                    @else
                        <span class="badge bg-secondary font-monospace">Head Office</span>
                    @endif
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Target Study Centre / Branch</label>
                            @if($enquiry->centre)
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-dark font-monospace text-warning px-2 py-1">{{ $enquiry->centre->centre_code }}</span>
                                        <h6 class="fw-bold text-dark mb-0">{{ $enquiry->centre->centre_name }}</h6>
                                    </div>
                                    <div class="small text-muted mb-2">
                                        <i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $enquiry->centre->address }}, {{ $enquiry->centre->city }} ({{ $enquiry->centre->district?->name ?? '' }}, {{ $enquiry->centre->state?->name ?? '' }})
                                    </div>
                                    <div class="small text-muted mb-2">
                                        <i class="fa-solid fa-user-tie me-1"></i><strong>Director:</strong> {{ $enquiry->centre->director_name ?? 'N/A' }} | 
                                        <i class="fa-solid fa-phone ms-2 me-1"></i>{{ $enquiry->centre->mobile }}
                                    </div>
                                    <div class="d-flex gap-2 mt-2">
                                        <a href="{{ route('admin.centres.show', $enquiry->centre->id) }}" class="btn btn-sm btn-outline-primary rounded-2">
                                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Branch in Admin
                                        </a>
                                        <a href="{{ $enquiry->centre->landing_page_url }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-2">
                                            <i class="fa-solid fa-globe me-1"></i> Open Franchise Landing Page
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="p-3 bg-light rounded-3 border text-muted">
                                    <i class="fa-solid fa-building me-1"></i> General inquiry submitted directly to Central Head Office website.
                                </div>
                            @endif
                        </div>

                        <div class="col-md-5">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Selected Course</label>
                            @if($enquiry->course)
                                <div class="p-3 bg-light rounded-3 border">
                                    <span class="badge bg-primary font-monospace mb-1">{{ $enquiry->course->course_code }}</span>
                                    <h6 class="fw-bold text-dark mb-1">{{ $enquiry->course->course_name }}</h6>
                                    <div class="small text-muted mb-1"><i class="fa-regular fa-clock me-1 text-primary"></i><strong>Duration:</strong> {{ $enquiry->course->duration }}</div>
                                    <div class="small text-muted"><i class="fa-solid fa-tag me-1 text-success"></i><strong>Category:</strong> {{ $enquiry->course->category->name ?? 'Accredited' }}</div>
                                </div>
                            @else
                                <div class="p-3 bg-light rounded-3 border text-muted">
                                    <i class="fa-solid fa-bookmark me-1"></i> General Admission / No specific course pre-selected.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact & Status Management Sidebar -->
        <div class="col-lg-4">
            <!-- Sender Card -->
            <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-id-card me-2 text-primary"></i>Sender Information</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                            <i class="fa-solid fa-user fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">{{ $enquiry->name }}</h6>
                            <span class="badge bg-light text-muted border">{{ str_replace('_', ' ', $enquiry->type) }}</span>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Mobile</span>
                            <a href="tel:{{ $enquiry->mobile }}" class="fw-bold text-primary font-monospace text-decoration-none">
                                <i class="fa-solid fa-phone me-1"></i>{{ $enquiry->mobile }}
                            </a>
                        </li>
                        @if($enquiry->email)
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted">Email</span>
                                <a href="mailto:{{ $enquiry->email }}" class="fw-semibold text-dark text-decoration-none">
                                    {{ $enquiry->email }}
                                </a>
                            </li>
                        @endif
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Enquiry Type</span>
                            <span class="fw-semibold text-dark text-capitalize">{{ str_replace('_', ' ', $enquiry->type) }}</span>
                        </li>
                    </ul>

                    <div class="d-grid gap-2 mt-3">
                        <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $enquiry->mobile) }}" target="_blank" class="btn btn-sm btn-success">
                            <i class="fa-brands fa-whatsapp me-1"></i> Chat on WhatsApp
                        </a>
                        <a href="tel:{{ $enquiry->mobile }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-phone me-1"></i> Direct Call
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lead Status Management -->
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-arrows-rotate me-2 text-primary"></i>Lead Lifecycle Status</h6>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('admin.cms.enquiries.update', $enquiry) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="status" class="form-label small fw-semibold">Current Pipeline State</label>
                            <select name="status" id="status" class="form-select form-select-sm" required>
                                <option value="new" {{ $enquiry->status == 'new' ? 'selected' : '' }}>New (Needs Follow-up)</option>
                                <option value="contacted" {{ $enquiry->status == 'contacted' ? 'selected' : '' }}>Contacted / Speaking</option>
                                <option value="converted" {{ $enquiry->status == 'converted' ? 'selected' : '' }}>Converted / Enrolled</option>
                                <option value="closed" {{ $enquiry->status == 'closed' ? 'selected' : '' }}>Closed (Not Interested)</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-check me-1"></i> Update Lead Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
