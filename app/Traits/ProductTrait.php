<?php

namespace App\Traits;

use App\Models\OfferProduct;
use App\Models\SectionProduct;
use Illuminate\Support\Facades\Auth;

trait ProductTrait
{
    public function productAssignTosection($product, $sectionIds)
    {
        SectionProduct::where('product_id', $product->id)->delete();
        foreach ($sectionIds as $section) {
            SectionProduct::create([
                'section_id' => $section,
                'product_id' => $product->id,
                'user_id' => Auth::id()
            ]);
        }
    }

    public function productAssignToOffer($product, $offerIds)
    {
        OfferProduct::where('product_id', $product->id)->delete();
        if($offerIds)
        {
            foreach ($offerIds as $offer) {
                OfferProduct::create([
                    'offer_id' => $offer,
                    'product_id' => $product->id,
                    'user_id' => Auth::id()
                ]);
            }
        }
    }
}
