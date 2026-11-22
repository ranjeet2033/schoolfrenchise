<?php

namespace Database\Seeders;

use App\Models\Centre;
use App\Models\CentreBanner;
use App\Models\CentreGallery;
use App\Models\CentreLandingPage;
use App\Models\DigitalProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DigitalStoreAndLandingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Sample PDF Product
        DigitalProduct::firstOrCreate(
            ['slug' => 'complete-beltron-deo-computer-notes-2026'],
            [
                'title'             => 'Complete Beltron & DEO Computer Exam Master Notes 2026',
                'type'              => 'pdf',
                'category'          => 'Computer Exam Notes',
                'short_description' => 'Comprehensive bilingual Hindi & English computer theory, shortcuts, MS Office 365, Krutidev & Mangal typing rules.',
                'description'       => "Chapter 1: Fundamentals of Computers & Operating Systems\nChapter 2: MS Word, Excel, PowerPoint Mastery with Shortcuts\nChapter 3: Networking, Cyber Security, Internet Protocols\nChapter 4: Hindi Typing (Krutidev 010 & Mangal Remington CBI) Speed Tips\nChapter 5: 1000+ Previous Year Solved Multiple Choice Questions",
                'price'             => 299.00,
                'sale_price'        => 99.00,
                'total_pages'       => 168,
                'status'            => 'active',
                'is_featured'       => true,
                'sort_order'        => 1,
            ]
        );

        // 2. Seed Sample Private YouTube Video Course
        DigitalProduct::firstOrCreate(
            ['slug' => 'master-hindi-english-high-speed-typing-masterclass'],
            [
                'title'             => 'Master Hindi (Krutidev & Mangal) & English High-Speed Typing Masterclass',
                'type'              => 'video',
                'category'          => 'Typing Video Masterclass',
                'short_description' => 'Complete high-speed typing video masterclass with dedicated keyboard finger positioning and Beltron exam simulation.',
                'description'       => "Lesson 1: Home Row, Upper Row, and Bottom Row Touch Typing\nLesson 2: Krutidev 010 Character Codes and Special Conjunctions\nLesson 3: Mangal Remington CBI Layout vs Inscript in Windows 10/11\nLesson 4: Live Speed Tracking: Crossing 35+ WPM with 95%+ Accuracy\nLesson 5: Beltron & State Govt Typing Exam Hall Test Strategies",
                'price'             => 499.00,
                'sale_price'        => 199.00,
                'youtube_video_id'  => 'dQw4w9WgXcQ',
                'youtube_video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration'          => '4 Hours 15 Mins (5 HD Lessons)',
                'max_invites'       => 50,
                'current_invites'   => 0,
                'status'            => 'active',
                'is_featured'       => true,
                'sort_order'        => 2,
            ]
        );

        // 3. Initialize Landing Pages for existing active centres
        $centres = Centre::all();
        foreach ($centres as $centre) {
            $landingPage = CentreLandingPage::firstOrCreate(
                ['centre_id' => $centre->id],
                [
                    'slug'            => Str::slug($centre->centre_name) . '-' . strtolower($centre->centre_code),
                    'tagline'         => 'Authorized Tech Era Computer Education Centre',
                    'theme_color'     => '#0d2238',
                    'accent_color'    => '#8f9f1b',
                    'about_heading'   => 'Welcome to ' . $centre->centre_name,
                    'about_content'   => $centre->centre_name . " is an authorized branch of TECH ERA COMPUTER. We are committed to providing top-notch computer education, job-oriented diplomas, and hands-on laboratory practice.",
                    'owner_name'      => $centre->director_name,
                    'owner_title'     => 'Centre Director / Head',
                    'owner_message'   => 'We ensure every student gains real-world practical skills and accredited certification to excel in career opportunities.',
                    'custom_phone'    => $centre->mobile ?? $centre->contact_phone,
                    'custom_whatsapp' => $centre->mobile ?? $centre->contact_phone,
                    'custom_email'    => $centre->email,
                    'address_display' => $centre->address . ', ' . $centre->city,
                    'facilities'      => [
                        '1:1 Dedicated Computer Ratio',
                        'High-Speed Internet / Wi-Fi',
                        'Air-Conditioned Practical Labs',
                        'Free Official Printed Study Notes',
                        'Bilingual Typing Lab (Krutidev & Mangal)',
                        'Online Practice & Mock Exams',
                    ],
                    'is_published'    => true,
                ]
            );
        }
    }
}
