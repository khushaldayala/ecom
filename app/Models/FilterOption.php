<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FilterOption extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = ['filter_id', 'value1', 'value2'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function filters()
    {
        return $this->belongsTo(Filter::class);
    }
}
