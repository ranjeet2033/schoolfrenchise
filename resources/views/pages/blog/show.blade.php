@extends('layouts.app')

@section('title', $post->title . ' | ' . ($instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER'))
@section('meta_description', Str::limit(strip_tags($post->summary ?: $post->content), 160))
@section('meta_image', $post->featured_image_url)
@section('meta_keywords', implode(', ', array_filter([$post->title, $post->category, 'TECH ERA COMPUTER', 'Computer Education', 'Bihar', 'Sasbahana Sheikhpura'])))

@push('styles')
<style>
    .article-body {
        line-height: 1.85;
        font-size: 1.05rem;
        color: #1e293b;
    }
    .article-body h1, .article-body h2, .article-body h3, .article-body h4 {
        color: #0f172a;
        font-weight: 800;
        margin-top: 1.6rem;
        margin-bottom: 0.8rem;
    }
    .article-body p {
        margin-bottom: 1.25rem;
    }
    .article-body img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 1rem 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .article-body blockquote {
        border-left: 4px solid var(--dce-primary, #0d2238);
        background-color: #f8fafc;
        padding: 1rem 1.25rem;
        border-radius: 0 8px 8px 0;
        margin: 1.5rem 0;
        font-style: italic;
    }
    .article-body table {
        width: 100%;
        margin: 1.5rem 0;
        border-collapse: collapse;
    }
    .article-body table th, .article-body table td {
        border: 1px solid #e2e8f0;
        padding: 8px 12px;
    }
    .article-body table th {
        background-color: #f1f5f9;
        font-weight: 700;
    }
</style>
<!-- Automatic Schema.org BlogPosting Structured Data for SEO -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BlogPosting",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ url()->current() }}"
  },
  "headline": "{{ addslashes($post->title) }}",
  "description": "{{ addslashes(Str::limit(strip_tags($post->summary ?: $post->content), 160)) }}",
  "image": "{{ $post->featured_image_url }}",
  "author": {
    "@type": "Organization",
    "name": "{{ addslashes($post->author_name ?: 'TECH ERA COMPUTER') }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "{{ addslashes($instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER') }}",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ $instituteSettings['logo_url'] ?? asset('images/tech-era-logo.png') }}"
    }
  },
  "datePublished": "{{ ($post->published_at ?? $post->created_at)->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}"
}
</script>
@endpush

@section('content')
<div class="dce-page-banner">
    <div class="container text-center">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1 fw-bold">{{ $post->category ?? 'Article' }}</span>
        <h1 class="display-6 fw-bold text-white mb-3">{{ $post->title }}</h1>
        <p class="text-white-50 mb-0">
            <span><i class="fa-solid fa-user-pen me-1 text-warning"></i> {{ $post->author_name }}</span> &bull; 
            <span><i class="fa-regular fa-calendar me-1 text-info"></i> {{ $post->published_at ? $post->published_at->format('d M, Y') : $post->created_at->format('d M, Y') }}</span> &bull;
            <span><i class="fa-regular fa-clock me-1 text-success"></i> {{ $post->reading_time ?? 2 }} min read</span>
        </p>
    </div>
</div>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-5">
            <!-- Article Main Content -->
            <div class="col-lg-8">
                <article class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    @if($post->featured_image)
                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="img-fluid rounded-4 mb-4 shadow-sm w-100" style="max-height: 420px; object-fit: cover;">
                    @endif

                    @if($post->summary && strip_tags($post->summary) !== '')
                        <div class="p-3 bg-light rounded-3 mb-4 border-start border-4 border-primary fs-6 fst-italic text-secondary">
                            {!! $post->summary !!}
                        </div>
                    @endif

                    <div class="article-body">
                        {!! $post->content !!}
                    </div>

                    <div class="border-top pt-4 mt-5 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to All Articles
                        </a>
                        <div class="d-flex align-items-center gap-2">
                            <span class="small text-muted fw-bold">Share Article:</span>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="btn btn-sm btn-success rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="Share on WhatsApp">
                                <i class="fa-brands fa-whatsapp fs-6"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="Share on Facebook">
                                <i class="fa-brands fa-facebook-f fs-6"></i>
                            </a>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark border-bottom pb-3 mb-3"><i class="fa-solid fa-fire text-danger me-2"></i>Recent Articles</h5>
                    <div class="d-flex flex-column gap-3">
                        @foreach($recentPosts as $rPost)
                            <div class="d-flex gap-3 align-items-center">
                                <a href="{{ route('blog.show', $rPost->slug) }}" class="flex-shrink-0 overflow-hidden rounded-3 d-block" style="width: 64px; height: 64px;">
                                    <img src="{{ $rPost->featured_image_url }}" alt="{{ $rPost->title }}" class="w-100 h-100 transition-all hover-scale" style="object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('images/courses/default-course.svg') }}';">
                                </a>
                                <div>
                                    <h6 class="mb-1" style="font-size: 0.88rem;">
                                        <a href="{{ route('blog.show', $rPost->slug) }}" class="text-dark text-decoration-none fw-semibold">
                                            {{ Str::limit($rPost->title, 48) }}
                                        </a>
                                    </h6>
                                    <small class="text-muted" style="font-size: 0.72rem;"><i class="fa-regular fa-clock me-1"></i>{{ $rPost->published_at ? $rPost->published_at->format('d M, Y') : '' }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 bg-primary text-white text-center">
                    <div class="mb-2 fs-2 text-warning"><i class="fa-solid fa-building-columns"></i></div>
                    <h5 class="fw-bold mb-2">Want to Start a Franchise?</h5>
                    <p class="small text-white-50 mb-3">Partner with {{ $instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER' }} to establish an authorized computer academy in your town with complete government certifications and ERP software.</p>
                    <a href="{{ route('franchise.apply') }}" class="btn btn-warning fw-bold text-dark rounded-pill px-4 shadow-sm">
                        Apply for Franchise
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
