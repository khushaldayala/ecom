<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantImage extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        // 'variant_id',
        // 'variant_id',
        // 'variant_option_id',
        // 'qty',
        // 'sku',
        // 'weight',
        // 'color',
        // 'discount_type',
        // 'off_price',
        // 'off_percentage',
        // 'original_price'
        'product_variant_id',
        'image'
    ];

    public function productVariants()
    {
        return $this->belongsTo(ProductVariant::class,'product_variant_id');
    }
}
