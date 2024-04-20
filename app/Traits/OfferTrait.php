<?php

namespace App\Traits;

use App\Models\OfferProduct;
use App\Models\SectionOffer;

trait OfferTrait
{
    public function productAssignToOffer($offer, $productIds)
    {
        foreach ($productIds as $product) {
            OfferProduct::create([
                'product_id' => $product,
                'offer_id' => $offer->id,
                'user_id' => 1
            ]);
        }
    }

    public function offerAssignTosection($offer, $sectionIds)
    {
        foreach ($sectionIds as $section) {
            SectionOffer::create([
                'section_id' => $section,
                'offer_id' => $offer->id,
                'user_id' => 1
            ]);
        }
    }
}