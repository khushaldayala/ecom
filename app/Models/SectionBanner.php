<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionBanner extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'banner_id', 'user_id'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class)->select('id', 'title');
    }

    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }
}
