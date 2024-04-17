<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferProduct extends Model
{
    use HasFactory;

    protected $fillable = ['offer_id', 'product_id', 'user_id'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->select('id', 'product_name');
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class)->select('id', 'title');
    }
}
