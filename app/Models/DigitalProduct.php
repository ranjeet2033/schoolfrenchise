<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DigitalProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'video_access_type',
        'category',
        'short_description',
        'description',
        'price',
        'sale_price',
        'thumbnail',
        'pdf_file_path',
        'pdf_drive_link',
        'resource_links',
        'total_pages',
        'sample_pdf_path',
        'demo_url',
        'youtube_video_id',
        'youtube_video_url',
        'intro_video_url',
        'duration',
        'video_links',
        'max_invites',
        'current_invites',
        'status',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'total_pages' => 'integer',
        'max_invites' => 'integer',
        'current_invites' => 'integer',
        'sort_order' => 'integer',
        'resource_links' => 'array',
        'video_links' => 'array',
    ];

    public function orderItems()
    {
        return $this->hasMany(DigitalOrderItem::class);
    }

    public function accesses()
    {
        return $this->hasMany(DigitalProductAccess::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePdfs($query)
    {
        return $query->where('type', 'pdf');
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public function isPublicVideo(): bool
    {
        return $this->type === 'video' && $this->video_access_type === 'public';
    }

    public function isPrivateVideo(): bool
    {
        return $this->type === 'video' && $this->video_access_type !== 'public';
    }

    public function isFree(): bool
    {
        return $this->isPublicVideo() || $this->effective_price <= 0;
    }

    public function getEffectivePriceAttribute(): float
    {
        if ($this->isPublicVideo()) {
            return 0.00;
        }
        return (float) ($this->sale_price !== null && $this->sale_price > 0 ? $this->sale_price : $this->price);
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->sale_price !== null && $this->sale_price > 0 && $this->sale_price < $this->price;
    }

    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->has_discount || $this->price <= 0) {
            return 0;
        }
        return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function getThumbnailUrlAttribute(): string
    {
        if (!empty($this->thumbnail)) {
            return asset('storage/' . $this->thumbnail);
        }
        return $this->type === 'video'
            ? asset('images/video-placeholder.png')
            : asset('images/pdf-placeholder.png');
    }

    public function getAvailableInvitesAttribute(): int
    {
        return max(0, $this->max_invites - $this->current_invites);
    }

    public static function extractYoutubeId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }
        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/))([\w-]{11})/', $url, $matches)) {
            return $matches[1];
        }
        if (strlen($url) === 11 && preg_match('/^[\w-]{11}$/', $url)) {
            return $url;
        }
        return null;
    }

    public function getIntroVideoIdAttribute(): ?string
    {
        return self::extractYoutubeId($this->intro_video_url);
    }

    public function getHasDemoAttribute(): bool
    {
        return !empty($this->demo_url) || !empty($this->sample_pdf_path);
    }

    public function getEffectiveDemoUrlAttribute(): ?string
    {
        if (!empty($this->demo_url)) {
            return $this->demo_url;
        }
        if (!empty($this->sample_pdf_path)) {
            return asset('storage/' . $this->sample_pdf_path);
        }
        return null;
    }

    public function getNormalizedResourceLinksAttribute(): array
    {
        $links = is_array($this->resource_links) ? $this->resource_links : [];
        return array_values(array_filter($links, fn($item) => !empty($item['url'] ?? null)));
    }

    public function getNormalizedVideoLinksAttribute(): array
    {
        $links = is_array($this->video_links) ? $this->video_links : [];
        return array_values(array_filter($links, fn($item) => !empty($item['url'] ?? null)));
    }
}
