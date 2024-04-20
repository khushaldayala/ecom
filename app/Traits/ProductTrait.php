<?php

namespace App\Traits;

use App\Models\OfferProduct;
use App\Models\SectionProduct;

trait ProductTrait
{
    public function productAssignTosection($product, $sectionIds)
    {
        foreach ($sectionIds as $section) {
            SectionProduct::create([
                'section_id' => $section,
                'product_id' => $product->id,
                'user_id' => 1
            ]);
        }
    }

    public function productAssignToOffer($product, $offerIds)
    {
        foreach ($offerIds as $offer) {
            OfferProduct::create([
                'offer_id' => $offer,
                'product_id' => $product->id,
                'user_id' => 1
            ]);
        }
    }
}
