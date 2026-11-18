@extends('layouts.admin')

@section('title', 'Write New Blog Post - Admin Panel')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Blog List
    </a>
    <h3 class="fw-bold text-dark mb-0">Write New Blog Post</h3>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4 mb-4">
            <div class="col-md-8">
                <label class="form-label fw-semibold">Article Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Scope of ADCA Computer Course in 2026" value="{{ old('title') }}" required>
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Category</label>
                <input type="text" name="category" class="form-control" placeholder="e.g. Courses, Career, Tech" value="{{ old('category', 'Courses') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Author Name</label>
                <input type="text" name="author_name" class="form-control" placeholder="e.g. Tech Era Team" value="{{ old('author_name', 'Tech Era Team') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Featured Image</label>
                <input type="file" name="featured_image" class="form-control" accept="image/*">
                <small class="text-muted">Recommended size: 1200x630 (JPEG, PNG, WebP)</small>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Short Summary / Description</label>
                <textarea name="summary" class="form-control" rows="2" placeholder="Brief 1-2 sentence overview of the article...">{{ old('summary') }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Full Article Content <span class="text-danger">*</span></label>
                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="12" placeholder="Write full details, paragraphs, headings, bullet points..." required>{{ old('content') }}</textarea>
                @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishCheck" checked>
                    <label class="form-check-label fw-semibold" for="publishCheck">Publish article immediately on website</label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-3 fw-bold shadow-sm">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Save &amp; Publish Blog Post
        </button>
    </form>
</div>
@endsection
