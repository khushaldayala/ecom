<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\SectionBrand;

trait BrandTrait
{
    public function brandAssignTosection($brand, $sectionIds)
    {
        SectionBrand::where('brand_id', $brand->id)->delete();
        foreach ($sectionIds as $section) {
            SectionBrand::create([
                'section_id' => $section,
                'brand_id' => $brand->id,
                'user_id' => 1
            ]);
        }
    }

    public function productAssignToBrand($brand, $productIds)
    {
        Product::whereIn('id', $productIds)->update(['brand_id' => $brand->id]);
    }
}
