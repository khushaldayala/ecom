<?php

namespace App\Traits;

use App\Models\SectionAdvertise;

trait AdvertiseTrait
{
    public function advertiseAssignTosection($advertise, $sectionIds)
    {
        SectionAdvertise::where('banner_id', $advertise->id)->delete();
        foreach ($sectionIds as $section) {
            SectionAdvertise::create([
                'section_id' => $section,
                'advertise_id' => $advertise->id,
                'user_id' => 1
            ]);
        }
    }
}
