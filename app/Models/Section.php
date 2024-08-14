<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Advertise;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Brand;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    
    protected $fillable = ['user_id', 'title', 'description', '	keywords', 'keyword_option', 'end_point', 'order', 'dlink', 'status'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function advertise()
    {
        return $this->hasMany(Advertise::class);
    }
    public function banner()
    {
        return $this->hasMany(Banner::class);
    }
    public function category()
    {
        return $this->hasMany(Category::class);
    }
    public function offer()
    {
        return $this->hasMany(Offer::class);
    }
    public function brand()
    {
        return $this->hasMany(Brand::class);
    }

    public function section_banners()
    {
        return $this->hasMany(SectionBanner::class)->select('id', 'section_id', 'banner_id');
    }

    public function section_categories()
    {
        return $this->hasMany(SectionCategory::class)->select('id', 'section_id', 'category_id');
    }

    public function section_brands()
    {
        return $this->hasMany(SectionBrand::class)->select('id', 'section_id', 'brand_id');
    }

    public function section_advertise()
    {
        return $this->hasMany(SectionAdvertise::class)->select('id', 'section_id', 'advertise_id');
    }

    public function section_offers()
    {
        return $this->hasMany(SectionOffer::class)->select('id', 'section_id', 'offer_id');
    }

    public function section_products()
    {
        return $this->hasMany(SectionProduct::class)->select('id', 'section_id', 'product_id');
    }
 
}
