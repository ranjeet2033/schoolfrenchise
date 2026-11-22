<?php

namespace App\Http\Controllers;

use App\Models\Centre;
use App\Models\CentreLandingPage;
use App\Models\Course;
use App\Models\DigitalProduct;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class FranchiseLandingController extends Controller
{
    /**
     * Show dedicated franchise landing page by centre_code or slug (e.g. /c/DCE-1001 or /c/patna-center).
     */
    public function showByCode($code)
    {
        $centre = Centre::where('centre_code', $code)
            ->orWhere('centre_code', strtoupper($code))
            ->first();

        if (!$centre) {
            $landingPage = CentreLandingPage::where('slug', $code)->first();
            if ($landingPage) {
                $centre = $landingPage->centre;
            }
        }

        if (!$centre) {
            abort(404, 'Centre landing page not found.');
        }

        $centre->load(['landingPage', 'banners' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order');
        }, 'galleries' => function ($q) {
            $q->orderBy('sort_order');
        }, 'state', 'district']);

        return $this->renderLandingPage($centre);
    }

    /**
     * Show dedicated franchise landing page by slug or code (e.g. /branch/patna-center).
     */
    public function showBySlug($slug)
    {
        $landingPage = CentreLandingPage::where('slug', $slug)->first();

        if ($landingPage && $landingPage->centre) {
            $centre = $landingPage->centre;
        } else {
            // Check if slug matches centre_code
            $centre = Centre::where('centre_code', $slug)
                ->orWhere('centre_code', strtoupper($slug))
                ->firstOrFail();
        }

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

        $courses = Course::with('category')->where('status', true)->orderBy('course_code')->get();
        $digitalProducts = DigitalProduct::active()->orderBy('sort_order')->take(8)->get();

        return view('pages.centre-landing', compact('centre', 'landingPage', 'courses', 'digitalProducts'));
    }

    /**
     * Handle fast direct admission enquiry submitted on franchise landing page.
     */
    public function submitEnquiry(Request $request, $code)
    {
        $centre = Centre::where('centre_code', $code)
            ->orWhere('centre_code', strtoupper($code))
            ->first();

        if (!$centre) {
            $landingPage = CentreLandingPage::where('slug', $code)->first();
            if ($landingPage) {
                $centre = $landingPage->centre;
            }
        }

        if (!$centre) {
            abort(404, 'Centre not found.');
        }

        $validated = $request->validate([
            'name'       => 'required|string|max:191',
            'mobile'     => 'required|string|size:10',
            'email'      => 'nullable|email|max:191',
            'course_id'  => 'nullable|exists:courses,id',
            'message'    => 'nullable|string|max:500',
        ]);

        $course = !empty($validated['course_id']) ? Course::find($validated['course_id']) : null;
        $courseTitle = $course ? $course->course_name : 'General Admission';

        Enquiry::create([
            'centre_id'   => $centre->id,
            'course_id'   => $course?->id,
            'name'        => $validated['name'],
            'mobile'      => $validated['mobile'],
            'email'       => $validated['email'] ?? null,
            'state_id'    => $centre->state_id,
            'district_id' => $centre->district_id,
            'subject'     => 'Admission Enquiry: ' . $courseTitle . ' (' . $centre->centre_code . ')',
            'message'     => $validated['message'] ?? 'Enquiry submitted from Franchise Dedicated Landing Page.',
            'type'        => 'admission_enquiry',
            'status'      => 'new',
        ]);

        return back()->with('enquiry_success', 'Thank you! Your enquiry has been submitted successfully to ' . $centre->centre_name . '. Our counselor will call you shortly.');
    }
}
