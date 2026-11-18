@extends('layouts.app')

@section('title', $post->title . ' | ' . ($instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER'))
@section('meta_description', Str::limit($post->summary ?: strip_tags($post->content), 160))

@section('content')
<div class="dce-page-banner">
    <div class="container text-center">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1">{{ $post->category ?? 'Article' }}</span>
        <h1 class="display-6 fw-bold text-white">{{ $post->title }}</h1>
        <p class="text-white-50">
            <span><i class="fa-solid fa-user-pen me-1"></i> {{ $post->author_name }}</span> &bull; 
            <span><i class="fa-regular fa-calendar me-1"></i> {{ $post->published_at ? $post->published_at->format('d M, Y') : $post->created_at->format('d M, Y') }}</span>
        </p>
    </div>
</div>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-5">
            <!-- Article Main Content -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    @if($post->featured_image)
                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="img-fluid rounded-4 mb-4 shadow-sm w-100" style="max-height: 420px; object-fit: cover;">
                    @endif

                    <div class="article-body fs-6 text-dark" style="line-height: 1.8;">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    <div class="border-top pt-4 mt-5 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to All Articles
                        </a>
                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-muted">Share:</span>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="btn btn-sm btn-success rounded-circle" style="width: 32px; height: 32px;" title="Share on WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark border-bottom pb-3 mb-3">Recent Posts</h5>
                    <div class="d-flex flex-column gap-3">
                        @foreach($recentPosts as $rPost)
                            <div class="d-flex gap-3 align-items-center">
                                <img src="{{ $rPost->featured_image_url }}" alt="" class="rounded-3" style="width: 60px; height: 60px; object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('images/courses/default-course.svg') }}';">
                                <div>
                                    <h6 class="mb-1" style="font-size: 0.88rem;">
                                        <a href="{{ route('blog.show', $rPost->slug) }}" class="text-dark text-decoration-none fw-semibold">
                                            {{ Str::limit($rPost->title, 45) }}
                                        </a>
                                    </h6>
                                    <small class="text-muted" style="font-size: 0.72rem;">{{ $rPost->published_at ? $rPost->published_at->format('d M, Y') : '' }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 bg-primary text-white text-center">
                    <h5 class="fw-bold mb-2">Want to Start a Franchise?</h5>
                    <p class="small text-white-50 mb-3">Partner with TECH ERA COMPUTER to establish an authorized computer academy in your town.</p>
                    <a href="{{ route('franchise.apply') }}" class="btn btn-warning fw-bold text-dark rounded-pill px-4">
                        Apply for Franchise
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
