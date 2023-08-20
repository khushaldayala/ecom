<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;
use Response;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function brands(){
        $brand = Brand::all();
        if($brand){
            return Response::json([
                'status' => '200',
                'message' => 'Brands list get successfully',
                'data' => $brand
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Brands data not found'
            ], 404);
        }
    }
    public function store(Request $request){

        $validator = Validator::make(request()->all(), [

            'title'=>'required',

            'image'=>'required',

            'status'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{

            $image = $request->file('image');

            $name = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/images/brand');

            $image->move($destinationPath,$name);

            $keyword = Str::slug($request->title);

            $brand = new Brand;
            $brand->title = $request->title;
            $brand->description = $request->description;
            $brand->image = $name;
            $brand->keyword = $keyword;
            $brand->status = $request->status;
            $brand->section_id = $request->section_id;
            $brand->save();
            if($brand){
                return Response::json([
                    'status' => '200',
                    'message' => 'Brand data has been saved'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Brand data has been not saved'
                ], 401);
            }
        }
    }
    public function get_single_brand($id){
        $brand = Brand::findorfail($id);
        if($brand){
            return Response::json([
                'status' => '200',
                'message' => 'brand data get successfully',
                'data' => $brand
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'brand data not found'
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

                $destinationPath = public_path('/images/brand');

                $image->move($destinationPath,$name);
            }

            $keyword = Str::slug($request->title);

            $brand = Brand::find($id);
            $brand->title = $request->title;
            $brand->description = $request->description;
            if($request->hasFile('image')){
                $brand->image = $name;
            }
            $brand->keyword = $keyword;
            $brand->status = $request->status;
            $brand->section_id = $request->section_id;
            $brand->save();
            if($brand){
                return Response::json([
                    'status' => '200',
                    'message' => 'Brand data has been updated'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Brand data has been not updated'
                ], 401);
            }
        }
    }
    public function delete($id){
        $brand = Brand::find($id);
        $brand->delete();
        $brand->products()->update(['brand_id'=>null]);
        if($brand){
            return Response::json([
                'status' => '200',
                'message' => 'brand move to trash successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'brand has been not move in trash'
            ], 401);
        }
    }

    // Trash data section
    public function trash_brand(){
        $brand = Brand::onlyTrashed()->get();
        if($brand){
            return Response::json([
                'status' => '200',
                'message' => 'Trash brands list get successfully',
                'data' => $brand
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Trash brands data not found'
            ], 404);
        }
    }
    public function trash_brand_restore($id){
        $brand = Brand::onlyTrashed()->findOrFail($id);
        $brand->restore();
        if($brand){
            return Response::json([
                'status' => '200',
                'message' => 'brand restored successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'brand has been not restored'
            ], 401);
        }
    }
    public function trash_brand_delete($id){
        $brand = Brand::onlyTrashed()->findOrFail($id);
        $brand->forceDelete();
        if($brand){
            return Response::json([
                'status' => '200',
                'message' => 'Trash brand deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'brand has been not deleted'
            ], 401);
        }
    }
    public function all_trash_brand_delete(){
        $brand = Brand::onlyTrashed()->forceDelete();
        if($brand){
            return Response::json([
                'status' => '200',
                'message' => 'All Trash brands deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'brands has been not deleted'
            ], 401);
        }
    }
}
