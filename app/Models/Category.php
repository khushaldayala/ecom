<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, Subcategory::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    public function subcategory()
    {
        return $this->hasMany(Subcategory::class);
    }
    public function fabric()
    {
        return $this->hasMany(Fabric::class);
    }
    public function section_categories()
    {
        return $this->hasMany(SectionCategory::class)->select('id', 'section_id', 'category_id');
    }
}
