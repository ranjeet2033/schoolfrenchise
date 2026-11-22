<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'digital_order_id',
        'digital_product_id',
        'product_title',
        'product_type',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(DigitalOrder::class, 'digital_order_id');
    }

    public function product()
    {
        return $this->belongsTo(DigitalProduct::class, 'digital_product_id');
    }
}
