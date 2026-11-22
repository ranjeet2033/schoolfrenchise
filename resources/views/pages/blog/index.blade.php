@extends('layouts.app')

@section('title', 'Knowledge Hub & News | ' . ($instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER'))
@section('meta_description', 'Latest news, educational updates, student guidance, and technology insights from TECH ERA COMPUTER.')

@section('content')
<div class="dce-page-banner">
    <div class="container text-center">
        <h1>Latest Articles &amp; Updates</h1>
        <p class="text-white-50">Educational guidance, exam notifications, computer tips, and student announcements.</p>
    </div>
</div>

<section class="py-5 bg-light">
    <div class="container">
        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-md-8 mx-auto">
                <form action="{{ route('blog.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control rounded-pill px-4 shadow-sm" placeholder="Search blog articles..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-dce-primary rounded-pill px-4 fw-bold">Search</button>
                    @if(request('search') || request('category'))
                        <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary rounded-pill px-3">Clear</a>
                    @endif
                </form>
            </div>
        </div>

        <div class="row g-4">
            @forelse($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white d-flex flex-column hover-shadow transition-all">
                        <a href="{{ route('blog.show', $post->slug) }}" class="d-block overflow-hidden position-relative">
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-100 transition-all" style="height: 200px; object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('images/courses/default-course.svg') }}';">
                        </a>
                        <div class="card-body p-4 d-flex flex-column flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">{{ $post->category ?? 'Announcement' }}</span>
                                <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $post->published_at ? $post->published_at->format('d M, Y') : $post->created_at->format('d M, Y') }}</small>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none">
                                    {{ $post->title }}
                                </a>
                            </h5>
                            <p class="text-muted small flex-grow-1 mb-3">
                                {{ Str::limit($post->summary ?: strip_tags($post->content), 120) }}
                            </p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="fw-bold text-primary text-decoration-none small d-inline-flex align-items-center gap-1 mt-auto">
                                Read Full Article <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="fa-regular fa-newspaper fs-1 text-black-50 mb-3 d-block"></i>
                    <h4>No articles found</h4>
                    <p class="small">Check back soon for new announcements, articles, and student guides.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
</section>
@endsection
