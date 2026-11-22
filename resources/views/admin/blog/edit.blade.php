@extends('layouts.admin')

@section('title', 'Edit Blog Post - Admin Panel')

@push('styles')
<!-- Summernote Lite CSS for Rich Text Editing -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame {
        border: 1px solid #dee2e6 !important;
        border-radius: 10px !important;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .note-toolbar {
        background-color: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 8px 10px !important;
    }
    .note-btn {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 6px !important;
        color: #334155 !important;
        font-size: 13px !important;
        padding: 5px 9px !important;
    }
    .note-btn:hover {
        background: #f1f5f9 !important;
        color: #0f172a !important;
    }
    .note-editable {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 15px !important;
        line-height: 1.7 !important;
        color: #1e293b !important;
        background: #ffffff !important;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Blog List
    </a>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3 class="fw-bold text-dark mb-0"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Edit Blog Post</h3>
            <p class="text-muted small mb-0">Update article content, headings, images, colors, or publication status.</p>
        </div>
        <div>
            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-outline-info btn-sm rounded-pill px-3 fw-semibold">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Live on Website
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
    <form action="{{ route('admin.blog.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-4 mb-4">
            <div class="col-md-8">
                <label class="form-label fw-semibold">Article Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}" required>
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Category</label>
                <input type="text" name="category" class="form-control form-control-lg" value="{{ old('category', $post->category) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Author Name</label>
                <input type="text" name="author_name" class="form-control" value="{{ old('author_name', $post->author_name) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Featured Image</label>
                @if($post->featured_image)
                    <div class="mb-2 d-flex align-items-center gap-2">
                        <img src="{{ $post->featured_image_url }}" alt="" class="rounded border shadow-sm" style="height: 60px; max-width: 120px; object-fit: cover;">
                        <span class="badge bg-secondary-subtle text-secondary small">Current Image</span>
                    </div>
                @endif
                <input type="file" name="featured_image" class="form-control" accept="image/*">
                <small class="text-muted">Select new image to replace current one (Recommended: 1200x630)</small>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold d-flex justify-content-between align-items-center">
                    <span>Short Summary / Description <small class="text-muted fw-normal">(Shown in search results &amp; article cards)</small></span>
                    <span class="badge bg-light text-secondary border">Rich Editor Enabled</span>
                </label>
                <textarea name="summary" id="blogSummaryEditor" class="form-control">{{ old('summary', $post->summary) }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold d-flex justify-content-between align-items-center">
                    <span>Full Article Content <span class="text-danger">*</span> <small class="text-muted fw-normal">(Supports Headings H1-H3, Bold, Colors, Links, Images &amp; Tables)</small></span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Full WYSIWYG Suite</span>
                </label>
                <textarea name="content" id="blogContentEditor" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $post->content) }}</textarea>
                @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <div class="form-check form-switch p-3 bg-light rounded-3 border">
                    <input class="form-check-input ms-0 me-3" type="checkbox" name="is_published" value="1" id="publishCheck" {{ old('is_published', $post->is_published) ? 'checked' : '' }} style="width: 2.2em; height: 1.2em;">
                    <label class="form-check-label fw-semibold" for="publishCheck">Published on website (Visible in Public Blog &amp; Search)</label>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-3 fw-bold shadow-sm">
                <i class="fa-solid fa-save me-2"></i> Update Blog Post
            </button>
            <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary px-4 py-2.5 rounded-3 fw-semibold">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        // Full Content Editor
        $('#blogContentEditor').summernote({
            placeholder: 'Write your full blog post here... You can add headings (H1, H2, H3), bold text, links, images, tables, lists, and custom colors.',
            tabsize: 2,
            height: 420,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            styleTags: [
                'p',
                { title: 'Heading 1', tag: 'h1', className: 'fw-bold mb-3' },
                { title: 'Heading 2', tag: 'h2', className: 'fw-bold mb-3' },
                { title: 'Heading 3', tag: 'h3', className: 'fw-bold mb-2' },
                { title: 'Heading 4', tag: 'h4', className: 'fw-bold' },
                'blockquote',
                'pre'
            ]
        });

        // Short Summary Editor
        $('#blogSummaryEditor').summernote({
            placeholder: 'Brief summary or highlight (supports bold, links, colors)...',
            tabsize: 2,
            height: 140,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ],
            styleTags: ['p', 'blockquote', { title: 'Heading 3', tag: 'h3', className: 'fw-bold' }]
        });
    });
</script>
@endpush
