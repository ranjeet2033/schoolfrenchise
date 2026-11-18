@extends('layouts.app')

@section('title', '404 - Page Not Found | ' . ($instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER'))

@section('content')
<section class="py-5 my-md-5">
    <div class="container text-center py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="mb-4">
                    <span class="display-1 fw-bolder text-primary">404</span>
                </div>
                <h2 class="fw-bold text-dark mb-3">Oops! Page Not Found</h2>
                <p class="text-muted mb-4 lead fs-6">
                    Aap jis page ko dhoondh rahe hain wo uplabdh nahi hai ya remove kar diya gaya hai. Kripya mukhya prishth (Home page) par wapas jayein.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('home') }}" class="btn btn-dce-primary px-4 py-2.5 rounded-3 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-house"></i>
                        <span>Go to Home Page</span>
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-secondary px-4 py-2.5 rounded-3 fw-bold d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-envelope"></i>
                        <span>Contact Support</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
