<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'course_code',
        'course_name',
        'slug',
        'duration',
        'eligibility',
        'default_course_fee',
        'registration_fee',
        'exam_fee',
        'certificate_fee',
        'syllabus',
        'thumbnail',
        'is_popular',
        'status',
    ];

    protected $casts = [
        'is_popular' => 'boolean',
        'status' => 'boolean',
        'default_course_fee' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    public function subjects()
    {
        return $this->hasMany(CourseSubject::class)->orderBy('sort_order');
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function feeStructures()
    {
        return $this->hasMany(FeeStructure::class);
    }

    public function admissionApplications()
    {
        return $this->hasMany(AdmissionApplication::class);
    }

    public function studyMaterials()
    {
        return $this->hasMany(StudyMaterial::class);
    }

    public function getTitleAttribute()
    {
        return $this->course_name;
    }

    public function getTotalFeeAttribute()
    {
        return (float) ($this->default_course_fee + $this->registration_fee + $this->exam_fee + $this->certificate_fee);
    }

    public function getCourseFeeAttribute()
    {
        return (float) ($this->default_course_fee ?: $this->total_fee ?: 0);
    }

    public function getDurationMonthsAttribute(): int
    {
        $dur = strtolower(trim((string) $this->duration));
        if (preg_match('/(\d+)\s*year/i', $dur, $m)) {
            return ((int) $m[1]) * 12;
        }
        if (preg_match('/(\d+)\s*month/i', $dur, $m)) {
            return (int) $m[1];
        }
        if (preg_match('/(\d+)/', $dur, $m)) {
            $val = (int) $m[1];
            if (str_contains($dur, 'yr')) {
                return $val * 12;
            }
            return ($val <= 2) ? $val * 12 : $val;
        }
        return 6;
    }

    public function calculateIssueDate(?string $admissionDate): string
    {
        if (!$admissionDate) {
            return now()->toDateString();
        }
        return \Carbon\Carbon::parse($admissionDate)->addMonths($this->duration_months)->toDateString();
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function getThumbnailUrlAttribute(): string
    {
        // 1. If custom thumbnail is set and actually exists on disk or is remote URL
        if (!empty($this->thumbnail)) {
            if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
                return $this->thumbnail;
            }

            if (file_exists(public_path($this->thumbnail))) {
                return asset($this->thumbnail);
            }

            $storageRel = 'storage/' . ltrim($this->thumbnail, '/');
            if (file_exists(public_path($storageRel)) || file_exists(storage_path('app/public/' . ltrim($this->thumbnail, '/')))) {
                return asset($storageRel);
            }
        }

        // 2. Intelligent matching with curated course vector illustrations
        $haystack = strtolower(($this->course_code ?? '') . ' ' . ($this->course_name ?? ''));

        if (str_contains($haystack, 'adca')) {
            return asset('images/courses/adca.svg');
        }
        if (str_contains($haystack, 'adfa') || str_contains($haystack, 'dfa') || str_contains($haystack, 'financial accounting')) {
            return asset('images/courses/dfa.svg');
        }
        if (str_contains($haystack, 'tally')) {
            return asset('images/courses/tally-gst.svg');
        }
        if (str_contains($haystack, 'steno')) {
            return asset('images/courses/steno.svg');
        }
        if (str_contains($haystack, 'typing')) {
            return asset('images/courses/cct-eh.svg');
        }
        if (str_contains($haystack, 'dca') || str_contains($haystack, 'dcaa')) {
            return asset('images/courses/dca.svg');
        }
        if (str_contains($haystack, 'bcca') || str_contains($haystack, 'bcc')) {
            return asset('images/courses/bcc.svg');
        }
        if (str_contains($haystack, 'ccc')) {
            return asset('images/courses/ccc.svg');
        }
        if (str_contains($haystack, 'web') || str_contains($haystack, 'dwd')) {
            return asset('images/courses/web-dev.svg');
        }
        if (str_contains($haystack, 'dtp')) {
            return asset('images/courses/dtp.svg');
        }
        if (str_contains($haystack, 'python')) {
            return asset('images/courses/python.svg');
        }
        if (str_contains($haystack, 'pgdca')) {
            return asset('images/courses/pgdca.svg');
        }
        if (str_contains($haystack, 'hardware')) {
            return asset('images/courses/hardware.svg');
        }

        return asset('images/courses/default-course.svg');
    }
}
