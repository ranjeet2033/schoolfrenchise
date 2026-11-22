<?php

namespace App\Http\Controllers\Centre;

use App\Http\Controllers\Controller;
use App\Models\Centre;
use App\Models\CentreBanner;
use App\Models\CentreGallery;
use App\Models\CentreLandingPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CentreLandingPageController extends Controller
{
    protected function getAuthCentre(): Centre
    {
        $user = Auth::user();
        $centre = $user->centre;

        if (!$centre && $user->isSuperAdmin()) {
            $centre = Centre::where('status', 'active')->first();
        }

        if (!$centre) {
            abort(403, 'No franchise study centre is linked to your user account.');
        }

        return $centre;
    }

    /**
     * Show Landing Page Customizer Dashboard.
     */
    public function edit()
    {
        $centre = $this->getAuthCentre();
        $centre->load(['landingPage', 'banners', 'galleries']);

        $landingPage = $centre->landingPage;
        if (!$landingPage) {
            $landingPage = CentreLandingPage::create([
                'centre_id'       => $centre->id,
                'slug'            => Str::slug($centre->centre_name) . '-' . strtolower($centre->centre_code),
                'tagline'         => 'Authorized Tech Era Computer Education Centre',
                'theme_color'     => '#0d2238',
                'accent_color'    => '#8f9f1b',
                'about_heading'   => 'About ' . $centre->centre_name,
                'about_content'   => 'Welcome to ' . $centre->centre_name . '. We provide industry-standard computer vocational courses, expert faculty, modern computer labs, and 100% genuine certification.',
                'owner_name'      => $centre->director_name,
                'owner_title'     => 'Centre Director / Head',
                'owner_message'   => 'Our mission is to empower students with practical digital and technical skills to excel in career and government examinations.',
                'custom_phone'    => $centre->mobile ?? $centre->contact_phone,
                'custom_whatsapp' => $centre->mobile ?? $centre->contact_phone,
                'custom_email'    => $centre->email,
                'address_display' => $centre->address . ', ' . $centre->city . ' (' . ($centre->district?->name ?? '') . ')',
                'facilities'      => ['High-Speed Wi-Fi', '1:1 Computer Ratio', 'Air Conditioned Lab', 'Printed Study Materials', 'Doubt Clearing Sessions', 'Job Oriented Diploma'],
                'is_published'    => true,
            ]);
        }

        return view('centre.landing_page.edit', compact('centre', 'landingPage'));
    }

    /**
     * Update Landing Page Details, Theme Colors, About, Social Links, Map.
     */
    public function update(Request $request)
    {
        $centre = $this->getAuthCentre();
        $landingPage = $centre->landingPage;

        $validated = $request->validate([
            'slug'             => 'nullable|string|max:150|unique:centre_landing_pages,slug,' . ($landingPage->id ?? 0),
            'tagline'          => 'nullable|string|max:255',
            'theme_color'      => 'nullable|string|max:20',
            'accent_color'     => 'nullable|string|max:20',
            'custom_logo'      => 'nullable|image|max:2048',
            'about_heading'    => 'nullable|string|max:255',
            'about_content'    => 'nullable|string',
            'owner_name'       => 'nullable|string|max:191',
            'owner_title'      => 'nullable|string|max:191',
            'owner_message'    => 'nullable|string',
            'owner_photo'      => 'nullable|image|max:2048',
            'facilities_text'  => 'nullable|string',
            'custom_phone'     => 'nullable|string|max:50',
            'custom_whatsapp'  => 'nullable|string|max:50',
            'custom_email'     => 'nullable|email|max:191',
            'address_display'  => 'nullable|string',
            'google_map_embed' => 'nullable|string',
            'facebook'         => 'nullable|url|max:255',
            'instagram'        => 'nullable|url|max:255',
            'youtube'          => 'nullable|url|max:255',
            'twitter'          => 'nullable|url|max:255',
            'telegram'         => 'nullable|url|max:255',
            'is_published'     => 'nullable|boolean',
        ]);

        if ($request->hasFile('custom_logo')) {
            $landingPage->custom_logo = $request->file('custom_logo')->store('centres/landing/logos', 'public');
            $landingPage->save();
        }

        if ($request->hasFile('owner_photo')) {
            $landingPage->owner_photo = $request->file('owner_photo')->store('centres/landing/owners', 'public');
            $landingPage->save();
        }

        $dataToUpdate = [];

        if ($request->has('slug') && !empty($validated['slug'])) {
            $dataToUpdate['slug'] = Str::slug($validated['slug']);
        }
        if ($request->has('tagline')) {
            $dataToUpdate['tagline'] = $validated['tagline'] ?? null;
        }
        if ($request->has('theme_color') && !empty($validated['theme_color'])) {
            $dataToUpdate['theme_color'] = $validated['theme_color'];
        }
        if ($request->has('accent_color') && !empty($validated['accent_color'])) {
            $dataToUpdate['accent_color'] = $validated['accent_color'];
        }
        if ($request->has('about_heading')) {
            $dataToUpdate['about_heading'] = $validated['about_heading'] ?? null;
        }
        if ($request->has('about_content')) {
            $dataToUpdate['about_content'] = $validated['about_content'] ?? null;
        }
        if ($request->has('owner_name')) {
            $dataToUpdate['owner_name'] = $validated['owner_name'] ?? null;
        }
        if ($request->has('owner_title')) {
            $dataToUpdate['owner_title'] = $validated['owner_title'] ?? null;
        }
        if ($request->has('owner_message')) {
            $dataToUpdate['owner_message'] = $validated['owner_message'] ?? null;
        }
        if ($request->has('facilities_text')) {
            $dataToUpdate['facilities'] = array_values(array_filter(array_map('trim', preg_split('/[\r\n,]+/', (string) $request->input('facilities_text')))));
        }
        if ($request->has('custom_phone')) {
            $dataToUpdate['custom_phone'] = $validated['custom_phone'] ?? null;
        }
        if ($request->has('custom_whatsapp')) {
            $dataToUpdate['custom_whatsapp'] = $validated['custom_whatsapp'] ?? null;
        }
        if ($request->has('custom_email')) {
            $dataToUpdate['custom_email'] = $validated['custom_email'] ?? null;
        }
        if ($request->has('address_display')) {
            $dataToUpdate['address_display'] = $validated['address_display'] ?? null;
        }
        if ($request->has('google_map_embed')) {
            $dataToUpdate['google_map_embed'] = $validated['google_map_embed'] ?? null;
        }
        if ($request->has('facebook') || $request->has('instagram') || $request->has('youtube') || $request->has('twitter') || $request->has('telegram')) {
            $existingSocial = $landingPage->social_links ?? [];
            $dataToUpdate['social_links'] = [
                'facebook'  => $request->input('facebook', $existingSocial['facebook'] ?? null),
                'instagram' => $request->input('instagram', $existingSocial['instagram'] ?? null),
                'youtube'   => $request->input('youtube', $existingSocial['youtube'] ?? null),
                'twitter'   => $request->input('twitter', $existingSocial['twitter'] ?? null),
                'telegram'  => $request->input('telegram', $existingSocial['telegram'] ?? null),
            ];
        }
        if ($request->has('is_published')) {
            $dataToUpdate['is_published'] = $request->boolean('is_published');
        }

        if (!empty($dataToUpdate)) {
            $landingPage->update($dataToUpdate);
        }

        return back()->with('success', 'Your dedicated franchise landing page has been updated successfully!');
    }

    /**
     * Upload Slider Banner.
     */
    public function storeBanner(Request $request)
    {
        $centre = $this->getAuthCentre();

        $request->validate([
            'banner_image' => 'required|image|max:3072',
            'title'        => 'nullable|string|max:191',
            'subtitle'     => 'nullable|string|max:255',
            'button_text'  => 'nullable|string|max:100',
            'button_link'  => 'nullable|string|max:255',
        ]);

        $imagePath = $request->file('banner_image')->store('centres/landing/banners', 'public');

        CentreBanner::create([
            'centre_id'   => $centre->id,
            'image_path'  => $imagePath,
            'title'       => $request->input('title'),
            'subtitle'    => $request->input('subtitle'),
            'button_text' => $request->input('button_text'),
            'button_link' => $request->input('button_link'),
            'sort_order'  => (int) CentreBanner::where('centre_id', $centre->id)->count() + 1,
            'is_active'   => true,
        ]);

        return back()->with('success', 'Banner slide added successfully!');
    }

    /**
     * Delete Slider Banner.
     */
    public function destroyBanner($id)
    {
        $centre = $this->getAuthCentre();
        $banner = CentreBanner::where('centre_id', $centre->id)->findOrFail($id);
        $banner->delete();

        return back()->with('success', 'Banner slide removed.');
    }

    /**
     * Upload Photo to Gallery.
     */
    public function storeGallery(Request $request)
    {
        $centre = $this->getAuthCentre();

        $request->validate([
            'gallery_image' => 'required|image|max:3072',
            'caption'       => 'nullable|string|max:191',
            'category'      => 'nullable|string|max:100',
        ]);

        $imagePath = $request->file('gallery_image')->store('centres/landing/gallery', 'public');

        CentreGallery::create([
            'centre_id'   => $centre->id,
            'image_path'  => $imagePath,
            'caption'     => $request->input('caption'),
            'category'    => $request->input('category', 'Campus'),
            'sort_order'  => (int) CentreGallery::where('centre_id', $centre->id)->count() + 1,
        ]);

        return back()->with('success', 'Photo added to landing page gallery!');
    }

    /**
     * Delete Gallery Photo.
     */
    public function destroyGallery($id)
    {
        $centre = $this->getAuthCentre();
        $photo = CentreGallery::where('centre_id', $centre->id)->findOrFail($id);
        $photo->delete();

        return back()->with('success', 'Gallery photo deleted.');
    }
}
