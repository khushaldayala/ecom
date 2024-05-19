<?php

namespace App\Traits;

use App\Models\OfferProduct;
use App\Models\SectionOffer;

trait OfferTrait
{
    public function productAssignToOffer($offer, $productIds)
    {
        foreach ($productIds as $product) {
            OfferProduct::updateOrCreate(
                [
                    'product_id' => $product,
                    'offer_id' => $offer->id
                ],
                [
                    'user_id' => 1
                ]
            );
        }
    }

    public function offerAssignTosection($offer, $sectionIds)
    {
        SectionOffer::where('banner_id', $offer->id)->delete();
        foreach ($sectionIds as $section) {
            SectionOffer::create([
                'section_id' => $section,
                'offer_id' => $offer->id,
                'user_id' => 1
            ]);
        }
    }
}