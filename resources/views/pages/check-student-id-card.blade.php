@extends('layouts.app')

@section('title', 'Check Student ID Card | ' . ($instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER'))
@section('meta_description', 'Search and verify student identity card credentials online by Roll No, Registration No or verification token.')

@section('content')
<div class="dce-page-banner">
    <div class="container text-center">
        <h1>Verify Student ID Card</h1>
        <p class="text-white-50">Authentic digital verification system for student cards issued by TECH ERA COMPUTER.</p>
    </div>
</div>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white">
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="fa-solid fa-id-card fs-2"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Student ID Card Search</h4>
                        <p class="text-muted small">Enter Roll Number, Registration Number or Token below</p>
                    </div>

                    <form action="{{ route('verify.student.search.submit') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Roll No. / Reg. No. / Card Token <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                <input type="text" name="query" class="form-control" placeholder="e.g. TEC2601001 or DCE/24/8430002" required autofocus>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dce-primary w-100 py-2.5 fw-bold fs-6">
                            <i class="fa-solid fa-shield-check me-2"></i> Verify Student ID Card
                        </button>
                    </form>

                    <div class="mt-4 pt-3 border-top text-center">
                        <small class="text-muted">
                            <i class="fa-solid fa-qrcode text-primary me-1"></i> You can also scan the QR code printed on the physical ID card using your mobile camera.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
