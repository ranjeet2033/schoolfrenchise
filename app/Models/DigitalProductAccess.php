<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DigitalProductAccess extends Model
{
    use HasFactory;

    protected $table = 'digital_product_access';

    protected $fillable = [
        'digital_order_id',
        'digital_product_id',
        'customer_email',
        'customer_gmail',
        'access_token',
        'youtube_invite_status',
        'youtube_invited_at',
        'admin_notes',
        'pdf_download_count',
        'last_downloaded_at',
        'status',
    ];

    protected $casts = [
        'youtube_invited_at' => 'datetime',
        'last_downloaded_at' => 'datetime',
        'pdf_download_count' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(DigitalOrder::class, 'digital_order_id');
    }

    public function product()
    {
        return $this->belongsTo(DigitalProduct::class, 'digital_product_id');
    }

    public static function generateAccessToken(): string
    {
        return Str::random(40) . time();
    }
}
