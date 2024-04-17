<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VariantOptionStoreRequest;
use App\Http\Requests\VariantOptionUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\VariantOption;

class VariantOptionController extends Controller
{
    public function variant_options(){
        $variant_options = VariantOption::all();
        if($variant_options){
            return Response::json([
                'status' => '200',
                'message' => 'Variant Options list get successfully',
                'data' => $variant_options
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Variant Options data not found'
            ], 404);
        }
    }
    public function store(VariantOptionStoreRequest $request){
       
        $variantoption = new VariantOption;
        $variantoption->variant_id = $request->variant_id;
        $variantoption->option = $request->option;
        $variantoption->status = $request->status;
        $variantoption->save();
        if($variantoption){
            return Response::json([
                'status' => '200',
                'message' => 'Variant Option data has been saved'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Variant Option data has been not saved'
            ], 401);
        }
    }
    public function get_single_variant_option($id){
        $variant_option = VariantOption::findorfail($id);
        if($variant_option){
            return Response::json([
                'status' => '200',
                'message' => 'Variant Option data get successfully',
                'data' => $variant_option
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Variant Option data not found'
            ], 404);
        }
    }
    public function update(VariantOptionUpdateRequest $request, $id){
        
        $variantoption = VariantOption::find($id);
        $variantoption->variant_id = $request->variant_id;
        $variantoption->option = $request->option;
        $variantoption->status = $request->status;
        $variantoption->save();
        if($variantoption){
            return Response::json([
                'status' => '200',
                'message' => 'Variant Option data has been updated'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Variant Option data has been not updated'
            ], 401);
        }
    }
    public function delete($id){
        $variant_option = VariantOption::find($id);
        $variant_option->delete();
        if($variant_option){
            return Response::json([
                'status' => '200',
                'message' => 'variant Option move to trash successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'variant Option has been not move in trash'
            ], 401);
        }
    }

    // Trash data section
    public function trash_variant_option(){
        $variant_option = VariantOption::onlyTrashed()->get();
        if($variant_option){
            return Response::json([
                'status' => '200',
                'message' => 'Trash Variants Options list get successfully',
                'data' => $variant_option
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Trash Variants Options data not found'
            ], 404);
        }
    }
    public function trash_variant_option_restore($id){
        $variant_option = VariantOption::onlyTrashed()->findOrFail($id);
        $variant_option->restore();
        if($variant_option){
            return Response::json([
                'status' => '200',
                'message' => 'Variant Option restored successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Variant Option has been not restored'
            ], 401);
        }
    }
    public function trash_variant_option_delete($id){
        $variant_option = VariantOption::onlyTrashed()->findOrFail($id);
        $variant_option->forceDelete();
        if($variant_option){
            return Response::json([
                'status' => '200',
                'message' => 'Trash Variant Option deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Variant Option has been not deleted'
            ], 401);
        }
    }
    public function all_trash_variant_option_delete(){
        $variant_option = VariantOption::onlyTrashed()->forceDelete();
        if($variant_option){
            return Response::json([
                'status' => '200',
                'message' => 'All Trash Variants Options deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Variants Options has been not deleted'
            ], 401);
        }
    }
}
