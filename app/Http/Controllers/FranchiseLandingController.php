<?php

namespace App\Http\Controllers;

use App\Models\Centre;
use App\Models\CentreLandingPage;
use App\Models\Course;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class FranchiseLandingController extends Controller
{
    /**
     * Show dedicated franchise landing page by centre_code (e.g. /c/DCE-BI825).
     */
    public function showByCode($code)
    {
        $centre = Centre::with(['landingPage', 'banners' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order');
        }, 'galleries' => function ($q) {
            $q->orderBy('sort_order');
        }, 'state', 'district'])
        ->where('centre_code', $code)
        ->firstOrFail();

        return $this->renderLandingPage($centre);
    }

    /**
     * Show dedicated franchise landing page by slug (e.g. /centre/patna-computer-centre).
     */
    public function showBySlug($slug)
    {
        $landingPage = CentreLandingPage::where('slug', $slug)
            ->where('is_published', true)
            ->first();

        if (!$landingPage) {
            // Check if slug matches centre_code
            $centre = Centre::where('centre_code', $slug)->firstOrFail();
            return $this->renderLandingPage($centre);
        }

        $centre = $landingPage->centre;
        $centre->load(['landingPage', 'banners' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order');
        }, 'galleries' => function ($q) {
            $q->orderBy('sort_order');
        }, 'state', 'district']);

        return $this->renderLandingPage($centre);
    }

    /**
     * Common render logic for franchise landing page.
     */
    protected function renderLandingPage(Centre $centre)
    {
        $landingPage = $centre->landingPage;

        // Auto-create basic landing page settings if missing
        if (!$landingPage) {
            $landingPage = CentreLandingPage::create([
                'centre_id'       => $centre->id,
                'slug'            => strtolower($centre->centre_code),
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

        $courses = Course::where('status', true)->orderBy('course_name')->get();

        return view('pages.centre-landing', compact('centre', 'landingPage', 'courses'));
    }

    /**
     * Handle fast direct admission enquiry submitted on franchise landing page.
     */
    public function submitEnquiry(Request $request, $code)
    {
        $centre = Centre::where('centre_code', $code)->firstOrFail();

        $validated = $request->validate([
            'name'       => 'required|string|max:191',
            'mobile'     => 'required|string|size:10',
            'email'      => 'nullable|email|max:191',
            'course_id'  => 'nullable|exists:courses,id',
            'message'    => 'nullable|string|max:500',
        ]);

        Enquiry::create([
            'name'        => $validated['name'],
            'mobile'      => $validated['mobile'],
            'email'       => $validated['email'] ?? null,
            'state_id'    => $centre->state_id,
            'district_id' => $centre->district_id,
            'subject'     => 'Franchise Branch Enquiry (' . $centre->centre_code . ')',
            'message'     => $validated['message'] ?? 'Enquiry submitted from Franchise Dedicated Landing Page.',
            'type'        => 'admission_enquiry',
            'status'      => 'new',
        ]);

        return back()->with('enquiry_success', 'Thank you! Your enquiry has been received by ' . $centre->centre_name . '. Our counselor will call you shortly.');
    }
}
