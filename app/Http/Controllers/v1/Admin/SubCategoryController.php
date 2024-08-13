<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryStoreRequest;
use App\Http\Requests\SubCategoryUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\Subcategory;
use App\Traits\SubCategoryTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class SubCategoryController extends Controller
{
    use SubCategoryTrait;

    public function sub_categories(Request $request)
    {
        $userId = Auth::id();
        $sort = $request->input('sort');
        $sortType = $request->input('sort_type');
        $search = $request->input('search');
        $isActive = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);

        $sub_category = Subcategory::where('user_id', $userId)->with('products');

        if ($search) {
            $sub_category = $sub_category->where('sub_title', 'LIKE', '%' . $search . '%');
        }

        if($sortType == 'product') {
            if ($sort) {
                switch ($sort) {
                    case 'asc':
                        $sub_category->withCount('products')->orderBy('products_count', $sort);
                        break;
                    case 'desc':
                        $sub_category->withCount('products')->orderBy('products_count', $sort);
                        break;
                }
            }
        } else if($sortType == 'name') {
            if ($sort) {
                switch ($sort) {
                    case 'asc':
                        $sub_category->withCount('products')->orderBy('sub_title', $sort);
                        break;
                    case 'desc':
                        $sub_category->withCount('products')->orderBy('sub_title', $sort);
                        break;
                }
            }
        } 
        else if ($sortType == 'category') {
            if ($sort) {
                switch ($sort) {
                    case 'asc':
                        $sub_category = SubCategory::withCount('products')
                        ->join('categories', 'subcategories.category_id', '=', 'categories.id')
                        ->where('subcategories.user_id', '=', Auth::id())
                        ->orderBy('categories.title', $sort)
                        ->select('subcategories.*');
                        break;
                    case 'desc':
                        $sub_category = SubCategory::withCount('products')
                        ->join('categories', 'subcategories.category_id', '=', 'categories.id')
                        ->where('subcategories.user_id', '=', Auth::id())
                        ->orderBy('categories.title', $sort)
                        ->select('subcategories.*');
                        break;
                }
            }
        }

        if ($isActive) {
            $sub_category = $sub_category->get();
        } else {
            $sub_category = $sub_category->paginate();
        }

        $sub_category->each(function ($sub_category) {
            $sub_category->product_count = $sub_category->products->filter(function ($brandProduct) {
                return $brandProduct !== null;
            })->count();

            $sub_category->makeHidden('products');
        });

        return Response::json([
            'status' => '200',
            'message' => 'Sub-category list get successfully',
            'data' => $sub_category
        ], 200);
    }

    public function store(SubCategoryStoreRequest $request){

        $userId = Auth::id();
        $image = $request->file('sub_image');

        $name = time().'.'.$image->getClientOriginalExtension();

        $destinationPath = public_path('/images/subcategory');

        $image->move($destinationPath,$name);

        $subcategory = new Subcategory;
        $subcategory->user_id = $userId;
        $subcategory->category_id = $request->category_id;
        $subcategory->sub_image = $name;
        $subcategory->sub_title = $request->sub_title;
        $subcategory->sub_description = $request->sub_description;
        $subcategory->status = $request->status;
        $subcategory->save();

        if ($request->product_ids) {
            $this->productAssignToSubCategory($subcategory, $request->product_ids);
        }

        return Response::json([
            'status' => '200',
            'message' => 'Sub-category data has been saved'
        ], 200);
    }

    public function get_single_subcategory(Subcategory $subcategory){

        $assignedProductIds = $subcategory->products->pluck('id')->toArray();

        $subcategory = $subcategory->load(['products' => function ($query) {
                               $query->take(10);
                           }, 'products.productImages', 'products.productVariants.productVariantAttribute', 'products.productVariants.productVariantImages']);

        return Response::json([
            'status' => '200',
            'message' => 'Sub-category data get successfully',
            'data' => $subcategory,
            'assigned_product_ids' => $assignedProductIds
        ], 200);
    }

    public function update(SubCategoryUpdateRequest $request, Subcategory $subcategory){
        if($request->hasFile('sub_image')){
            
            $image = $request->file('sub_image');
            
            $name = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = public_path('/images/subcategory');
            
            $image->move($destinationPath,$name);
        }
        $subcategory->category_id = $request->category_id;
        if($request->hasFile('sub_image')){
            $subcategory->sub_image = $name;
        }
        $subcategory->sub_title = $request->sub_title;
        $subcategory->sub_description = $request->sub_description;
        $subcategory->status = $request->status;
        $subcategory->save();

        $this->productAssignToSubCategory($subcategory, $request->assigned_product_ids);

        return Response::json([
            'status' => '200',
            'message' => 'Sub-category data has been Updated'
        ], 200);
    }

    public function delete(Subcategory $subcategory){
        $subcategory->products()->update(['subcategory_id'=>null]);
        $subcategory->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Sub-category move to trash successfully'
        ], 200);
    }

    // Trash data section
    public function trash_subcategory(){
        $userId = Auth::id();
        $sub_category = Subcategory::where('user_id', $userId)->onlyTrashed()->get();
        
        return Response::json([
            'status' => '200',
            'message' => 'Trash Sub-category list get successfully',
            'data' => $sub_category
        ], 200);
    }

    public function trash_subcategory_restore($subcategory){
        $sub_category = Subcategory::onlyTrashed()->findOrFail($subcategory);
        $sub_category->restore();

        return Response::json([
            'status' => '200',
            'message' => 'Sub-category restored successfully'
        ], 200);
    }

    public function trash_subcategory_delete($subcategory){
        $sub_category = Subcategory::onlyTrashed()->findOrFail($subcategory);
        $sub_category->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'Trash Sub-category deleted successfully'
        ], 200);
    }
    
    public function all_trash_subcategory_delete(){
        $userId = Auth::id();
        Subcategory::where('user_id', $userId)->onlyTrashed()->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'All Trash Sub-category deleted successfully'
        ], 200);
    }

    public function statusUpdate(Subcategory $subcategory)
    {
        if ($subcategory->status == 'active') {
            $status = 'inactive';
        } else {
            $status = 'active';
        }

        $subcategory->update(['status' => $status]);

        return Response::json([
            'status' => '200',
            'message' => 'Sub-category status updated successfully.',
        ], 200);
    }
}