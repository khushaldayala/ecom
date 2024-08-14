<?php

namespace App\Traits;

use App\Models\AttributeCategory;
use App\Models\AttributeSubCategory;
use App\Models\AttributOption;

trait AttributeTrait
{
    public function addAttributesValue($attribute1, $request)
    {
        foreach($request->attributeValue as $attribute)
        {
            AttributOption::create([
                'attribut_id' => $attribute1->id,
                'value1' => isset($attribute['value1']) && $attribute['value1'] ? $attribute['value1'] : null,
                'value2' => isset($attribute['value2']) && $attribute['value2'] ? $attribute['value2'] : null
            ]);
        }
    }

    public function updateAttributesValue($attribute1, $request)
    {
        AttributOption::where('attribut_id', $attribute1->id)->delete();
        if(isset($request->attributeValue) && $request->attributeValue)
        {
            foreach ($request->attributeValue as $attribute) {
                AttributOption::create([
                    'attribut_id' => $attribute1->id,
                    'value1' => isset($attribute['value1']) && $attribute['value1'] ? $attribute['value1'] : null,
                    'value2' => isset($attribute['value2']) && $attribute['value2'] ? $attribute['value2'] : null
                ]);
            }
        }
    }

    public function assignCategory($attribute, $categoryIds)
    {
        AttributeCategory::where('attribute_id', $attribute->id)->delete();

        foreach($categoryIds as $category)
        {
            AttributeCategory::create([
                'category_id' => $category,
                'attribute_id' => $attribute->id
            ]);
        }
    }

    public function assignSubCategory($attribute, $subcategoryIds)
    {
        AttributeSubCategory::where('attribute_id', $attribute->id)->delete();

        foreach ($subcategoryIds as $subcategory) {
            AttributeSubCategory::create([
                'sub_category_id' => $subcategory,
                'attribute_id' => $attribute->id
            ]);
        }
    }
}
