<?php

namespace App\Traits;

use App\Models\SectionBanner;

trait BannerTrait
{
    public function bannerAssignTosection($banner, $sectionIds)
    {
        SectionBanner::where('banner_id', $banner->id)->delete();
        foreach ($sectionIds as $section) {
            SectionBanner::create([
                'section_id' => $section,
                'banner_id' => $banner->id,
                'user_id' => 1
            ]);
        }
    }
}