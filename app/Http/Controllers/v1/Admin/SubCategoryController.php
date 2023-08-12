<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Models\Subcategory;

class SubCategoryController extends Controller
{
    public function sub_categories()
    {
        $sub_category = Subcategory::get();
        if($sub_category){
            return Response::json([
                'status' => '200',
                'message' => 'Sub-category list get successfully',
                'data' => $sub_category
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Sub-category data not found'
            ], 404);
        }
    }
    public function store(Request $request){
        $validator = Validator::make(request()->all(), [

            'category_id'=>'required',

            'fabric_id'=>'required',

            'sub_title'=>'required',

            'sub_image'=>'required',

            'status'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'error_code' => '1007',
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{
            $image = $request->file('sub_image');

            $name = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/images/subcategory');

            $image->move($destinationPath,$name);

            $subcategory = new Subcategory;
            $subcategory->category_id = $request->category_id;
            $subcategory->fabric_id = $request->fabric_id;
            $subcategory->sub_image = $name;
            $subcategory->sub_title = $request->sub_title;
            $subcategory->sub_description = $request->sub_description;
            $subcategory->status = $request->status;
            $subcategory->save();
            if($subcategory){
                return Response::json([
                    'error_code' => '1002',
                    'status' => '200',
                    'message' => 'Sub-category data has been saved'
                ], 200);
            }else{
                return Response::json([
                    'error_code' => '1001',
                    'status' => '401',
                    'message' => 'Sub-category data has been not saved'
                ], 401);
            }
        }
    }
    public function get_single_subcategory($id){
        $sub_category = Subcategory::findorfail($id);
        if($sub_category){
            return Response::json([
                'status' => '200',
                'message' => 'Sub-category data get successfully',
                'data' => $sub_category
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Sub-category data not found'
            ], 404);
        }
    }
    public function update(Request $request, $id){
        $validator = Validator::make(request()->all(), [

            'category_id'=>'required',

            'fabric_id'=>'required',

            'sub_title'=>'required',

            'status'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'error_code' => '1007',
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{
            if($request->hasFile('sub_image')){

                $image = $request->file('sub_image');

                $name = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/images/subcategory');

                $image->move($destinationPath,$name);
            }
            $subcategory = Subcategory::find($id);
            $subcategory->category_id = $request->category_id;
            $subcategory->fabric_id = $request->fabric_id;
            if($request->hasFile('sub_image')){
                $subcategory->sub_image = $name;
            }
            $subcategory->sub_title = $request->sub_title;
            $subcategory->sub_description = $request->sub_description;
            $subcategory->status = $request->status;
            $subcategory->save();
            if($subcategory){
                return Response::json([
                    'error_code' => '1002',
                    'status' => '200',
                    'message' => 'Sub-category data has been Updated'
                ], 200);
            }else{
                return Response::json([
                    'error_code' => '1001',
                    'status' => '401',
                    'message' => 'Sub-category data has been not Updated'
                ], 401);
            }
        }
    }
    public function delete($id){
        $sub_category = Subcategory::find($id);
        $sub_category->delete();
        if($sub_category){
            return Response::json([
                'status' => '200',
                'message' => 'Sub-category move to trash successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Sub-category has been not move in trash'
            ], 401);
        }
    }

    // Trash data section
    public function trash_subcategory(){
        $sub_category = Subcategory::onlyTrashed()->get();
        if($sub_category){
            return Response::json([
                'status' => '200',
                'message' => 'Trash Sub-category list get successfully',
                'data' => $sub_category
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Trash Sub-category data not found'
            ], 404);
        }
    }
    public function trash_subcategory_restore($id){
        $sub_category = Subcategory::onlyTrashed()->findOrFail($id);
        $sub_category->restore();
        if($sub_category){
            return Response::json([
                'status' => '200',
                'message' => 'Sub-category restored successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Sub-category has been not restored'
            ], 401);
        }
    }
    public function trash_subcategory_delete($id){
        $sub_category = Subcategory::onlyTrashed()->findOrFail($id);
        $sub_category->forceDelete();
        if($sub_category){
            return Response::json([
                'status' => '200',
                'message' => 'Trash Sub-category deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Sub-category has been not deleted'
            ], 401);
        }
    }
    public function all_trash_subcategory_delete(){
        $sub_category = Subcategory::onlyTrashed()->forceDelete();
        if($sub_category){
            return Response::json([
                'status' => '200',
                'message' => 'All Trash Sub-category deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Sub-category has been not deleted'
            ], 401);
        }
    }
}