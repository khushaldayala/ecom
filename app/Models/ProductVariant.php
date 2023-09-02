<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }

    public function variantOptions()
    {
        return $this->belongsTo(VariantOption::class, 'variant_option_id');
    }
    public function productVariantImages()
    {
        return $this->hasMany(ProductVariantImage::class);
    }
}
