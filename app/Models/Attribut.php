<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribut extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function attributes()
    {
        return $this->hasMany(AttributOption::class);
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class, 'attribute_id', 'id');
    }

    public function attributeCategories()
    {
        return $this->hasMany(AttributeCategory::class, 'attribute_id', 'id')->select('id', 'category_id', 'attribute_id');
    }

    public function attributeSubCategories()
    {
        return $this->hasMany(AttributeSubCategory::class, 'attribute_id', 'id')->select('id', 'sub_category_id', 'attribute_id');
    }
}
