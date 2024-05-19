<?php

namespace App\Traits;

use App\Models\SectionBrand;

trait BrandTrait
{
    public function bannerAssignTosection($brand, $sectionIds)
    {
        SectionBrand::where('banner_id', $brand->id)->delete();
        foreach ($sectionIds as $section) {
            SectionBrand::create([
                'section_id' => $section,
                'brand_id' => $brand->id,
                'user_id' => 1
            ]);
        }
    }
}
