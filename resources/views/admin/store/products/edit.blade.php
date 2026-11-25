@extends('layouts.admin')

@section('title', 'Edit Digital Product - Admin Panel')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Digital Product</h3>
        <p class="text-muted small mb-0">{{ $product->title }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('store.show', ['type' => $product->type, 'slug' => $product->slug]) }}" target="_blank" class="btn btn-outline-info rounded-3 px-3 shadow-sm">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Preview
        </a>
        <a href="{{ route('admin.store.products.index') }}" class="btn btn-outline-secondary rounded-3 px-3 shadow-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>
    </div>
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
        <form action="{{ route('admin.store.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Product Type Selection -->
            <div class="mb-4 p-3 bg-light rounded-3 border">
                <label class="form-label fw-bold d-block text-dark">Product Type</label>
                <div class="d-flex gap-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="typePdf" value="pdf" {{ old('type', $product->type) === 'pdf' ? 'checked' : '' }} onchange="toggleTypeFields()">
                        <label class="form-check-label fw-bold" for="typePdf">
                            <i class="fa-solid fa-file-pdf text-danger me-1"></i> PDF E-Book / Study Notes
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="typeVideo" value="video" {{ old('type', $product->type) === 'video' ? 'checked' : '' }} onchange="toggleTypeFields()">
                        <label class="form-check-label fw-bold" for="typeVideo">
                            <i class="fa-brands fa-youtube text-danger me-1"></i> YouTube Private Video Lecture
                        </label>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label small fw-bold">Product Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $product->title) }}" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Category</label>
                    <input type="text" name="category" value="{{ old('category', $product->category) }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Original Price (₹) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Offer / Sale Price (₹)</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold">Thumbnail Cover Image</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    @if($product->thumbnail)
                        <div class="mt-2">
                            <img src="{{ $product->thumbnail_url }}" alt="" height="50" class="rounded border p-1 bg-light">
                        </div>
                    @endif
                </div>

                <!-- PDF Specific Fields -->
                <div id="pdfFields" class="col-12">
                    <div class="p-3 bg-light rounded-3 border">
                        <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-file-pdf text-danger me-1"></i> PDF File & Drive Resources</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Replace Primary PDF File</label>
                                <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
                                @if($product->pdf_file_path)
                                    <span class="small text-success d-block mt-1"><i class="fa-solid fa-circle-check me-1"></i> Existing PDF is safely stored.</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold"><i class="fa-brands fa-google-drive text-warning me-1"></i> Primary PDF Google Drive Link</label>
                                <input type="url" name="pdf_drive_link" value="{{ old('pdf_drive_link', $product->pdf_drive_link) }}" class="form-control" placeholder="https://drive.google.com/file/d/...">
                                <span class="form-text small text-muted">Optionally host the main PDF on Google Drive directly.</span>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Total Pages Count</label>
                                <input type="number" name="total_pages" value="{{ old('total_pages', $product->total_pages) }}" class="form-control">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small fw-bold"><i class="fa-solid fa-eye text-primary me-1"></i> Demo / Sample PDF Link</label>
                                <input type="url" name="pdf_demo_url" id="pdf_demo_url" value="{{ old('pdf_demo_url', old('demo_url', $product->type === 'pdf' ? $product->demo_url : '')) }}" class="form-control" placeholder="Google Drive or sample PDF URL">
                                <span class="form-text small text-muted">Triggers the prominent "Demo" button on PDF card &amp; details page (Free to view).</span>
                            </div>

                            <!-- Multiple PDF / Google Drive Resource Links -->
                            <div class="col-12 mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label small fw-bold text-dark mb-0">
                                        <i class="fa-brands fa-google-drive text-warning me-1"></i> Additional Google Drive / PDF Download Links (Multiple Links)
                                    </label>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addPdfResourceLink()">
                                        <i class="fa-solid fa-plus me-1"></i> Add Drive / PDF Link
                                    </button>
                                </div>
                                <div id="pdfResourceLinksContainer" class="d-flex flex-column gap-2">
                                    <!-- Render existing links -->
                                </div>
                                <span class="form-text small text-muted">Paid customers will be able to access all these Google Drive folders/files and PDFs.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video Specific Fields -->
                <div id="videoFields" class="col-12 d-none">
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h6 class="fw-bold text-dark mb-0"><i class="fa-brands fa-youtube text-danger me-1"></i> Video Course & Lectures Configuration</h6>
                            <span class="badge bg-secondary font-monospace">YouTube &amp; Google Drive Supported</span>
                        </div>

                        <!-- Video Access Type Selector (Public vs Private) -->
                        <div class="card border-0 bg-white rounded-3 shadow-sm p-3 mb-3">
                            <label class="form-label small fw-bold text-dark d-block mb-2">Video Access & Visibility Type <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check p-3 border rounded-3 h-100 bg-light-subtle">
                                        <input class="form-check-input ms-0 me-2" type="radio" name="video_access_type" id="accessPrivate" value="private" {{ old('video_access_type', $product->video_access_type ?? 'private') === 'private' ? 'checked' : '' }} onchange="toggleVideoAccessMode()">
                                        <label class="form-check-label fw-bold text-dark" for="accessPrivate">
                                            <i class="fa-solid fa-lock text-danger me-1"></i> Private / Paid Video (Purchase or Access Required)
                                        </label>
                                        <p class="text-muted small mb-0 mt-1" style="font-size: 0.78rem;">
                                            Video locked rahegi. Student/customer payment ke baad ya admin dwara access diye jane par "See Video" button se videos access kar sakenge.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check p-3 border rounded-3 h-100 bg-light-subtle">
                                        <input class="form-check-input ms-0 me-2" type="radio" name="video_access_type" id="accessPublic" value="public" {{ old('video_access_type', $product->video_access_type) === 'public' ? 'checked' : '' }} onchange="toggleVideoAccessMode()">
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
                                <label class="form-label small fw-bold">Primary Video URL</label>
                                <input type="url" name="youtube_video_url" value="{{ old('youtube_video_url', $product->youtube_video_url) }}" class="form-control" placeholder="https://www.youtube.com/watch?v=... or Google Drive URL">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Class Duration</label>
                                <input type="text" name="duration" value="{{ old('duration', $product->duration) }}" class="form-control">
                            </div>
                            <div class="col-md-3" id="maxInvitesWrapper">
                                <label class="form-label small fw-bold">Max Gmail Invites (Quota)</label>
                                <input type="number" name="max_invites" value="{{ old('max_invites', $product->max_invites) }}" max="50" min="1" class="form-control">
                                <span class="small text-muted d-block mt-1">Current Invites: <strong>{{ $product->current_invites }}/{{ $product->max_invites }}</strong></span>
                            </div>

                            <!-- Introduction Video URL -->
                            <div class="col-md-6">
                                <label class="form-label small fw-bold"><i class="fa-solid fa-play text-info me-1"></i> Introduction Video (YouTube Link)</label>
                                <input type="url" name="intro_video_url" value="{{ old('intro_video_url', $product->intro_video_url) }}" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                                <span class="form-text small text-muted">Shown in video details overview so prospective students can preview the course.</span>
                            </div>

                            <!-- Demo Video URL -->
                            <div class="col-md-6">
                                <label class="form-label small fw-bold"><i class="fa-solid fa-circle-play text-success me-1"></i> Demo Video Link (YouTube or Drive)</label>
                                <input type="url" name="video_demo_url" id="video_demo_url" value="{{ old('video_demo_url', old('demo_url', $product->type === 'video' ? $product->demo_url : '')) }}" class="form-control" placeholder="YouTube or Drive Demo link">
                                <span class="form-text small text-muted">Triggers the prominent "Demo" button on the video card and details page.</span>
                            </div>

                            <!-- Multiple Video Links Repeater -->
                            <div class="col-12 mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label small fw-bold text-dark mb-0">
                                        <i class="fa-solid fa-film text-danger me-1"></i> Multiple Video Lectures &amp; Drive Links (Course Playlist / Modules)
                                    </label>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="addVideoLectureLink()">
                                        <i class="fa-solid fa-plus me-1"></i> Add Video / Lecture Link
                                    </button>
                                </div>
                                <div id="videoLectureLinksContainer" class="d-flex flex-column gap-2">
                                    <!-- Render dynamic rows here -->
                                </div>
                                <span class="form-text small text-muted">Add multiple videos (YouTube or Google Drive). Paid users can watch all of them using the "See Video" portal.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="form-label small fw-bold">Short Summary / Highlights</label>
                    <textarea name="short_description" rows="2" class="form-control">{{ old('short_description', $product->short_description) }}</textarea>
                </div>

                <div class="col-md-12">
                    <label class="form-label small fw-bold">Detailed Syllabus / Description</label>
                    <textarea name="description" rows="5" class="form-control">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active (Visible in Store)</option>
                        <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive / Draft</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold small" for="isFeatured">
                            Mark as Featured Product
                        </label>
                    </div>
                </div>

                <div class="col-12 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Update Product
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
        pdfFields.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
        videoFields.querySelectorAll('input, select, textarea').forEach(el => el.disabled = false);
        toggleVideoAccessMode();
    } else {
        pdfFields.classList.remove('d-none');
        videoFields.classList.add('d-none');
        pdfFields.querySelectorAll('input, select, textarea').forEach(el => el.disabled = false);
        videoFields.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
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

let pdfLinkIndex = 0;
function addPdfResourceLink(title = '', url = '') {
    const container = document.getElementById('pdfResourceLinksContainer');
    if (!container) return;
    const rowId = `pdf_link_row_${pdfLinkIndex}`;
    const html = `
        <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-3 border shadow-sm" id="${rowId}">
            <span class="badge bg-warning text-dark"><i class="fa-brands fa-google-drive"></i></span>
            <input type="text" name="resource_links[${pdfLinkIndex}][title]" value="${title.replace(/"/g, '&quot;')}" class="form-control form-control-sm" placeholder="Link Title e.g. Chapter 1-5 Drive Link" style="max-width: 260px;" required>
            <input type="url" name="resource_links[${pdfLinkIndex}][url]" value="${url.replace(/"/g, '&quot;')}" class="form-control form-control-sm" placeholder="https://drive.google.com/... or PDF URL" required>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="document.getElementById('${rowId}').remove()" title="Remove link">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    pdfLinkIndex++;
}

let videoLinkIndex = 0;
function addVideoLectureLink(title = '', url = '', platform = 'youtube') {
    const container = document.getElementById('videoLectureLinksContainer');
    if (!container) return;
    const rowId = `video_link_row_${videoLinkIndex}`;
    const html = `
        <div class="d-flex align-items-center gap-2 p-2 bg-white rounded-3 border shadow-sm" id="${rowId}">
            <select name="video_links[${videoLinkIndex}][platform]" class="form-select form-select-sm" style="max-width: 130px;">
                <option value="youtube" ${platform === 'youtube' ? 'selected' : ''}>YouTube</option>
                <option value="drive" ${platform === 'drive' ? 'selected' : ''}>Google Drive</option>
            </select>
            <input type="text" name="video_links[${videoLinkIndex}][title]" value="${title.replace(/"/g, '&quot;')}" class="form-control form-control-sm" placeholder="Lecture Title e.g. Lesson 2: Windows 11" style="max-width: 250px;" required>
            <input type="url" name="video_links[${videoLinkIndex}][url]" value="${url.replace(/"/g, '&quot;')}" class="form-control form-control-sm" placeholder="https://www.youtube.com/watch?v=... or Google Drive URL" required>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="document.getElementById('${rowId}').remove()" title="Remove video">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    videoLinkIndex++;
}

document.addEventListener('DOMContentLoaded', () => {
    toggleTypeFields();
    toggleVideoAccessMode();

    // Hydrate existing resource links
    @if(!empty($product->normalized_resource_links))
        @foreach($product->normalized_resource_links as $rLink)
            addPdfResourceLink({!! json_encode($rLink['title'] ?? '') !!}, {!! json_encode($rLink['url'] ?? '') !!});
        @endforeach
    @endif

    // Hydrate existing video links
    @if(!empty($product->normalized_video_links))
        @foreach($product->normalized_video_links as $vLink)
            addVideoLectureLink({!! json_encode($vLink['title'] ?? '') !!}, {!! json_encode($vLink['url'] ?? '') !!}, {!! json_encode($vLink['platform'] ?? 'youtube') !!});
        @endforeach
    @endif
});
</script>
@endpush
