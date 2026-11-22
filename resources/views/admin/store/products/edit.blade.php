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
                        <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-file-pdf text-danger me-1"></i> PDF File Details</h6>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-bold">Replace PDF File</label>
                                <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
                                @if($product->pdf_file_path)
                                    <span class="small text-success d-block mt-1"><i class="fa-solid fa-circle-check me-1"></i> Existing PDF is safely stored.</span>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Total Pages Count</label>
                                <input type="number" name="total_pages" value="{{ old('total_pages', $product->total_pages) }}" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video Specific Fields -->
                <div id="videoFields" class="col-12 d-none">
                    <div class="p-3 bg-light rounded-3 border">
                        <h6 class="fw-bold text-dark mb-3"><i class="fa-brands fa-youtube text-danger me-1"></i> YouTube Private Video Configuration</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">YouTube Video URL</label>
                                <input type="url" name="youtube_video_url" value="{{ old('youtube_video_url', $product->youtube_video_url) }}" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Class Duration</label>
                                <input type="text" name="duration" value="{{ old('duration', $product->duration) }}" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Max Gmail Invites (Quota)</label>
                                <input type="number" name="max_invites" value="{{ old('max_invites', $product->max_invites) }}" max="50" min="1" class="form-control">
                                <span class="small text-muted d-block mt-1">Current Invites: <strong>{{ $product->current_invites }}/{{ $product->max_invites }}</strong></span>
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
    } else {
        pdfFields.classList.remove('d-none');
        videoFields.classList.add('d-none');
    }
}
document.addEventListener('DOMContentLoaded', toggleTypeFields);
</script>
@endpush
