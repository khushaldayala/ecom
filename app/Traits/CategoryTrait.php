<?php

namespace App\Traits;

use App\Models\SectionCategory;

trait CategoryTrait
{
    public function categoryAssignTosection($category, $sectionIds)
    {
        SectionCategory::where('banner_id', $category->id)->delete();
        foreach ($sectionIds as $section) {
            SectionCategory::create([
                'section_id' => $section,
                'category_id' => $category->id,
                'user_id' => 1
            ]);
        }
    }
}
