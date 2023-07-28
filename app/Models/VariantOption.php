<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantOption extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function productVariants()
    {
        return $this->belongsTo(Product::class);
    }

}

