<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminDigitalProductController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $query = DigitalProduct::withCount('accesses')->orderBy('sort_order')->latest('id');

        if ($type === 'pdf') {
            $query->pdfs();
        } elseif ($type === 'video') {
            $query->videos();
        }

        $products = $query->paginate(15);
        $totalPdfs = DigitalProduct::pdfs()->count();
        $totalVideos = DigitalProduct::videos()->count();

        return view('admin.store.products.index', compact('products', 'type', 'totalPdfs', 'totalVideos'));
    }

    public function create()
    {
        return view('admin.store.products.create');
    }

    protected function cleanLinksArray($links): array
    {
        if (!is_array($links)) {
            return [];
        }
        $cleaned = [];
        foreach ($links as $item) {
            $url = trim($item['url'] ?? '');
            if (!empty($url)) {
                $cleaned[] = [
                    'title'    => trim($item['title'] ?? '') ?: 'Resource Link',
                    'url'      => $url,
                    'platform' => trim($item['platform'] ?? (str_contains($url, 'drive.google.com') ? 'drive' : 'youtube')),
                ];
            }
        }
        return $cleaned;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'type'              => 'required|in:pdf,video',
            'video_access_type' => 'nullable|in:private,public',
            'category'          => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'price'             => 'nullable|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0',
            'thumbnail'         => 'nullable|image|max:3072',
            // PDF fields
            'pdf_file'          => 'nullable|file|mimes:pdf|max:51200', // max 50MB
            'pdf_drive_link'    => 'nullable|string|max:500',
            'total_pages'       => 'nullable|integer|min:1',
            'demo_url'          => 'nullable|string|max:500',
            'pdf_demo_url'      => 'nullable|string|max:500',
            'resource_links'    => 'nullable|array',
            'resource_links.*.title' => 'nullable|string|max:191',
            'resource_links.*.url'   => 'nullable|string|max:500',
            // Video fields
            'youtube_video_id'  => 'nullable|string|max:100',
            'youtube_video_url' => 'nullable|string|max:255',
            'intro_video_url'   => 'nullable|string|max:500',
            'video_demo_url'    => 'nullable|string|max:500',
            'duration'          => 'nullable|string|max:50',
            'video_links'       => 'nullable|array',
            'video_links.*.title'    => 'nullable|string|max:191',
            'video_links.*.url'      => 'nullable|string|max:500',
            'video_links.*.platform' => 'nullable|string|max:50',
            'max_invites'       => 'nullable|integer|min:1|max:50',
            'status'            => 'required|in:active,inactive',
            'is_featured'       => 'nullable|boolean',
        ]);

        $videoAccessType = ($validated['type'] === 'video') ? ($request->input('video_access_type', 'private')) : 'private';
        $price = $videoAccessType === 'public' ? 0.00 : (float) ($validated['price'] ?? 0.00);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('store/thumbnails', 'public');
        }

        $pdfPath = null;
        if ($request->hasFile('pdf_file')) {
            // Store PDF securely in private storage
            $pdfPath = $request->file('pdf_file')->store('pdfs', 'local');
        }

        // Clean YouTube ID if full URL provided
        $ytId = $validated['youtube_video_id'] ?? null;
        if (!empty($validated['youtube_video_url']) && empty($ytId)) {
            $ytId = DigitalProduct::extractYoutubeId($validated['youtube_video_url']);
        }

        $slug = Str::slug($validated['title']);
        $count = DigitalProduct::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $demoUrl = ($validated['type'] === 'video')
            ? ($request->input('video_demo_url') ?: $request->input('demo_url'))
            : ($request->input('pdf_demo_url') ?: $request->input('demo_url'));

        DigitalProduct::create([
            'title'             => $validated['title'],
            'slug'              => $slug,
            'type'              => $validated['type'],
            'video_access_type' => $videoAccessType,
            'category'          => $validated['category'] ?? 'General',
            'short_description' => $validated['short_description'] ?? null,
            'description'       => $validated['description'] ?? null,
            'price'             => $price,
            'sale_price'        => $videoAccessType === 'public' ? null : ($validated['sale_price'] ?? null),
            'thumbnail'         => $thumbnailPath,
            'pdf_file_path'     => $pdfPath,
            'pdf_drive_link'    => $validated['pdf_drive_link'] ?? null,
            'resource_links'    => $this->cleanLinksArray($request->input('resource_links')),
            'total_pages'       => $validated['total_pages'] ?? null,
            'demo_url'          => !empty($demoUrl) ? trim($demoUrl) : null,
            'youtube_video_id'  => $ytId,
            'youtube_video_url' => $validated['youtube_video_url'] ?? null,
            'intro_video_url'   => $validated['intro_video_url'] ?? null,
            'duration'          => $validated['duration'] ?? null,
            'video_links'       => $this->cleanLinksArray($request->input('video_links')),
            'max_invites'       => $validated['max_invites'] ?? 50,
            'status'            => $validated['status'],
            'is_featured'       => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.store.products.index')->with('success', 'Digital product created successfully!');
    }

    public function edit(DigitalProduct $product)
    {
        return view('admin.store.products.edit', compact('product'));
    }

    public function update(Request $request, DigitalProduct $product)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'type'              => 'required|in:pdf,video',
            'video_access_type' => 'nullable|in:private,public',
            'category'          => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'price'             => 'nullable|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0',
            'thumbnail'         => 'nullable|image|max:3072',
            // PDF fields
            'pdf_file'          => 'nullable|file|mimes:pdf|max:51200',
            'pdf_drive_link'    => 'nullable|string|max:500',
            'total_pages'       => 'nullable|integer|min:1',
            'demo_url'          => 'nullable|string|max:500',
            'pdf_demo_url'      => 'nullable|string|max:500',
            'resource_links'    => 'nullable|array',
            'resource_links.*.title' => 'nullable|string|max:191',
            'resource_links.*.url'   => 'nullable|string|max:500',
            // Video fields
            'youtube_video_id'  => 'nullable|string|max:100',
            'youtube_video_url' => 'nullable|string|max:255',
            'intro_video_url'   => 'nullable|string|max:500',
            'video_demo_url'    => 'nullable|string|max:500',
            'duration'          => 'nullable|string|max:50',
            'video_links'       => 'nullable|array',
            'video_links.*.title'    => 'nullable|string|max:191',
            'video_links.*.url'      => 'nullable|string|max:500',
            'video_links.*.platform' => 'nullable|string|max:50',
            'max_invites'       => 'nullable|integer|min:1|max:50',
            'status'            => 'required|in:active,inactive',
            'is_featured'       => 'nullable|boolean',
        ]);

        $videoAccessType = ($validated['type'] === 'video') ? ($request->input('video_access_type', $product->video_access_type ?? 'private')) : 'private';
        $price = $videoAccessType === 'public' ? 0.00 : (float) ($validated['price'] ?? 0.00);

        if ($request->hasFile('thumbnail')) {
            $product->thumbnail = $request->file('thumbnail')->store('store/thumbnails', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            $product->pdf_file_path = $request->file('pdf_file')->store('pdfs', 'local');
        }

        $ytId = $validated['youtube_video_id'] ?? $product->youtube_video_id;
        if (!empty($validated['youtube_video_url'])) {
            $parsedYtId = DigitalProduct::extractYoutubeId($validated['youtube_video_url']);
            if ($parsedYtId) {
                $ytId = $parsedYtId;
            }
        }

        $demoUrl = ($validated['type'] === 'video')
            ? ($request->input('video_demo_url') ?: $request->input('demo_url'))
            : ($request->input('pdf_demo_url') ?: $request->input('demo_url'));

        $product->update([
            'title'             => $validated['title'],
            'type'              => $validated['type'],
            'video_access_type' => $videoAccessType,
            'category'          => $validated['category'] ?? $product->category,
            'short_description' => $validated['short_description'],
            'description'       => $validated['description'],
            'price'             => $price,
            'sale_price'        => $videoAccessType === 'public' ? null : ($validated['sale_price'] ?? null),
            'pdf_drive_link'    => $validated['pdf_drive_link'] ?? null,
            'total_pages'       => $validated['total_pages'] ?? $product->total_pages,
            'demo_url'          => !empty($demoUrl) ? trim($demoUrl) : null,
            'resource_links'    => $this->cleanLinksArray($request->input('resource_links')),
            'youtube_video_id'  => $ytId,
            'youtube_video_url' => $validated['youtube_video_url'] ?? $product->youtube_video_url,
            'intro_video_url'   => $validated['intro_video_url'] ?? null,
            'duration'          => $validated['duration'] ?? $product->duration,
            'video_links'       => $this->cleanLinksArray($request->input('video_links')),
            'max_invites'       => $validated['max_invites'] ?? $product->max_invites,
            'status'            => $validated['status'],
            'is_featured'       => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.store.products.index')->with('success', 'Digital product updated successfully!');
    }

    public function destroy(DigitalProduct $product)
    {
        $product->delete();
        return redirect()->route('admin.store.products.index')->with('success', 'Digital product deleted successfully.');
    }
}
