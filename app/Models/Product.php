<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section;
use App\Models\Category;
use App\Models\Rating;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    
    protected $fillable = [
        'user_id',
        'category_id',
        'subcategory_id',
        'brand_id',
        'wishlist',
        'product_name',
        'description',
        'more_info',
        'status',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function addtocart()
    {
        return $this->hasMany(AddToCart::class);
    }
    public function oders()
    {
        return $this->hasMany(Order::class);
    }
    public function categories()
    {
        return $this->belongsTo(Category::class);
    }
    public function fabric()
    {
        return $this->belongsTo(Fabric::class);
    }
    public function subcategories()
    {
        return $this->belongsTo(Subcategory::class);
    }
    public function brands()
    {
        return $this->belongsTo(Brand::class);
    }
    public function section_products()
    {
        return $this->hasMany(SectionProduct::class)->select('id', 'section_id', 'product_id');
    }

    public function offer_product()
    {
        return $this->hasMany(OfferProduct::class)->select('id', 'product_id', 'offer_id');
    }
}
