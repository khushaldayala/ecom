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
    
    protected $fillable = ['product_id', 'offer_id', 'name', 'discount_type', 'off_price', 'off_percentage', 'original_price', 'discount_price', 'qty', 'min_qty', 'sku', 'status'];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }

    public function attribut()
    {
        return $this->belongsTo(Attribut::class, 'attribute_id');
    }

    public function attributOption()
    {
        return $this->belongsTo(AttributOption::class, 'attribute_option_id');
    }

    public function productVariantImages()
    {
        return $this->hasMany(ProductVariantImage::class, 'product_variant_id');
    }
    
    public function productVariantAttribute()
    {
        return $this->hasMany(ProductVariantAttribute::class, 'variant_id');
    }
}
