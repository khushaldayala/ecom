<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function offer_products()
    {
        return $this->hasMany(OfferProduct::class);
    }

    public function section_offers()
    {
        return $this->hasMany(SectionOffer::class)->select('id', 'section_id', 'offer_id');
    }

    public function offer_product()
    {
        return $this->hasMany(OfferProduct::class)->select('id', 'product_id', 'offer_id');
    }
}
