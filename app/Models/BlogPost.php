<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    protected $table = 'blog_posts';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'summary',
        'content',
        'featured_image',
        'author_name',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title) . '-' . rand(1000, 9999);
            }
            if (empty($post->published_at)) {
                $post->published_at = now();
            }
        });
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (!$this->featured_image) {
            return asset('images/courses/default-course.svg');
        }
        if (str_starts_with($this->featured_image, 'http')) {
            return $this->featured_image;
        }
        return asset('storage/' . $this->featured_image);
    }
}
