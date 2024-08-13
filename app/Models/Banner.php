<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = ['user_id', 'title', 'description', 'target', 'target_value', 'image', 'showtype', 'status', 'schedule_start_date', 'schedule_end_date'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function section_banners()
    {
        return $this->hasMany(SectionBanner::class)->select('id', 'section_id', 'banner_id');
    }
}
