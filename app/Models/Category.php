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
        return $this->hasMany(Product::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
