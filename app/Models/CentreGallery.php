<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentreGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'centre_id',
        'image_path',
        'caption',
        'category',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
