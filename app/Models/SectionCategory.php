<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionCategory extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'category_id', 'user_id'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class)->select('id', 'title');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
