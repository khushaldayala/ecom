<?php

namespace App\Traits;

use App\Models\SectionAdvertise;
use App\Models\SectionBanner;
use App\Models\SectionBrand;
use App\Models\SectionCategory;
use App\Models\SectionOffer;
use App\Models\SectionProduct;

trait SectionTrait
{
    public function assignToSection($type, $section, $itemsIds)
    {
        switch ($type) {
            case 'product':
                foreach ($itemsIds as $item) {
                    SectionProduct::create([
                        'section_id' => $section->id,
                        'product_id' => $item,
                        'user_id' => 1
                    ]);
                }
                break;
            case 'offer':
                foreach ($itemsIds as $item) {
                    SectionOffer::create([
                        'section_id' => $section->id,
                        'offer_id' => $item,
                        'user_id' => 1
                    ]);
                }
                break;
            case 'category':
                foreach ($itemsIds as $item) {
                    SectionCategory::create([
                        'section_id' => $section->id,
                        'category_id' => $item,
                        'user_id' => 1
                    ]);
                }
                break;
            case 'brand':
                foreach ($itemsIds as $item) {
                    SectionBrand::create([
                        'section_id' => $section->id,
                        'brand_id' => $item,
                        'user_id' => 1
                    ]);
                }
                break;
            case 'banner':
                foreach ($itemsIds as $item) {
                    SectionBanner::create([
                        'section_id' => $section->id,
                        'banner_id' => $item,
                        'user_id' => 1
                    ]);
                }
                break;
            case 'advertise':
                foreach ($itemsIds as $item) {
                    SectionAdvertise::create([
                        'section_id' => $section->id,
                        'advertise_id' => $item,
                        'user_id' => 1
                    ]);
                }
                break;
            default:
                // Handle default case
                break;
        }
    }
}
