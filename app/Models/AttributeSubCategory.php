<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeSubCategory extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = ['attribute_id', 'sub_category_id'];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'sub_category_id', 'id');
    }
}
