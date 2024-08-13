<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeStoreRequest;
use App\Http\Requests\AttributeUpdateRequest;
use App\Models\Attribut;
use App\Models\AttributeCategory;
use App\Models\AttributeSubCategory;
use App\Models\AttributOption;
use App\Models\ProductVariant;
use App\Models\ProductVariantAttribute;
use App\Models\ProductVariantImage;
use App\Traits\AttributeTrait;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    use AttributeTrait;

    public function attributes(Request $request)
    {
        $userId = Auth::id();
        $sort = $request->input('sort');
        $search = $request->input('search');
        $isActive = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);

        $query = Attribut::where('user_id', $userId)->with('attributes', 'attributeCategories.category', 'attributeSubCategories.subcategory');

        if ($search) {
            $query = $query->where('title', 'LIKE', '%' . $search . '%');
        }

        if ($sort === 'asc') {
            $query = $query->orderBy('id', 'asc');
        } elseif ($sort === 'desc') {
            $query = $query->orderBy('id', 'desc');
        } else {
            $query->latest();
        }

        if ($isActive) {
            $query = $query->get();
        } else {
            $query = $query->paginate();
        }

        return Response::json([
            'status' => '200',
            'message' => 'Attributes list get successfully',
            'data' => $query
        ], 200);
    }
    public function store(AttributeStoreRequest $request)
    {
        $userId = Auth::id();

        $attribute = new Attribut;
        $attribute->user_id = $userId;
        $attribute->title = $request->title;
        $attribute->display_name = $request->display_name;
        $attribute->type = $request->type;
        $attribute->status = $request->status;
        $attribute->save();

        if (isset($request->attributeValue) && $request->attributeValue) {
            $this->addAttributesValue($attribute, $request);
        }

        if (isset($request->categoryIds) && $request->categoryIds) {
            $this->assignCategory($attribute, $request->categoryIds);
        }

        if (isset($request->subcategoryIds) && $request->subcategoryIds) {
            $this->assignSubCategory($attribute, $request->subcategoryIds);
        }

        return Response::json([
            'status' => '201',
            'message' => 'Attribute created successfully'
        ], 201);
    }
    public function get_single_filter(Attribut $attribute)
    {
        $attribute = $attribute->load('attributes');
        return Response::json([
            'status' => '200',
            'message' => 'Attribute data get successfully',
            'data' => $attribute
        ], 200);
    }
    public function update(AttributeUpdateRequest $request, Attribut $attribute)
    {
        $userId = Auth::id();

        $attribute->user_id = $userId;
        $attribute->title = $request->title;
        $attribute->display_name = $request->display_name;
        $attribute->type = $request->type;
        $attribute->status = $request->status;
        $attribute->save();

        $this->updateAttributesValue($attribute, $request);

        if (isset($request->categoryIds)) {
            $this->assignCategory($attribute, $request->categoryIds);
        }

        if (isset($request->subcategoryIds)) {
            $this->assignSubCategory($attribute, $request->subcategoryIds);
        }

        return Response::json([
            'status' => '201',
            'message' => 'Attribute updated successfully'
        ], 201);
    }
    public function delete(Attribut $attribute)
    {
        $variantIds = ProductVariantAttribute::where('attribute_id', $attribute->id)
        ->distinct()
        ->pluck('variant_id');
        
        ProductVariant::whereIn('id', $variantIds)->delete();
        ProductVariantImage::whereIn('id', $variantIds)->delete();
        $attribute->attributes()->delete();
        $attribute->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Attribute data move to trash successfully'
        ], 200);
    }

    // Trash data section
    public function trash_filter()
    {
        $userId = Auth::id();
        $attribute = Attribut::where('user_id', $userId)->onlyTrashed()->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Trash Attribute list get successfully',
            'data' => $attribute
        ], 200);
    }
    public function trash_filter_restore($attribute)
    {
        $attribute = Attribut::onlyTrashed()->findOrFail($attribute);
        $attribute->attributes()->restore();
        $attribute->restore();

        return Response::json([
            'status' => '200',
            'message' => 'Attribute data restored successfully'
        ], 200);
    }
    public function trash_filter_delete($attribute)
    {
        $attribute = Attribut::onlyTrashed()->findOrFail($attribute);
        $attribute->attributes()->forceDelete();
        $attribute->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'Trash Attribute data deleted successfully'
        ], 200);
    }
    public function all_trash_filter_delete()
    {
        $userId = Auth::id();
        Attribut::where('user_id', $userId)->onlyTrashed()->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'All Trash Attributes deleted successfully'
        ], 200);
    }

    public function deleteAttributeOption(AttributOption $attributeOption)
    {
        $variantIds = ProductVariantAttribute::where('attribute_option_id', $attributeOption->id)
        ->distinct()
        ->pluck('variant_id');

        ProductVariant::whereIn('id', $variantIds)->delete();
        ProductVariantImage::whereIn('id', $variantIds)->delete();
        ProductVariantAttribute::where('attribute_option_id', $attributeOption->id)->delete();
        $attributeOption->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Attribute option deleted successfully'
        ], 200);
    }
}
