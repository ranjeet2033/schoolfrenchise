@extends('layouts.admin')

@section('title', 'Add New Digital Product - Admin Panel')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-cloud-arrow-up text-primary me-2"></i>Add New Digital Product</h3>
        <p class="text-muted small mb-0">Upload study material PDF or configure a Private YouTube video series.</p>
    </div>
    <a href="{{ route('admin.store.products.index') }}" class="btn btn-outline-secondary rounded-3 px-3 shadow-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Products
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger rounded-3 shadow-sm mb-4">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.store.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Product Type Selection -->
            <div class="mb-4 p-3 bg-light rounded-3 border">
                <label class="form-label fw-bold d-block text-dark">Select Product Type <span class="text-danger">*</span></label>
                <div class="d-flex gap-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="typePdf" value="pdf" {{ old('type', 'pdf') === 'pdf' ? 'checked' : '' }} onchange="toggleTypeFields()">
                        <label class="form-check-label fw-bold" for="typePdf">
                            <i class="fa-solid fa-file-pdf text-danger me-1"></i> PDF E-Book / Study Notes
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="typeVideo" value="video" {{ old('type') === 'video' ? 'checked' : '' }} onchange="toggleTypeFields()">
                        <label class="form-check-label fw-bold" for="typeVideo">
                            <i class="fa-brands fa-youtube text-danger me-1"></i> YouTube Private Video Lecture
                        </label>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label small fw-bold">Product Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-control" required placeholder="e.g. Complete Beltron Typing & DEO Question Bank 2026">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Category</label>
                    <input type="text" name="category" value="{{ old('category', 'Computer Exam Notes') }}" class="form-control" placeholder="e.g. Typing Lab, Beltron, ADCA">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Original Price (₹) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', '199.00') }}" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Offer / Sale Price (₹)</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', '99.00') }}" class="form-control" placeholder="Optional discounted price">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold">Thumbnail Cover Image</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                </div>

                <!-- PDF Specific Fields -->
                <div id="pdfFields" class="col-12">
                    <div class="p-3 bg-light rounded-3 border">
                        <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-file-pdf text-danger me-1"></i> PDF File Details</h6>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-bold">Upload PDF File <span class="text-danger">*</span></label>
                                <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
                                <span class="form-text small text-muted">Uploaded file will be securely stored and only accessible by paid customers.</span>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Total Pages Count</label>
                                <input type="number" name="total_pages" value="{{ old('total_pages') }}" class="form-control" placeholder="e.g. 150">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video Specific Fields -->
                <div id="videoFields" class="col-12 d-none">
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h6 class="fw-bold text-dark mb-0"><i class="fa-brands fa-youtube text-danger me-1"></i> YouTube Video Configuration</h6>
                            <span class="badge bg-secondary font-monospace">Public or Private Mode</span>
                        </div>

                        <!-- Video Access Type Selector (Public vs Private) -->
                        <div class="card border-0 bg-white rounded-3 shadow-sm p-3 mb-3">
                            <label class="form-label small fw-bold text-dark d-block mb-2">Video Access & Visibility Type <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check p-3 border rounded-3 h-100 bg-light-subtle">
                                        <input class="form-check-input ms-0 me-2" type="radio" name="video_access_type" id="accessPrivate" value="private" {{ old('video_access_type', 'private') === 'private' ? 'checked' : '' }} onchange="toggleVideoAccessMode()">
                                        <label class="form-check-label fw-bold text-dark" for="accessPrivate">
                                            <i class="fa-solid fa-lock text-danger me-1"></i> Private Video (Access / Invite Required)
                                        </label>
                                        <p class="text-muted small mb-0 mt-1" style="font-size: 0.78rem;">
                                            Video locked rahegi. Student/customer ko admin panel se access dena hoga ya wo purchase karke Gmail invite receive karenge.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check p-3 border rounded-3 h-100 bg-light-subtle">
                                        <input class="form-check-input ms-0 me-2" type="radio" name="video_access_type" id="accessPublic" value="public" {{ old('video_access_type') === 'public' ? 'checked' : '' }} onchange="toggleVideoAccessMode()">
                                        <label class="form-check-label fw-bold text-success" for="accessPublic">
                                            <i class="fa-solid fa-globe text-success me-1"></i> Public Video (Free to Watch Openly)
                                        </label>
                                        <p class="text-muted small mb-0 mt-1" style="font-size: 0.78rem;">
                                            Koi bhi student ya visitor website par bina kisi payment ya login ke directly YouTube video free me dekh sakega.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">YouTube Video URL / Link <span class="text-danger">*</span></label>
                                <input type="url" name="youtube_video_url" value="{{ old('youtube_video_url') }}" class="form-control" placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/...">
                                <span class="form-text small text-muted">YouTube video ka normal link ya short link paste karein.</span>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Class Duration</label>
                                <input type="text" name="duration" value="{{ old('duration', '2 Hours 30 Mins') }}" class="form-control">
                            </div>
                            <div class="col-md-3" id="maxInvitesWrapper">
                                <label class="form-label small fw-bold">Max Gmail Invites (Private Quota)</label>
                                <input type="number" name="max_invites" id="maxInvitesInput" value="{{ old('max_invites', 50) }}" max="50" min="1" class="form-control">
                                <span class="form-text small text-muted">YouTube allows max 50 email invites for private videos.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="form-label small fw-bold">Short Summary / Highlights</label>
                    <textarea name="short_description" rows="2" class="form-control" placeholder="Brief 1-2 sentence summary displayed on product cards...">{{ old('short_description') }}</textarea>
                </div>

                <div class="col-md-12">
                    <label class="form-label small fw-bold">Detailed Syllabus / Description</label>
                    <textarea name="description" rows="5" class="form-control" placeholder="Detailed syllabus breakdown, chapters covered, benefits, exam relevance...">{{ old('description') }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active (Visible in Store)</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive / Draft</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold small" for="isFeatured">
                            Mark as Featured Product
                        </label>
                    </div>
                </div>

                <div class="col-12 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Save Product
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleTypeFields() {
    const isVideo = document.getElementById('typeVideo').checked;
    const pdfFields = document.getElementById('pdfFields');
    const videoFields = document.getElementById('videoFields');

    if (isVideo) {
        pdfFields.classList.add('d-none');
        videoFields.classList.remove('d-none');
        toggleVideoAccessMode();
    } else {
        pdfFields.classList.remove('d-none');
        videoFields.classList.add('d-none');
    }
}

function toggleVideoAccessMode() {
    const isPublic = document.getElementById('accessPublic') && document.getElementById('accessPublic').checked;
    const priceInput = document.querySelector('input[name="price"]');
    const salePriceInput = document.querySelector('input[name="sale_price"]');
    const maxInvitesWrapper = document.getElementById('maxInvitesWrapper');

    if (isPublic) {
        if (priceInput) priceInput.value = '0.00';
        if (salePriceInput) salePriceInput.value = '';
        if (maxInvitesWrapper) maxInvitesWrapper.classList.add('opacity-50');
    } else {
        if (maxInvitesWrapper) maxInvitesWrapper.classList.remove('opacity-50');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    toggleTypeFields();
    toggleVideoAccessMode();
});
</script>
@endpush
