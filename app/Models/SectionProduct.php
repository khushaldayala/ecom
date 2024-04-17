<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionProduct extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'product_id', 'user_id'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class)->select('id', 'title');
    }
}
