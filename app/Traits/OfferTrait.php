<?php

namespace App\Traits;

use App\Models\OfferProduct;
use App\Models\SectionOffer;

trait OfferTrait
{
    public function productAssignToOffer($offer, $productIds)
    {
        $currentProductIds = OfferProduct::where('offer_id', $offer->id)->pluck('product_id')->toArray();

        $productIdsToDelete = array_diff($currentProductIds, $productIds);

        if (!empty($productIdsToDelete)) {
            OfferProduct::where('offer_id', $offer->id)
                ->whereIn('product_id', $productIdsToDelete)
                ->delete();
        }

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
        SectionOffer::where('offer_id', $offer->id)->delete();
        foreach ($sectionIds as $section) {
            SectionOffer::create([
                'section_id' => $section,
                'offer_id' => $offer->id,
                'user_id' => 1
            ]);
        }
    }
}