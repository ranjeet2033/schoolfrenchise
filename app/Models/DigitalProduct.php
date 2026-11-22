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
        'category',
        'short_description',
        'description',
        'price',
        'sale_price',
        'thumbnail',
        'pdf_file_path',
        'total_pages',
        'sample_pdf_path',
        'youtube_video_id',
        'youtube_video_url',
        'duration',
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

    public function getEffectivePriceAttribute(): float
    {
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
}
