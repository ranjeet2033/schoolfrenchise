@extends('layouts.admin')

@section('title', 'Student Profile - ' . $student->full_name . ' - TECH ERA COMPUTER ERP')

@section('content')
<!-- Header & Navigation -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb dce-breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}" class="text-decoration-none">Students</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $student->roll_no }}</li>
            </ol>
        </nav>
        <h3 class="fw-bold mb-0 text-dark">Student Dossier</h3>
        <p class="text-muted small mb-0">Complete academic, financial, attendance and certification records.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.students.id-card', $student->id) }}" target="_blank" class="btn btn-primary btn-sm px-3 shadow-sm">
            <i class="fa-solid fa-id-card me-1"></i> Print ID Card
        </a>
        <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-outline-primary btn-sm px-3 shadow-sm">
            <i class="fa-solid fa-pen me-1"></i> Edit Profile
        </a>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm px-3 shadow-sm">
            <i class="fa-solid fa-print me-1"></i> Print Dossier
        </button>
        <a href="{{ route('admin.students.index') }}" class="btn btn-light btn-sm px-3 shadow-sm border">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Roster
        </a>
    </div>
</div>

<!-- Student Header Card -->
<div class="card dce-table-card mb-4 border-top border-4 border-info">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap align-items-center gap-4">
            <div class="position-relative">
                @if($student->photo_path)
                    <img src="{{ $student->photo_url ?? asset('storage/' . $student->photo_path) }}" alt="{{ $student->full_name }}" 
                         class="rounded-circle border shadow-sm" style="width: 100px; height: 100px; object-fit: cover;" onerror="this.onerror=null;this.src='https://placehold.co/120x120?text=Photo';">
                @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border text-secondary shadow-sm" 
                         style="width: 100px; height: 100px; font-size: 38px;">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                @endif
                @php
                    $statusColor = match($student->status) {
                        'enrolled' => 'bg-info',
                        'ongoing' => 'bg-primary',
                        'completed' => 'bg-success',
                        'dropped' => 'bg-danger',
                        default => 'bg-secondary',
                    };
                @endphp
                <span class="position-absolute bottom-0 end-0 badge rounded-pill {{ $statusColor }} border border-white" style="font-size: 11px;">
                    {{ ucfirst($student->status) }}
                </span>
            </div>

            <div class="flex-grow-1">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">{{ $student->full_name }}</h4>
                        <div class="text-muted small mb-2">
                            <span><i class="fa-solid fa-id-badge text-info me-1"></i>Roll No: <strong class="font-monospace text-dark">{{ $student->roll_no }}</strong></span>
                            <span class="mx-2">•</span>
                            <span><i class="fa-solid fa-hashtag text-secondary me-1"></i>Reg No: <span class="font-monospace">{{ $student->registration_no }}</span></span>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-dark-subtle text-dark px-3 py-2 fs-6 font-monospace">
                            <i class="fa-solid fa-building-columns me-1 text-primary"></i> {{ $student->centre?->centre_code }} - {{ $student->centre?->centre_name }}
                        </span>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-4 text-muted small border-top pt-2">
                    <div><i class="fa-solid fa-phone text-success me-1"></i> {{ $student->mobile }}</div>
                    @if($student->email)
                        <div><i class="fa-solid fa-envelope text-primary me-1"></i> {{ $student->email }}</div>
                    @endif
                    <div><i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $student->city }}, {{ $student->state?->name }}</div>
                    <div><i class="fa-solid fa-calendar-check text-info me-1"></i> Joined: {{ $student->created_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Financial & Academic KPI Cards -->
@php
    $totalAssignedFees = $student->fees->sum('total_amount');
    $totalPaidFees = $student->payments->where('status', 'success')->sum('amount_paid');
    $pendingDues = max(0, $totalAssignedFees - $totalPaidFees);
    $totalAttendance = $student->attendances->count();
    $presentCount = $student->attendances->where('status', 'present')->count();
    $attendancePercent = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100) : 0;
@endphp
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card dce-stat-card border-start border-4 border-primary">
            <div class="card-body p-3">
                <div class="text-muted small fw-semibold text-uppercase">Assigned Fees</div>
                <div class="h4 fw-bold mb-0 text-dark">₹{{ number_format($totalAssignedFees, 2) }}</div>
                <div class="text-muted small mt-1">{{ $student->fees->count() }} Fee Package(s)</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card dce-stat-card border-start border-4 border-success">
            <div class="card-body p-3">
                <div class="text-muted small fw-semibold text-uppercase">Collected Fees</div>
                <div class="h4 fw-bold mb-0 text-success">₹{{ number_format($totalPaidFees, 2) }}</div>
                <div class="text-muted small mt-1">{{ $student->payments->where('status', 'success')->count() }} Successful Receipts</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card dce-stat-card border-start border-4 border-danger">
            <div class="card-body p-3">
                <div class="text-muted small fw-semibold text-uppercase">Outstanding Dues</div>
                <div class="h4 fw-bold mb-0 text-danger">₹{{ number_format($pendingDues, 2) }}</div>
                <div class="text-muted small mt-1">{{ $pendingDues > 0 ? 'Payment pending' : 'Zero dues' }}</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card dce-stat-card border-start border-4 border-info">
            <div class="card-body p-3">
                <div class="text-muted small fw-semibold text-uppercase">Attendance Ratio</div>
                <div class="h4 fw-bold mb-0 text-info">{{ $attendancePercent }}%</div>
                <div class="text-muted small mt-1">{{ $presentCount }} / {{ $totalAttendance }} Sessions Marked</div>
            </div>
        </div>
    </div>
</div>

<!-- Details Tab System -->
<ul class="nav nav-tabs dce-tabs mb-3" id="studentTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab">
            <i class="fa-solid fa-user me-2"></i>Profile Particulars
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="academic-tab" data-bs-toggle="tab" data-bs-target="#academic-tab-pane" type="button" role="tab">
            <i class="fa-solid fa-book-open me-2"></i>Courses & Admissions ({{ $student->admissions->count() }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="fees-tab" data-bs-toggle="tab" data-bs-target="#fees-tab-pane" type="button" role="tab">
            <i class="fa-solid fa-receipt me-2"></i>Fees & Receipts ({{ $student->payments->count() }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="certificates-tab" data-bs-toggle="tab" data-bs-target="#certificates-tab-pane" type="button" role="tab">
            <i class="fa-solid fa-certificate me-2"></i>Certificates & Marksheets
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance-tab-pane" type="button" role="tab">
            <i class="fa-solid fa-clipboard-user me-2"></i>Attendance Log
        </button>
    </li>
</ul>

<div class="tab-content" id="studentTabContent">
    <!-- Profile Tab -->
    <div class="tab-pane fade show active" id="profile-tab-pane" role="tabpanel">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card dce-table-card h-100">
                    <div class="card-header bg-white">
                        <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-id-card-clip text-primary me-2"></i>Personal & Parent Information</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0 table-striped">
                            <tbody>
                                <tr>
                                    <th class="ps-3 w-35 text-muted">Full Name</th>
                                    <td class="fw-bold text-dark">{{ $student->full_name }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Father's Name</th>
                                    <td>{{ $student->father_name }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Mother's Name</th>
                                    <td>{{ $student->mother_name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Date of Birth</th>
                                    <td>{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d F Y') : '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Gender</th>
                                    <td>{{ ucfirst($student->gender) }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Prior Qualification</th>
                                    <td>{{ $student->highest_qualification ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Portal User Account</th>
                                    <td>
                                        <span class="font-monospace text-primary">{{ $student->user?->email }}</span>
                                        <span class="badge bg-success-subtle text-success ms-2">Active</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card dce-table-card mb-4">
                    <div class="card-header bg-white">
                        <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-map-pin text-danger me-2"></i>Address & Contact</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0 table-striped">
                            <tbody>
                                <tr>
                                    <th class="ps-3 text-muted">Primary Mobile</th>
                                    <td><strong class="text-success">{{ $student->mobile }}</strong></td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Alternate Mobile</th>
                                    <td>{{ $student->alternate_mobile ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Email</th>
                                    <td>{{ $student->email ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Location</th>
                                    <td>{{ $student->city }}, {{ $student->district?->name }}, {{ $student->state?->name }} - {{ $student->pincode }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Full Address</th>
                                    <td class="small">{{ $student->address }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card dce-table-card">
                    <div class="card-header bg-white">
                        <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-paperclip text-info me-2"></i>Uploaded Documents</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex flex-column gap-2">
                            @if($student->photo_url)
                                <a href="{{ $student->photo_url }}" target="_blank" class="btn btn-sm btn-outline-secondary text-start">
                                    <i class="fa-solid fa-image text-primary me-2"></i> View Passport Photo
                                </a>
                            @endif
                            @if($student->signature_url)
                                <a href="{{ $student->signature_url }}" target="_blank" class="btn btn-sm btn-outline-secondary text-start">
                                    <i class="fa-solid fa-signature text-secondary me-2"></i> View Signature
                                </a>
                            @endif
                            @if($student->id_proof_doc_url)
                                <a href="{{ $student->id_proof_doc_url }}" target="_blank" class="btn btn-sm btn-outline-secondary text-start">
                                    <i class="fa-solid fa-file-pdf text-danger me-2"></i> View KYC / ID Proof Document
                                </a>
                            @endif
                            @if(!$student->photo_path && !$student->signature_path && !$student->id_proof_doc)
                                <div class="text-muted small text-center py-2">No documents uploaded yet.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Academics & Admissions Tab -->
    <div class="tab-pane fade" id="academic-tab-pane" role="tabpanel">
        <div class="card dce-table-card">
            <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center py-2.5 gap-2">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-graduation-cap text-primary me-2"></i>Course Enrollments & Admission History ({{ $student->admissions->count() }})
                </h6>
                <button type="button" class="btn btn-sm btn-primary rounded-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#adminAddCourseModal">
                    <i class="fa-solid fa-plus-circle me-1"></i> Enroll Additional Course
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Admission No</th>
                                <th>Course Details</th>
                                <th>Study Centre</th>
                                <th>Admission Date</th>
                                <th>Fee Details</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($student->admissions as $adm)
                            <tr>
                                <td class="font-monospace fw-bold text-primary">
                                    {{ $adm->admission_no }}
                                    @if($loop->first)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1" style="font-size: 10px;">Primary</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $adm->course?->course_name ?? 'Course #' . $adm->course_id }}</div>
                                    <div class="text-muted small font-monospace">Code: {{ $adm->course?->course_code }} • Duration: {{ $adm->course?->duration_months ?? '—' }} Months</div>
                                    @if($adm->batch)
                                        <div class="small text-secondary mt-0.5"><i class="fa-solid fa-users me-1 text-info"></i>Batch: {{ $adm->batch->batch_name }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark font-monospace border">{{ $adm->centre?->centre_code ?? 'HO' }}</span>
                                    <div class="small text-dark">{{ $adm->centre?->centre_name }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $adm->admission_date ? \Carbon\Carbon::parse($adm->admission_date)->format('d M Y') : '—' }}</div>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none text-primary small" data-bs-toggle="modal" data-bs-target="#adminEditAdmDateModal{{ $adm->id }}" title="Change Admission Date">
                                        <i class="fa-solid fa-pen-to-square me-1"></i>Change Date
                                    </button>
                                </td>
                                <td>
                                    <div class="small fw-bold text-dark">Agreed: ₹{{ number_format($adm->net_fee, 2) }}</div>
                                    <div class="small text-success">Paid: ₹{{ number_format($adm->total_paid, 2) }}</div>
                                    @php $due = max(0, $adm->net_fee - $adm->total_paid); @endphp
                                    @if($due > 0)
                                        <div class="small text-danger fw-semibold">Due: ₹{{ number_format($due, 2) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $adm->status === 'active' ? 'bg-success' : ($adm->status === 'completed' ? 'bg-primary' : 'bg-secondary') }}">
                                        {{ ucfirst($adm->status) }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2">
                                            <li>
                                                <a class="dropdown-item py-1.5" href="{{ route('admin.students.id-card', [$student->id, 'admission_id' => $adm->id]) }}" target="_blank">
                                                    <i class="fa-solid fa-id-card text-primary me-2"></i> Print Course ID Card
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-1.5" href="{{ route('admin.admissions.print', $adm->id) }}" target="_blank">
                                                    <i class="fa-solid fa-file-invoice text-dark me-2"></i> Print Admission Form
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-1.5" href="{{ route('admin.admissions.edit', $adm->id) }}">
                                                    <i class="fa-solid fa-pen text-info me-2"></i> Edit Admission Details
                                                </a>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item py-1.5 text-warning-emphasis" data-bs-toggle="modal" data-bs-target="#adminEditAdmDateModal{{ $adm->id }}">
                                                    <i class="fa-solid fa-calendar-day me-2"></i> Change Admission Date
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No course admission assigned yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Fees & Receipts Tab -->
    <div class="tab-pane fade" id="fees-tab-pane" role="tabpanel">
        <div class="card dce-table-card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">Payment Transactions & Computerized Receipts</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Receipt / Trx ID</th>
                                <th>Date</th>
                                <th>Payment Mode</th>
                                <th>Amount Paid</th>
                                <th>Status</th>
                                <th>QR Verification</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($student->payments as $pay)
                            <tr>
                                <td>
                                    <div class="fw-bold font-monospace text-primary">{{ $pay->transaction_id }}</div>
                                    <div class="text-muted small">Ref: {{ $pay->reference_no ?? 'N/A' }}</div>
                                </td>
                                <td>{{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d M Y') : $pay->created_at->format('d M Y') }}</td>
                                <td><span class="badge bg-light text-dark text-uppercase border">{{ $pay->payment_mode }}</span></td>
                                <td class="fw-bold text-success fs-6">₹{{ number_format($pay->amount_paid, 2) }}</td>
                                <td>
                                    @php
                                        $badgeCls = match($pay->status) {
                                            'success' => 'bg-success',
                                            'pending' => 'bg-warning text-dark',
                                            'failed' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeCls }}">{{ ucfirst($pay->status) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-dark font-monospace" style="font-size: 10px;">VERIFIED-QR</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No fee payments recorded.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificates & Marksheets Tab -->
    <div class="tab-pane fade" id="certificates-tab-pane" role="tabpanel">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card dce-table-card h-100">
                    <div class="card-header bg-white">
                        <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-certificate text-warning me-2"></i>Issued Certificates</h6>
                    </div>
                    <div class="card-body p-3">
                        @forelse($student->certificates as $cert)
                            <div class="border rounded p-3 mb-2 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-primary font-monospace">{{ $cert->certificate_no }}</strong>
                                    <div>
                                        <span class="badge bg-success me-1">{{ ucfirst($cert->status) }}</span>
                                        <a href="{{ route('admin.certificates.print', $cert->id) }}" target="_blank" class="btn btn-xs btn-outline-primary py-0 px-2 rounded" title="Print Certificate">
                                            <i class="fa-solid fa-print me-1"></i>Print
                                        </a>
                                    </div>
                                </div>
                                <div class="small text-dark fw-semibold">{{ $cert->course?->course_name ?? 'Course #' . $cert->course_id }}</div>
                                <div class="small text-muted">Issued on: {{ $cert->issue_date ? \Carbon\Carbon::parse($cert->issue_date)->format('d M Y') : '—' }} • Grade: {{ $cert->grade }}</div>
                            </div>
                        @empty
                            <div class="text-muted text-center py-4">No certificates issued yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card dce-table-card h-100">
                    <div class="card-header bg-white">
                        <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-file-lines text-info me-2"></i>Issued Marksheets</h6>
                    </div>
                    <div class="card-body p-3">
                        @forelse($student->marksheets as $mark)
                            <div class="border rounded p-3 mb-2 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-primary font-monospace">{{ $mark->marksheet_no }}</strong>
                                    <div>
                                        <span class="badge bg-info me-1">Issued</span>
                                        <a href="{{ route('admin.certificates.marksheet', $mark->exam_result_id ?? $mark->id) }}" target="_blank" class="btn btn-xs btn-outline-info py-0 px-2 rounded" title="Print Marksheet">
                                            <i class="fa-solid fa-print me-1"></i>Print
                                        </a>
                                    </div>
                                </div>
                                <div class="small text-dark fw-semibold">{{ $mark->course?->course_name ?? 'Course #' . $mark->course_id }}</div>
                                <div class="small text-muted">Total: {{ $mark->total_marks }} / {{ $mark->max_marks }} ({{ $mark->percentage }}%) • Grade: {{ $mark->grade }}</div>
                            </div>
                        @empty
                            <div class="text-muted text-center py-4">No marksheets issued yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Log Tab -->
    <div class="tab-pane fade" id="attendance-tab-pane" role="tabpanel">
        <div class="card dce-table-card">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0 text-dark">Recent Attendance Sessions (Last 30 Days)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Batch</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($student->attendances->take(20) as $att)
                            <tr>
                                <td class="fw-semibold">{{ \Carbon\Carbon::parse($att->attendance_date)->format('d M Y') }}</td>
                                <td>{{ $att->batch?->batch_name ?? 'Regular Session' }}</td>
                                <td>
                                    @if($att->status == 'present')
                                        <span class="badge bg-success">Present</span>
                                    @elseif($att->status == 'absent')
                                        <span class="badge bg-danger">Absent</span>
                                    @else
                                        <span class="badge bg-warning text-dark">{{ ucfirst($att->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $att->remarks ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No attendance logs available.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODALS ================= -->

<!-- Modal: Enroll Additional Course (Head Office) -->
<div class="modal fade" id="adminAddCourseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="{{ route('admin.students.add-course', $student->id) }}" method="POST">
                @csrf
                <div class="modal-header border-bottom py-3">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="fa-solid fa-graduation-cap text-primary me-2"></i>Enroll Student into Additional Course
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 small rounded-3 mb-3">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Enrolling <strong>{{ $student->full_name }}</strong> (Roll: {{ $student->roll_no }}) into an additional course from Head Office.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Select Course <span class="text-danger">*</span></label>
                            <select name="course_id" id="adminCourseSelect" class="form-select" required>
                                <option value="">-- Choose Course --</option>
                                @foreach($courses as $c)
                                    <option value="{{ $c->id }}" data-fee="{{ $c->course_fee }}">
                                        {{ $c->course_name }} ({{ $c->course_code }}) - Fee: ₹{{ number_format($c->course_fee, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Admission Date <span class="text-danger">*</span></label>
                            <input type="date" name="admission_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Assign Batch (Optional)</label>
                            <select name="batch_id" class="form-select">
                                <option value="">-- No Batch Assigned --</option>
                                @foreach($batches as $b)
                                    <option value="{{ $b->id }}">{{ $b->batch_name }} ({{ $b->timing ?? 'Standard' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Standard Course Fee (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="course_fee" id="adminCourseFee" class="form-control" placeholder="0.00" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Discount Amount (₹)</label>
                            <input type="number" step="0.01" name="discount_amount" class="form-control" value="0.00">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Remarks / Notes</label>
                            <input type="text" name="remarks" class="form-control" placeholder="e.g. Additional course enrollment approved by Head Office">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-2.5">
                    <button type="button" class="btn btn-secondary rounded-2 btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-2 btn-sm px-4 fw-bold">
                        <i class="fa-solid fa-check me-1"></i> Enroll Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals: Edit Admission Date for each course admission -->
@foreach($student->admissions as $adm)
<div class="modal fade" id="adminEditAdmDateModal{{ $adm->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="{{ route('admin.students.admissions.date', [$student->id, $adm->id]) }}" method="POST">
                @csrf
                <div class="modal-header border-bottom py-2.5">
                    <h6 class="modal-title fw-bold text-dark">
                        <i class="fa-solid fa-calendar-day text-primary me-1"></i>Change Admission Date
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="small text-muted mb-2">Course: <strong>{{ $adm->course?->course_name ?? 'Course #' . $adm->course_id }}</strong></div>
                    <label class="form-label small fw-semibold">New Admission Date <span class="text-danger">*</span></label>
                    <input type="date" name="admission_date" class="form-control" 
                           value="{{ $adm->admission_date ? \Carbon\Carbon::parse($adm->admission_date)->toDateString() : date('Y-m-d') }}" required>
                </div>
                <div class="modal-footer border-top py-2">
                    <button type="button" class="btn btn-secondary btn-sm rounded-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-2 fw-semibold">Update Date</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function() {
    const courseSelect = document.getElementById('adminCourseSelect');
    const feeInput = document.getElementById('adminCourseFee');
    if (courseSelect && feeInput) {
        courseSelect.addEventListener('change', function() {
            const selectedOpt = this.options[this.selectedIndex];
            const fee = selectedOpt.getAttribute('data-fee');
            if (fee) {
                feeInput.value = parseFloat(fee).toFixed(2);
            }
        });
    }
});
</script>
@endsection
