<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentreBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'centre_id',
        'image_path',
        'title',
        'subtitle',
        'button_text',
        'button_link',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return asset('images/banners/banner1.jpg');
        }
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }
        if (file_exists(public_path($this->image_path))) {
            return asset($this->image_path);
        }
        return asset('storage/' . ltrim($this->image_path, '/'));
    }
}
