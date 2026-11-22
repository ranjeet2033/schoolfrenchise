<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentreLandingPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'centre_id',
        'slug',
        'tagline',
        'theme_color',
        'accent_color',
        'custom_logo',
        'about_heading',
        'about_content',
        'owner_name',
        'owner_title',
        'owner_message',
        'owner_photo',
        'facilities',
        'custom_phone',
        'custom_whatsapp',
        'custom_email',
        'address_display',
        'google_map_embed',
        'social_links',
        'custom_sections',
        'is_published',
    ];

    protected $casts = [
        'facilities' => 'array',
        'social_links' => 'array',
        'custom_sections' => 'array',
        'is_published' => 'boolean',
    ];

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function banners()
    {
        return $this->hasMany(CentreBanner::class, 'centre_id', 'centre_id')->orderBy('sort_order');
    }

    public function galleries()
    {
        return $this->hasMany(CentreGallery::class, 'centre_id', 'centre_id')->orderBy('sort_order');
    }

    public function getEffectiveLogoUrlAttribute(): string
    {
        if (!empty($this->custom_logo)) {
            return asset('storage/' . $this->custom_logo);
        }
        return $this->centre?->centre_logo_url ?? asset('images/tech-era-logo.png');
    }

    public function getEffectiveOwnerPhotoUrlAttribute(): string
    {
        if (!empty($this->owner_photo)) {
            return asset('storage/' . $this->owner_photo);
        }
        return $this->centre?->director_photo_url ?? asset('images/default-avatar.png');
    }
}
