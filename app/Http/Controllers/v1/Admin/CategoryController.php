<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\Category;

class CategoryController extends Controller
{
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
    public function store(Request $request){
        $validator = Validator::make(request()->all(), [

            'title'=>'required',

            'image'=>'required',

            'status'=>'required',

            'portrait_image' => 'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{
            $image = $request->file('image');

            $name = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/images/categories');

            $image->move($destinationPath,$name);

            $image1 = $request->file('portrait_image');

            $name1 = time().'.'.$image1->getClientOriginalExtension();

            $destinationPath1 = public_path('/images/categories');

            $image1->move($destinationPath1,$name1);

            $category = new Category;
            $category->title = $request->title;
            $category->description = $request->description;
            $category->image = $name;
            $category->portrait_image = $name1;
            $category->status = $request->status;
            $category->section_id = $request->section_id;
            $category->save();
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
    }
    public function get_single_category($id){
        $category = Category::findorfail($id);
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
    public function update(Request $request, $id){
        $validator = Validator::make(request()->all(), [

            'title'=>'required',

            'status'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{
            if($request->hasFile('image')){
                $image = $request->file('image');

                $name = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/images/categories');

                $image->move($destinationPath,$name);
            }
            if($request->hasFile('portrait_image')){
                $image1 = $request->file('portrait_image');

                $name1 = time().'.'.$image1->getClientOriginalExtension();

                $destinationPath1 = public_path('/images/categories');

                $image1->move($destinationPath1,$name1);
            }
            $category = Category::find($id);
            $category->title = $request->title;
            $category->description = $request->description;
            if($request->hasFile('image')){
                $category->image = $name;
            };
            if($request->hasFile('portrait_image')){
                $category->portrait_image = $name1;
            }
            $category->status = $request->status;
            $category->section_id = $request->section_id;
            $category->save();
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
    }
    public function delete($id){
        $category = Category::find($id);
        $category->delete();
        $category->subcategory()->update(['category_id'=>null]);
        $category->fabric()->update(['category_id'=>null]);
        $category->products()->update(['category_id'=>null]);
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

}
