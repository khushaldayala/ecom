<?php

namespace App\Traits;

use App\Models\Product;

trait SubCategoryTrait
{
    public function productAssignToSubCategory($subcategory, $productIds)
    {
        $currentProductIds = Product::where('subcategory_id', $subcategory->id)->pluck('id')->toArray();

        if (!empty($productIds)) {
            $productIdsToDelete = array_diff($currentProductIds, $productIds);
        } else {
            $productIdsToDelete = $currentProductIds;
        }

        if (!empty($productIdsToDelete)) {
            Product::whereIn('id', $productIdsToDelete)->update(['subcategory_id' => null]);
        }

        if (!empty($productIds)) {
            Product::whereIn('id', $productIds)->update(['subcategory_id' => $subcategory->id]);
        }
    }
}
