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

class CategoryController extends Controller
{
    use CategoryTrait;

    public function categories(){
        $category = Category::all();
        if($category){
            return Response::json([
                'status' => '200',
                'message' => 'Category list get successfully',
                'data' => $category
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Category data not found'
            ], 404);
        }
    }
    public function store(CategoryStoreRequest $request){
        
        $image = $request->file('image');

        $name = time().'.'.$image->getClientOriginalExtension();

        $destinationPath = public_path('/images/categories');

        $image->move($destinationPath,$name);

        $category = new Category;
        $category->title = $request->title;
        $category->description = $request->description;
        $category->image = $name;
        $category->status = $request->status;
        $category->section_id = $request->section_id ? $request->section_id[0] : null;
        $category->save();

        if ($request->section_id) {
            $this->categoryAssignTosection($category, $request->section_id);
        }

        if($category){
            return Response::json([
                'status' => '200',
                'message' => 'category data has been saved'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'category data has been not saved'
            ], 401);
        }
    }
    public function get_single_category($id){
        $category = Category::with('section_categories.section')->findorfail($id);
        if($category){
            return Response::json([
                'status' => '200',
                'message' => 'Category data get successfully',
                'data' => $category
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Category data not found'
            ], 404);
        }
    }
    public function update(CategoryUpdateRequest $request, $id){
       
        if($request->hasFile('image')){
            $image = $request->file('image');

            $name = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/images/categories');

            $image->move($destinationPath,$name);
        }
        $category = Category::find($id);
        $category->title = $request->title;
        $category->description = $request->description;
        if($request->hasFile('image')){
            $category->image = $name;
        };
        $category->status = $request->status;
        $category->section_id = $request->section_id ? $request->section_id[0] : null;
        $category->save();

        if ($request->section_id) {
            $this->categoryAssignTosection($category, $request->section_id);
        }
        
        if($category){
            return Response::json([
                'status' => '200',
                'message' => 'category data has been Updaed successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'category data has been not Updated'
            ], 401);
        }
    }
    public function delete($id){
        $category = Category::find($id);
        $category->delete();
        // $category->subcategory()->update(['category_id'=>null]);
        // $category->fabric()->update(['category_id'=>null]);
        // $category->products()->update(['category_id'=>null]);
        if($category){
            return Response::json([
                'status' => '200',
                'message' => 'Category move to trash successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Category has been not move in trash'
            ], 401);
        }
    }

    // Trash data section
    public function trash_categories(){
        $category = Category::onlyTrashed()->get();
        if($category){
            return Response::json([
                'status' => '200',
                'message' => 'Trash category list get successfully',
                'data' => $category
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Trash category data not found'
            ], 404);
        }
    }
    public function trash_restore($id){
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();
        if($category){
            return Response::json([
                'status' => '200',
                'message' => 'Category restored successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Category has been not restored'
            ], 401);
        }
    }
    public function trash_delete($id){
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();
        if($category){
            return Response::json([
                'status' => '200',
                'message' => 'Trash category deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Category has been not deleted'
            ], 401);
        }
    }
    public function all_trash_delete(){
        $category = Category::onlyTrashed()->forceDelete();
        if($category){
            return Response::json([
                'status' => '200',
                'message' => 'All Trash Category deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Category has been not deleted'
            ], 401);
        }
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
        $categoryIds = SectionCategory::pluck('category_id')->unique()->values()->toArray();
        $data = Category::whereIn('id', $categoryIds)->get();

        return Response::json([
            'status' => '200',
            'message' => 'Assigned category list.',
            'data' => $data
        ], 200);
    }

    public function unassigned()
    {
        $categoryIds = SectionCategory::pluck('category_id')->unique()->values()->toArray();
        $data = Category::whereNotIn('id', $categoryIds)->get();

        return Response::json([
            'status' => '200',
            'message' => 'Unassigned category list.',
            'data' => $data
        ], 200);
    }

}
