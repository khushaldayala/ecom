<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Product;
use App\Models\SectionBrand;
use App\Traits\BrandTrait;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    use BrandTrait;

    public function brands(Request $request)
    {
        $userId = Auth::id();
        $sort = $request->input('sort');
        $sortType = $request->input('sort_type');
        $search = $request->input('search');
        $isActive = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);

        $brands = Brand::with('products')->where('user_id', $userId);

        // If a search query is provided, filter the brands
        if ($search) {
            $brands = $brands->where('title', 'LIKE', '%' . $search . '%');
        }
        
        if($sortType == 'product')
        {
            if ($sort) {
                switch ($sort) {
                    case 'asc':
                        $brands->withCount('products')->orderBy('products_count', $sort);
                        break;
                    case 'desc':
                        $brands->withCount('products')->orderBy('products_count', $sort);
                        break;
                }
            }
        } else if($sortType == 'name')
        {
            if ($sort) {
                switch ($sort) {
                    case 'asc':
                        $brands->withCount('products')->orderBy('title', $sort);
                        break;
                    case 'desc':
                        $brands->withCount('products')->orderBy('title', $sort);
                        break;
                }
            }
        } else {
            $brands->latest();
        }

        if ($isActive) {
            $brands = $brands->get();
        } else {
            $brands = $brands->paginate();
        }
        
        $brands->each(function ($brands) {
            $brands->product_count = $brands->products->filter(function ($brandProduct) {
                return $brandProduct !== null;
            })->count();

            $brands->makeHidden('products');
        });

        return Response::json([
            'status' => '200',
            'message' => 'Brands list get successfully',
            'data' => $brands
        ], 200);
    }
    public function store(BrandStoreRequest $request)
    {

        $userId = Auth::id();

        $image = $request->file('image');
        $name = time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('/images/brand');
        $image->move($destinationPath, $name);

        $keyword = Str::slug($request->title);

        $brand = new Brand;
        $brand->user_id = $userId;
        $brand->title = $request->title;
        $brand->description = $request->description;
        $brand->image = $name;
        $brand->keyword = $keyword;
        $brand->link = $request->link;
        $brand->status = $request->status;
        $brand->save();

        if ($request->product_ids) {
            $this->productAssignToBrand($brand, $request->product_ids);
        }

        if ($request->section_id) {
            $this->brandAssignTosection($brand, $request->section_id);
        }

        return Response::json([
            'status' => '200',
            'message' => 'Brand data has been saved'
        ], 200);
    }
    public function get_single_brand(Brand $brand)
    {
        $assignedProductIds = $brand->products->pluck('id')->toArray();
        
        $brand = $brand->load(['section_brands.section', 'products' => function ($query) {
                               $query->take(500);
                           }, 'products.productImages', 'products.productVariants', 'products.productVariants.productVariantImages']);
                           
        return Response::json([
            'status' => '200',
            'message' => 'brand data get successfully',
            'data' => $brand,
            'assigned_product_ids' => $assignedProductIds
        ], 200);
    }
    public function update(BrandUpdateRequest $request, Brand $brand)
    {

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $name = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('/images/brand');

            $image->move($destinationPath, $name);
        }

        $keyword = Str::slug($request->title);
        $userId = Auth::id();

        $brand->user_id = $userId;
        $brand->title = $request->title;
        $brand->description = $request->description;
        if ($request->hasFile('image')) {
            $brand->image = $name;
        }
        $brand->link = $request->link;
        $brand->keyword = $keyword;
        $brand->status = $request->status;
        $brand->save();

        if(isset($request->section_id) && $request->section_id)
        {
             $this->brandAssignTosection($brand, $request->section_id);
        }

        $this->productAssignToBrand($brand, $request->assigned_product_ids);

        return Response::json([
            'status' => '200',
            'message' => 'Brand data has been updated'
        ], 200);
    }
    public function delete(Brand $brand)
    {
        
        $brand->products()->update(['brand_id' => null]);
        $brand->delete();
        
        return Response::json([
            'status' => '200',
            'message' => 'brand move to trash successfully'
        ], 200);
    }

    // Trash data section
    public function trash_brand()
    {
        $userId = Auth::id();
        $brand = Brand::where('user_id', $userId)->onlyTrashed()->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Trash brands list get successfully',
            'data' => $brand
        ], 200);
    }
    public function trash_brand_restore($brand)
    {
        $brand = Brand::onlyTrashed()->findOrFail($brand);
        $brand->restore();

        return Response::json([
            'status' => '200',
            'message' => 'brand restored successfully'
        ], 200);
    }
    public function trash_brand_delete($brand)
    {
        $brand = Brand::onlyTrashed()->findOrFail($brand);
        $brand->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'Trash brand deleted successfully'
        ], 200);
    }
    public function all_trash_brand_delete()
    {
        $userId = Auth::id();
        Brand::where('user_id', $userId)->onlyTrashed()->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'All Trash brands deleted successfully'
        ], 200);
    }

    public function remove_brand_section(SectionBrand $section)
    {
        $section->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Brand has been successfully removed from the section.'
        ], 200);
    }

    public function assigned()
    {
        $userId = Auth::id();
        $brandIds = SectionBrand::where('user_id', $userId)->pluck('brand_id')->unique()->values()->toArray();
        $data = Brand::whereIn('id', $brandIds)->where('user_id', $userId)->get();

        return Response::json([
            'status' => '200',
            'message' => 'Assigned brand list.',
            'data' => $data
        ], 200);
    }

    public function unassigned()
    {
        $userId = Auth::id();
        $brandIds = SectionBrand::where('user_id', $userId)->pluck('brand_id')->unique()->values()->toArray();
        $data = Brand::whereNotIn('id', $brandIds)->where('user_id', $userId)->get();

        return Response::json([
            'status' => '200',
            'message' => 'Unassigned brand list.',
            'data' => $data
        ], 200);
    }

    public function assigned_products($brand_id)
    {
        $userId = Auth::id();
        $query = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages')->where('user_id', $userId)->where('brand_id', $brand_id);
        $data = $query->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Assigned products list.',
            'data' => $data
        ], 200);
    }

    public function unassigned_products()
    {
        $userId = Auth::id();
        $query = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages')->where('user_id', $userId)->whereNull('brand_id');
        $data = $query->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Unassigned products list.',
            'data' => $data
        ], 200);
    }

    public function statusUpdate(Brand $brand)
    {
        if ($brand->status == 'active') {
            $status = 'inactive';
        } else {
            $status = 'active';
        }

        $brand->update(['status' => $status]);

        return Response::json([
            'status' => '200',
            'message' => 'Brand status updated successfully.',
        ], 200);
    }
}
