<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionBrand extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'brand_id', 'user_id'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class)->select('id', 'title');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
