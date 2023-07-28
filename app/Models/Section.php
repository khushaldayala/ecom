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
}
