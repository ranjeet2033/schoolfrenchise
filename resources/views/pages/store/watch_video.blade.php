@extends('layouts.app')

@section('title', 'Watch Course: ' . $product->title . ' | ' . ($instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER'))
@section('meta_description', 'Authorized Video Learning Hub for ' . $product->title)

@section('content')
<!-- Top Bar -->
<div class="bg-dark text-white py-3 border-bottom border-secondary">
    <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('store.index') }}" class="text-white-50 text-decoration-none">Store</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ $product->title }}</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success font-monospace px-3 py-1.5"><i class="fa-solid fa-shield-halved me-1"></i> Authorized Video License</span>
            <a href="{{ route('store.purchases') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">
                <i class="fa-solid fa-bag-shopping me-1"></i> My Purchases
            </a>
        </div>
    </div>
</div>

<div class="bg-black py-4 py-lg-5 text-white">
    <div class="container">
        <div class="row g-4">
            <!-- Main Video Stage (Left 8 Cols) -->
            <div class="col-lg-8">
                <!-- Video Player Container -->
                <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-2xl border border-secondary border-opacity-50 bg-dark position-relative" id="mainVideoPlayerWrapper">
                    @if($primaryYtId)
                        <iframe id="videoPlayerFrame" src="https://www.youtube.com/embed/{{ $primaryYtId }}?rel=0&autoplay=1&enablejsapi=1" title="{{ $product->title }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-100 h-100 border-0"></iframe>
                    @elseif(!empty($product->youtube_video_url))
                        @php
                            $driveEmbed = str_replace(['/view?usp=sharing', '/view'], '/preview', $product->youtube_video_url);
                        @endphp
                        <iframe id="videoPlayerFrame" src="{{ $driveEmbed }}" title="{{ $product->title }}" allow="autoplay" allowfullscreen class="w-100 h-100 border-0"></iframe>
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center p-4">
                            <i class="fa-solid fa-play-circle fa-4x text-danger mb-3"></i>
                            <h5>Select a lecture from the playlist to start watching</h5>
                        </div>
                    @endif
                </div>

                <!-- Video Title & Metadata -->
                <div class="mt-3 d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <span class="badge bg-danger text-uppercase font-monospace mb-1">{{ $product->category ?? 'Video Course' }}</span>
                        <h4 class="fw-bold mb-1" id="currentPlayingTitle">{{ $product->title }}</h4>
                        <div class="text-white-50 small">
                            <i class="fa-regular fa-clock text-info me-1"></i> {{ $product->duration ?? 'Complete Course' }} &bull;
                            <i class="fa-solid fa-user-graduate text-success ms-2 me-1"></i> Licensed to: <strong>{{ $access->customer_gmail ?? $access->customer_email }}</strong>
                        </div>
                    </div>
                    @if($product->effective_demo_url)
                        <a href="{{ $product->effective_demo_url }}" target="_blank" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                            <i class="fa-solid fa-circle-play me-1"></i> Open Demo
                        </a>
                    @endif
                </div>

                <!-- Course Description & Syllabus -->
                <div class="card bg-dark border-secondary border-opacity-50 rounded-4 p-4 mt-4 text-white">
                    <h5 class="fw-bold mb-3 border-bottom border-secondary pb-2"><i class="fa-solid fa-book-open text-primary me-2"></i>Course Overview &amp; Learning Goals</h5>
                    <div class="text-white-50 lh-lg" style="font-size: 0.95rem;">
                        {!! $product->description ? nl2br(e($product->description)) : '<p>' . e($product->short_description) . '</p>' !!}
                    </div>

                    <!-- PDF / Drive Resources if available -->
                    @if(!empty($product->normalized_resource_links) || !empty($product->pdf_file_path))
                        <div class="border-top border-secondary pt-3 mt-3">
                            <h6 class="fw-bold text-warning mb-2"><i class="fa-solid fa-paperclip me-2"></i>Included Study Materials &amp; Drive Folders</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @if(!empty($product->pdf_file_path))
                                    <a href="{{ route('store.download.pdf', $access->access_token) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fa-solid fa-file-pdf me-1"></i> Download Accompanying Notes (PDF)
                                    </a>
                                @endif
                                @foreach($product->normalized_resource_links as $res)
                                    <a href="{{ $res['url'] }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                        <i class="fa-brands fa-google-drive me-1"></i> {{ $res['title'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Video Playlist / Modules Sidebar (Right 4 Cols) -->
            <div class="col-lg-4">
                <div class="card bg-dark border-secondary border-opacity-50 rounded-4 overflow-hidden shadow-sm">
                    <div class="card-header bg-black py-3 px-3 d-flex justify-content-between align-items-center border-bottom border-secondary">
                        <h6 class="fw-bold mb-0 text-white"><i class="fa-solid fa-list-check text-danger me-2"></i>Course Videos ({{ 1 + count($videoLinks) }})</h6>
                        <span class="badge bg-secondary font-monospace">Full Access</span>
                    </div>

                    <div class="list-group list-group-flush" style="max-height: 580px; overflow-y: auto;">
                        <!-- Primary Main Video Item -->
                        @php
                            $mainVideoUrl = $primaryYtId ? "https://www.youtube.com/embed/{$primaryYtId}?rel=0&autoplay=1" : (str_replace(['/view?usp=sharing', '/view'], '/preview', $product->youtube_video_url));
                        @endphp
                        <button type="button" class="list-group-item list-group-item-action bg-dark text-white border-secondary border-opacity-25 p-3 playlist-item active" onclick="switchVideo('{{ $mainVideoUrl }}', '{{ addslashes($product->title) }}', this)">
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="fa-solid fa-play"></i>
                                </span>
                                <div class="flex-grow-1 text-truncate">
                                    <strong class="d-block text-white text-truncate" style="font-size: 0.9rem;">1. Main Lecture Class</strong>
                                    <small class="text-white-50">{{ $product->duration ?? 'Main Video' }}</small>
                                </div>
                                <span class="badge bg-success-subtle text-success small font-monospace">Active</span>
                            </div>
                        </button>

                        <!-- Additional Module / Playlist Videos -->
                        @foreach($videoLinks as $idx => $vLink)
                            @php
                                $vUrl = $vLink['url'];
                                $ytParsed = \App\Models\DigitalProduct::extractYoutubeId($vUrl);
                                $embedUrl = $ytParsed
                                    ? "https://www.youtube.com/embed/{$ytParsed}?rel=0&autoplay=1"
                                    : str_replace(['/view?usp=sharing', '/view'], '/preview', $vUrl);
                            @endphp
                            <button type="button" class="list-group-item list-group-item-action bg-dark text-white border-secondary border-opacity-25 p-3 playlist-item" onclick="switchVideo('{{ $embedUrl }}', '{{ addslashes($vLink['title']) }}', this)">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-secondary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="fa-solid {{ ($vLink['platform'] ?? 'youtube') === 'drive' ? 'fa-film' : 'fa-play' }}"></i>
                                    </span>
                                    <div class="flex-grow-1 text-truncate">
                                        <strong class="d-block text-white text-truncate" style="font-size: 0.9rem;">{{ $idx + 2 }}. {{ $vLink['title'] }}</strong>
                                        <small class="text-white-50 text-capitalize">{{ $vLink['platform'] ?? 'Video Module' }}</small>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-white-50 small"></i>
                                </div>
                            </button>
                        @endforeach
                    </div>

                    <!-- Verified License Footer -->
                    <div class="card-footer bg-black p-3 border-top border-secondary text-center">
                        <small class="text-white-50 d-block mb-1">Order #{{ $access->order?->order_number ?? 'LIC-' . $access->id }}</small>
                        <span class="badge bg-primary bg-opacity-25 text-info border border-info border-opacity-25 px-2 py-1">
                            <i class="fa-solid fa-lock text-success me-1"></i> Personal Educational License
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchVideo(url, title, element) {
    const iframe = document.getElementById('videoPlayerFrame');
    const titleEl = document.getElementById('currentPlayingTitle');

    if (iframe && url) {
        iframe.src = url;
    }
    if (titleEl && title) {
        titleEl.textContent = title;
    }

    document.querySelectorAll('.playlist-item').forEach(btn => {
        btn.classList.remove('active');
        const badge = btn.querySelector('.badge.bg-success-subtle');
        if (badge) badge.remove();
    });

    if (element) {
        element.classList.add('active');
        const flexDiv = element.querySelector('.d-flex');
        if (flexDiv && !element.querySelector('.badge.bg-success-subtle')) {
            const span = document.createElement('span');
            span.className = 'badge bg-success-subtle text-success small font-monospace';
            span.textContent = 'Active';
            flexDiv.appendChild(span);
        }
    }

    // Smooth scroll to video stage on mobile
    if (window.innerWidth < 992) {
        document.getElementById('mainVideoPlayerWrapper')?.scrollIntoView({ behavior: 'smooth' });
    }
}
</script>
@endpush
@endsection
