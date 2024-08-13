<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\SectionBrand;
use Illuminate\Support\Facades\Auth;

trait BrandTrait
{
    public function brandAssignTosection($brand, $sectionIds)
    {
        SectionBrand::where('brand_id', $brand->id)->delete();
        foreach ($sectionIds as $section) {
            if($section)
            {
                SectionBrand::create([
                    'section_id' => $section,
                    'brand_id' => $brand->id,
                    'user_id' => Auth::id()
                ]);
            }
        }
    }

    public function productAssignToBrand($brand, $productIds)
    {
        $currentProductIds = Product::where('brand_id', $brand->id)->pluck('id')->toArray();

        $productIdsToDelete = array_diff($currentProductIds, $productIds);

        if (!empty($productIdsToDelete)) {
            Product::whereIn('id', $productIdsToDelete)->update(['brand_id' => null]);
        }

        if($productIds)
        {
            Product::whereIn('id', $productIds)->update(['brand_id' => $brand->id]);
        }
    }
}
