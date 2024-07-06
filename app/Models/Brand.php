<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = ['title', 'description', 'image', 'keyword', 'status', 'link'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function section_brands()
    {
        return $this->hasMany(SectionBrand::class)->select('id', 'section_id', 'brand_id');
    }
}
