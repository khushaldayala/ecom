<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\Variant;

class VariantController extends Controller
{
    public function variants(){
        $variant = Variant::all();
        if($variant){
            return Response::json([
                'status' => '200',
                'message' => 'Variants list get successfully',
                'data' => $variant
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Variants data not found'
            ], 404);
        }
    }
    public function store(Request $request){
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
            $variant = new Variant;
            $variant->title = $request->title;
            $variant->status = $request->status;
            $variant->save();
            if($variant){
                return Response::json([
                    'variant_id' => $variant->id,
                    'status' => '200',
                    'message' => 'variant data has been saved'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'variant data has been not saved'
                ], 401);
            }
        }
    }
    public function get_single_variant($id){
        $variant = Variant::findorfail($id);
        if($variant){
            return Response::json([
                'status' => '200',
                'message' => 'Variant data get successfully',
                'data' => $variant
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Variant data not found'
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
            $variant = Variant::find($id);
            $variant->title = $request->title;
            $variant->status = $request->status;
            $variant->save();
            if($variant){
                return Response::json([
                    'status' => '200',
                    'message' => 'variant data has been updated'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'variant data has been not updated'
                ], 401);
            }
        }
    }
    public function delete($id){
        $variant = Variant::find($id);
        $variant->delete();
        $variant->variantoptions()->delete();
        if($variant){
            return Response::json([
                'status' => '200',
                'message' => 'variant move to trash successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'variant has been not move in trash'
            ], 401);
        }
    }

    // Trash data section
    public function trash_variant(){
        $variant = Variant::onlyTrashed()->get();
        if($variant){
            return Response::json([
                'status' => '200',
                'message' => 'Trash Variants list get successfully',
                'data' => $variant
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Trash Variants data not found'
            ], 404);
        }
    }
    public function trash_variant_restore($id){
        $variant = Variant::onlyTrashed()->findOrFail($id);
        $variant->restore();
        $variant->variantoptions()->restore();
        if($variant){
            return Response::json([
                'status' => '200',
                'message' => 'Variant restored successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Variant has been not restored'
            ], 401);
        }
    }
    public function trash_variant_delete($id){
        $variant = Variant::onlyTrashed()->findOrFail($id);
        $variant->forceDelete();
        if($variant){
            return Response::json([
                'status' => '200',
                'message' => 'Trash Variant deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Variant has been not deleted'
            ], 401);
        }
    }
    public function all_trash_variant_delete(){
        $variant = Variant::onlyTrashed()->forceDelete();
        if($variant){
            return Response::json([
                'status' => '200',
                'message' => 'All Trash Variants deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Variants has been not deleted'
            ], 401);
        }
    }
}
