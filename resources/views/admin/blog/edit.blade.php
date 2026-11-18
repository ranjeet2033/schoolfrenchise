@extends('layouts.admin')

@section('title', 'Edit Blog Post - Admin Panel')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Blog List
    </a>
    <h3 class="fw-bold text-dark mb-0">Edit Blog Post</h3>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
    <form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-4 mb-4">
            <div class="col-md-8">
                <label class="form-label fw-semibold">Article Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}" required>
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Category</label>
                <input type="text" name="category" class="form-control" value="{{ old('category', $post->category) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Author Name</label>
                <input type="text" name="author_name" class="form-control" value="{{ old('author_name', $post->author_name) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Featured Image</label>
                @if($post->featured_image)
                    <div class="mb-2">
                        <img src="{{ $post->featured_image_url }}" alt="" class="rounded" style="height: 60px;">
                    </div>
                @endif
                <input type="file" name="featured_image" class="form-control" accept="image/*">
                <small class="text-muted">Leave empty to keep current image</small>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Short Summary / Description</label>
                <textarea name="summary" class="form-control" rows="2">{{ old('summary', $post->summary) }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Full Article Content <span class="text-danger">*</span></label>
                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="12" required>{{ old('content', $post->content) }}</textarea>
                @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishCheck" {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="publishCheck">Published on website</label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-3 fw-bold shadow-sm">
            <i class="fa-solid fa-save me-2"></i> Update Blog Post
        </button>
    </form>
</div>
@endsection
