<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributOption extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = ['attribut_id', 'value1', 'value2'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function attribute()
    {
        return $this->belongsTo(Attribut::class);
    }
}
