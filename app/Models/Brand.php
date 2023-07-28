<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section;

class Brand extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
