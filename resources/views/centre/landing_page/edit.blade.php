@extends('layouts.centre')

@section('title', 'Manage Dedicated Landing Page - ' . ($instituteSettings['institute_name'] ?? 'TECH ERA COMPUTER'))

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h3 class="fw-bold text-dark mb-1 fs-5 fs-md-4">
            <i class="fa-solid fa-paintbrush text-primary me-2"></i>Dedicated Landing Page Customizer
        </h3>
        <p class="text-muted small mb-0">Customize your branch website theme, upload banners, set facilities, and manage your photo gallery.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ $centre->landing_page_url }}" target="_blank" class="btn btn-outline-primary rounded-3 px-3 py-2 fw-semibold shadow-sm">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Live Landing Page
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- URL Highlight Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary text-white">
    <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <span class="badge bg-warning text-dark px-2 py-1 small fw-bold mb-1">YOUR EXCLUSIVE DEDICATED URL</span>
            <h5 class="fw-bold mb-1 font-monospace">{{ $centre->landing_page_url }}</h5>
            <span class="small opacity-85">Share this link with your prospective students on social media, WhatsApp, or printed brochures.</span>
        </div>
        <div>
            <a href="{{ $centre->landing_page_url }}" target="_blank" class="btn btn-light text-primary fw-bold rounded-pill px-4 shadow">
                <i class="fa-solid fa-globe me-1"></i> Open Website
            </a>
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-pills mb-4 bg-white p-2 rounded-4 shadow-sm border" id="customizerTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill fw-semibold" id="basic-tab" data-bs-toggle="pill" data-bs-target="#basic" type="button" role="tab">
            <i class="fa-solid fa-palette me-1"></i> Theme & Brand
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill fw-semibold" id="banners-tab" data-bs-toggle="pill" data-bs-target="#banners" type="button" role="tab">
            <i class="fa-solid fa-images me-1"></i> Sliders & Banners ({{ $centre->banners->count() }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill fw-semibold" id="about-tab" data-bs-toggle="pill" data-bs-target="#about" type="button" role="tab">
            <i class="fa-solid fa-user-tie me-1"></i> About & Director
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill fw-semibold" id="facilities-tab" data-bs-toggle="pill" data-bs-target="#facilities" type="button" role="tab">
            <i class="fa-solid fa-list-check me-1"></i> Facilities
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill fw-semibold" id="gallery-tab" data-bs-toggle="pill" data-bs-target="#gallery" type="button" role="tab">
            <i class="fa-solid fa-camera-retro me-1"></i> Photo Gallery ({{ $centre->galleries->count() }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill fw-semibold" id="contact-tab" data-bs-toggle="pill" data-bs-target="#contact" type="button" role="tab">
            <i class="fa-solid fa-map-location-dot me-1"></i> Contact & Google Map
        </button>
    </li>
</ul>

<!-- Tab Contents -->
<div class="tab-content" id="customizerTabContent">

    <!-- 1. Theme & Brand Settings -->
    <div class="tab-pane fade show active" id="basic" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-sliders text-primary me-2"></i> Branding & Color Customization</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('centre.landing.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Header Centre Name (Brand Title)</label>
                            <input type="text" name="custom_centre_name" value="{{ old('custom_centre_name', $landingPage->custom_centre_name ?? $centre->centre_name) }}" class="form-control" placeholder="e.g. {{ $centre->centre_name }}">
                            <span class="form-text small text-muted">Displays in the top navbar and header brand area. Leave empty to use default.</span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Header Subtitle / Campus Location</label>
                            <input type="text" name="header_subtext" value="{{ old('header_subtext', $landingPage->header_subtext ?? ('Code: ' . $centre->centre_code . ' | ' . $centre->city)) }}" class="form-control" placeholder="e.g. Code: {{ $centre->centre_code }} | {{ $centre->city }}">
                            <span class="form-text small text-muted">Sub-line under your centre name in the top navbar.</span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Custom URL Slug <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted small">{{ url('/branch') }}/</span>
                                <input type="text" name="slug" value="{{ old('slug', $landingPage->slug) }}" class="form-control" required placeholder="e.g. patna-centre">
                            </div>
                            <span class="form-text small text-muted">Use lowercase letters, numbers, and hyphens only.</span>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Hero Tagline / Punchline</label>
                            <input type="text" name="tagline" value="{{ old('tagline', $landingPage->tagline) }}" class="form-control" placeholder="e.g. Empowering Youth with Vocational Skills">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Primary Brand Color</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="theme_color" value="{{ old('theme_color', $landingPage->theme_color ?? '#0d2238') }}" class="form-control form-control-color" style="width: 50px;">
                                <input type="text" class="form-control font-monospace" value="{{ old('theme_color', $landingPage->theme_color ?? '#0d2238') }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Accent Color</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="accent_color" value="{{ old('accent_color', $landingPage->accent_color ?? '#8f9f1b') }}" class="form-control form-control-color" style="width: 50px;">
                                <input type="text" class="form-control font-monospace" value="{{ old('accent_color', $landingPage->accent_color ?? '#8f9f1b') }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Custom Branch Logo (Navbar Header)</label>
                            <input type="file" name="custom_logo" class="form-control" accept="image/*">
                            @if($landingPage->custom_logo)
                                <div class="mt-2 d-flex align-items-center gap-3 p-2 bg-light rounded-3 border">
                                    <img src="{{ $landingPage->effective_logo_url }}" alt="Logo" height="40" class="rounded border p-1 bg-white">
                                    <div>
                                        <span class="small fw-bold text-dark d-block">Current Custom Logo</span>
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="remove_custom_logo" value="1" id="removeLogoCheck">
                                            <label class="form-check-label small text-danger fw-semibold" for="removeLogoCheck">
                                                Remove custom logo & use default institute logo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Branding Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. Sliders & Banners -->
    <div class="tab-pane fade" id="banners" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-plus-circle text-primary me-2"></i> Add New Slide / Banner</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('centre.landing.banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Banner Image <span class="text-danger">*</span> (Recommended: 1600x600 px)</label>
                            <input type="file" name="banner_image" class="form-control" required accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Slide Headline</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Admissions Open For New Batch">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Subtitle / Description</label>
                            <input type="text" name="subtitle" class="form-control" placeholder="e.g. Join professional ADCA & DCA programs">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Button Text</label>
                            <input type="text" name="button_text" class="form-control" placeholder="e.g. Join Now">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Button Link</label>
                            <input type="text" name="button_link" class="form-control" placeholder="#enquiry">
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                                <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Slide
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Existing Banners List -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h6 class="fw-bold mb-0 text-dark">Active Banners ({{ $centre->banners->count() }})</h6>
            </div>
            <div class="card-body p-4">
                @if($centre->banners->isEmpty())
                    <p class="text-muted mb-0 text-center py-3">No custom banner slides uploaded yet. The default hero banner will display on your landing page.</p>
                @else
                    <div class="row g-3">
                        @foreach($centre->banners as $banner)
                            <div class="col-md-6">
                                <div class="card border rounded-3 overflow-hidden">
                                    <img src="{{ $banner->image_url }}" alt="Slide" class="w-100" style="height: 180px; object-fit: cover;">
                                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark">{{ $banner->title ?? 'Untitled Slide' }}</h6>
                                            <span class="small text-muted">{{ $banner->subtitle }}</span>
                                        </div>
                                        <form action="{{ route('centre.landing.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Remove this banner slide?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle" style="width: 34px; height: 34px;">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 3. About & Director Profile -->
    <div class="tab-pane fade" id="about" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-building-columns text-primary me-2"></i> About Campus & Director Profile</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('centre.landing.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="slug" value="{{ $landingPage->slug }}">
                    <input type="hidden" name="theme_color" value="{{ $landingPage->theme_color }}">
                    <input type="hidden" name="accent_color" value="{{ $landingPage->accent_color }}">

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">About Section Heading</label>
                            <input type="text" name="about_heading" value="{{ old('about_heading', $landingPage->about_heading) }}" class="form-control" placeholder="e.g. Welcome to TECH ERA COMPUTER Study Centre">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold">About Content / Overview</label>
                            <textarea name="about_content" rows="4" class="form-control" placeholder="Describe your study centre, achievements, infrastructure, and teaching methodology...">{{ old('about_content', $landingPage->about_content) }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Director / Head Name</label>
                            <input type="text" name="owner_name" value="{{ old('owner_name', $landingPage->owner_name ?? $centre->director_name) }}" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Designation / Title</label>
                            <input type="text" name="owner_title" value="{{ old('owner_title', $landingPage->owner_title ?? 'Centre Director / Head') }}" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Director Photo</label>
                            <input type="file" name="owner_photo" class="form-control" accept="image/*">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Director's Message / Vision</label>
                            <textarea name="owner_message" rows="2" class="form-control" placeholder="Inspiring quote or personal message from the Centre Director...">{{ old('owner_message', $landingPage->owner_message) }}</textarea>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save About & Director Details
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 4. Facilities -->
    <div class="tab-pane fade" id="facilities" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-award text-primary me-2"></i> Facilities & Amenities</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('centre.landing.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="slug" value="{{ $landingPage->slug }}">
                    <input type="hidden" name="theme_color" value="{{ $landingPage->theme_color }}">
                    <input type="hidden" name="accent_color" value="{{ $landingPage->accent_color }}">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Facilities List (Enter one facility per line)</label>
                        <textarea name="facilities_text" rows="8" class="form-control font-monospace" placeholder="1:1 Computer Ratio&#10;High Speed Wi-Fi&#10;Air Conditioned Lab&#10;Free Study Books & Note Sheets&#10;Government Exam Typing Lab&#10;Online Mock Tests">{{ is_array($landingPage->facilities) ? implode("\n", $landingPage->facilities) : '' }}</textarea>
                        <span class="form-text small text-muted">Each line will become a featured facility badge on your public landing page.</span>
                    </div>

                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Facilities
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- 5. Photo Gallery -->
    <div class="tab-pane fade" id="gallery" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-camera text-primary me-2"></i> Upload Campus / Lab Photo</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('centre.landing.gallery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Select Photo <span class="text-danger">*</span></label>
                            <input type="file" name="gallery_image" class="form-control" required accept="image/*">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Caption (Optional)</label>
                            <input type="text" name="caption" class="form-control" placeholder="e.g. Computer Lab A / Typing Lab">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Category</label>
                            <select name="category" class="form-select">
                                <option value="Lab">Computer Lab</option>
                                <option value="Classroom">Theory Classroom</option>
                                <option value="Events">Annual Events / Exams</option>
                                <option value="Awards">Certificates & Awards</option>
                            </select>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                                <i class="fa-solid fa-cloud-arrow-up me-1"></i> Add To Gallery
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h6 class="fw-bold mb-0 text-dark">Uploaded Photos ({{ $centre->galleries->count() }})</h6>
            </div>
            <div class="card-body p-4">
                @if($centre->galleries->isEmpty())
                    <p class="text-muted mb-0 text-center py-3">No photos uploaded yet.</p>
                @else
                    <div class="row g-3">
                        @foreach($centre->galleries as $photo)
                            <div class="col-6 col-md-3">
                                <div class="card border rounded-3 overflow-hidden position-relative">
                                    <img src="{{ $photo->image_url }}" alt="Gallery" class="w-100" style="height: 140px; object-fit: cover;">
                                    <div class="p-2 bg-light d-flex justify-content-between align-items-center">
                                        <span class="small text-truncate" style="max-width: 120px;">{{ $photo->caption ?? $photo->category }}</span>
                                        <form action="{{ route('centre.landing.gallery.destroy', $photo->id) }}" method="POST" onsubmit="return confirm('Delete this photo?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm p-1 border-0">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 6. Contact & Google Map -->
    <div class="tab-pane fade" id="contact" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-location-dot text-primary me-2"></i> Contact Details, WhatsApp & Google Map</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('centre.landing.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="slug" value="{{ $landingPage->slug }}">
                    <input type="hidden" name="theme_color" value="{{ $landingPage->theme_color }}">
                    <input type="hidden" name="accent_color" value="{{ $landingPage->accent_color }}">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Public Phone Number</label>
                            <input type="text" name="custom_phone" value="{{ old('custom_phone', $landingPage->custom_phone ?? $centre->mobile) }}" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">WhatsApp Direct Number (10 Digits)</label>
                            <input type="text" name="custom_whatsapp" value="{{ old('custom_whatsapp', $landingPage->custom_whatsapp ?? $centre->mobile) }}" class="form-control" placeholder="9876543210">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Public Support Email</label>
                            <input type="email" name="custom_email" value="{{ old('custom_email', $landingPage->custom_email ?? $centre->email) }}" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Display Address</label>
                            <textarea name="address_display" rows="2" class="form-control">{{ old('address_display', $landingPage->address_display ?? $centre->address) }}</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Google Map Embed Code (Iframe)</label>
                            <textarea name="google_map_embed" rows="3" class="form-control font-monospace small" placeholder='<iframe src="https://www.google.com/maps/embed?..." width="600" height="450" ...></iframe>'>{{ old('google_map_embed', $landingPage->google_map_embed) }}</textarea>
                            <span class="form-text small text-muted">Open Google Maps, search your centre address, click "Share" -> "Embed a map", and paste the `&lt;iframe&gt;` code here.</span>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Facebook Page URL</label>
                            <input type="url" name="facebook" value="{{ $landingPage->social_links['facebook'] ?? '' }}" class="form-control" placeholder="https://facebook.com/...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Instagram URL</label>
                            <input type="url" name="instagram" value="{{ $landingPage->social_links['instagram'] ?? '' }}" class="form-control" placeholder="https://instagram.com/...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">YouTube Channel URL</label>
                            <input type="url" name="youtube" value="{{ $landingPage->social_links['youtube'] ?? '' }}" class="form-control" placeholder="https://youtube.com/@...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Telegram Channel URL</label>
                            <input type="url" name="telegram" value="{{ $landingPage->social_links['telegram'] ?? '' }}" class="form-control" placeholder="https://t.me/...">
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Contact & Location Details
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
