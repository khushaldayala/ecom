<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\SectionCategory;
use Illuminate\Support\Facades\Auth;

trait CategoryTrait
{
    public function categoryAssignTosection($category, $sectionIds)
    {
        SectionCategory::where('category_id', $category->id)->delete();
        foreach ($sectionIds as $section) {
            if($section)
            {
                SectionCategory::create([
                    'section_id' => $section,
                    'category_id' => $category->id,
                    'user_id' => Auth::id()
                ]);
            }
        }
    }

    public function productAssignToCategory($category, $productIds)
    {
        Product::whereIn('id', $productIds)->update(['Category_id' => $category->id]);
    }

    public function updateProductAssignToCategory($category, $productIds)
    {
        $assignedProductIds = Product::where('category_id', $category->id)->pluck('id')->unique()->values()->toArray();
        if (is_array($productIds) && $productIds) {
            $productIdsToDelete = array_diff($assignedProductIds, $productIds);
        } else {
            $productIdsToDelete = $assignedProductIds;
        }

        if (!empty($productIdsToDelete)) {
            Product::whereIn('id', $productIdsToDelete)
                ->update(['Category_id' => null]);
        }

        if (is_array($productIds) && $productIds) {
            Product::whereIn('id', $productIds)->update(['Category_id' => $category->id]);
        }
    }
}
