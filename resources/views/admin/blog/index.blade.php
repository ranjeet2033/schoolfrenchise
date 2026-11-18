@extends('layouts.admin')

@section('title', 'Manage Blog Posts - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-newspaper text-primary me-2"></i>Blog Posts &amp; Articles</h3>
        <p class="text-muted small mb-0">Publish announcements, career articles, and educational updates for students and centres.</p>
    </div>
    <a href="{{ route('admin.blog.create') }}" class="btn btn-primary rounded-3 px-3 shadow-sm fw-semibold">
        <i class="fa-solid fa-pen-to-square me-1"></i> Write New Blog Post
    </a>
</div>

<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $post->featured_image_url }}" alt="" class="rounded-2" style="width: 44px; height: 44px; object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('images/courses/default-course.svg') }}';">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $post->title }}</div>
                                        <small class="text-muted font-monospace">{{ $post->slug }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary-subtle text-secondary">{{ $post->category ?? 'General' }}</span></td>
                            <td>{{ $post->author_name }}</td>
                            <td>{{ $post->created_at->format('d M, Y') }}</td>
                            <td>
                                @if($post->is_published)
                                    <span class="badge bg-success-subtle text-success">Published</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">Draft</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-2 me-1" title="View Article"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                                <a href="{{ route('admin.blog.edit', $post) }}" class="btn btn-sm btn-outline-primary rounded-2 me-1" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-2" type="submit" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-regular fa-newspaper fs-1 text-black-50 mb-3 d-block"></i>
                                <h5>No Blog Posts Yet</h5>
                                <p class="small text-muted mb-3">Click below to author your first blog post or news announcement.</p>
                                <a href="{{ route('admin.blog.create') }}" class="btn btn-primary btn-sm">Write Blog Post</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
