<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\Category;
use App\Models\SectionCategory;
use App\Traits\CategoryTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    use CategoryTrait;

    public function categories(Request $request)
    {
        $userId = Auth::id();

        $sortType = $request->input('sort_type');
        $sort = $request->input('sort');
        $search = $request->input('search');
        $isActive = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);

        $categoryQuery = Category::where('user_id', $userId)->with('products', 'subcategory');

        // If a search query is provided, filter the categories
        if ($search) {
            $categoryQuery->where('title', 'LIKE', '%' . $search . '%');
        }

        // Apply sorting
        if ($sortType) {
            switch ($sortType) {
                case 'product':
                    $categoryQuery->withCount('products')->orderBy('products_count', $sort);
                    break;
                case 'subcategory':
                    $categoryQuery->withCount('subcategory')->orderBy('subcategory_count', $sort);
                    break;
                case 'category':
                    $categoryQuery->orderBy('title', $sort);
                    break;
            }
        } else {
            $categories = $categoryQuery->latest();
        }

        // Fetch the categories based on the active state
        if ($isActive) {
            $categories = $categoryQuery->get();
        } else {
            $categories = $categoryQuery->paginate();
        }

        // Calculate product and subcategory counts
        $categories->each(function ($category) {
            $category->product_count = $category->products->count();
            $category->subcategory_count = $category->subcategory->count();
            $category->makeHidden('products', 'subcategory');
        });

        return response()->json([
            'status' => '200',
            'message' => 'Category list retrieved successfully',
            'data' => $categories
        ], 200);
    }

    public function store(CategoryStoreRequest $request){

        $userId = Auth::id();

        $image = $request->file('image');

        $name = time().'.'.$image->getClientOriginalExtension();

        $destinationPath = public_path('/images/categories');

        $image->move($destinationPath,$name);

        $category = new Category;
        $category->user_id = $userId;
        $category->title = $request->title;
        $category->description = $request->description;
        $category->image = $name;
        $category->status = $request->status;
        $category->save();

        if ($request->section_id) {
            $this->categoryAssignTosection($category, $request->section_id);
        }

        if ($request->product_ids) {
            $this->productAssignToCategory($category, $request->product_ids);
        }

        return Response::json([
            'status' => '200',
            'message' => 'category data has been saved'
        ], 200);
    }
    public function get_single_category(Category $category){

        $assignedProductIds = $category->products->pluck('id')->toArray();
        $category = $category->load(['section_categories.section', 'products' => function ($query) {
                               $query->take(10);
                           }, 'products.productImages', 'products.productVariants', 'products.productVariants.productVariantImages']);

        return Response::json([
            'status' => '200',
            'message' => 'Category data get successfully',
            'data' => $category,
            'assigned_product_ids' => $assignedProductIds
        ], 200);
    }
    public function update(CategoryUpdateRequest $request, Category $category){

        $userId = Auth::id();
        if($request->hasFile('image')){
            $image = $request->file('image');

            $name = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/images/categories');

            $image->move($destinationPath,$name);
        }
        $category->user_id = $userId;
        $category->title = $request->title;
        $category->description = $request->description;
        if($request->hasFile('image')){
            $category->image = $name;
        };
        $category->status = $request->status;
        $category->save();

        if ($request->section_id) {
            $this->categoryAssignTosection($category, $request->section_id);
        }

        $this->updateProductAssignToCategory($category, $request->assigned_product_ids);
        
        return Response::json([
            'status' => '200',
            'message' => 'category data has been Updaed successfully'
        ], 200);
    }
    public function delete(Category $category){
        $category->subcategory()->update(['category_id'=>null]);
        $category->products()->update(['category_id'=>null]);
        $category->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Category move to trash successfully'
        ], 200);
    }

    // Trash data section
    public function trash_categories(){
        $userId = Auth::id();
        $category = Category::where('user_id', $userId)->onlyTrashed()->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Trash category list get successfully',
            'data' => $category
        ], 200);
    }
    public function trash_restore($id){
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return Response::json([
            'status' => '200',
            'message' => 'Category restored successfully'
        ], 200);
    }
    public function trash_delete($id){
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'Trash category deleted successfully'
        ], 200);
    }
    public function all_trash_delete(){
        $userId = Auth::id();
        Category::where('user_id', $userId)->onlyTrashed()->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'All Trash Category deleted successfully'
        ], 200);
    }

    public function remove_category_section(SectionCategory $section)
    {
        $section->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Category has been successfully removed from the section.'
        ], 200);
    }

    public function assigned()
    {
        $userId = Auth::id();
        $categoryIds = SectionCategory::where('user_id', $userId)->pluck('category_id')->unique()->values()->toArray();
        $data = Category::where('user_id', $userId)->whereIn('id', $categoryIds)->get();

        return Response::json([
            'status' => '200',
            'message' => 'Assigned category list.',
            'data' => $data
        ], 200);
    }

    public function unassigned()
    {
        $userId = Auth::id();
        $categoryIds = SectionCategory::where('user_id', $userId)->pluck('category_id')->unique()->values()->toArray();
        $data = Category::where('user_id', $userId)->whereNotIn('id', $categoryIds)->get();

        return Response::json([
            'status' => '200',
            'message' => 'Unassigned category list.',
            'data' => $data
        ], 200);
    }

    public function statusUpdate(Category $category)
    {
        if ($category->status == 'active') {
            $status = 'inactive';
        } else {
            $status = 'active';
        }

        $category->update(['status' => $status]);

        return Response::json([
            'status' => '200',
            'message' => 'Category status updated successfully.',
        ], 200);
    }

}
