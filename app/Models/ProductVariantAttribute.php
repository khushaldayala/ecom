<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'variant_id',
        'attribute_id',
        'attribute_option_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function attribut()
    {
        return $this->belongsTo(Attribut::class, 'attribute_id');
    }

    public function attributOption()
    {
        return $this->belongsTo(AttributOption::class, 'attribute_option_id');
    }
}
