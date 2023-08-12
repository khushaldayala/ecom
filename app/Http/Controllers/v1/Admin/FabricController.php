<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Models\Fabric;

class FabricController extends Controller
{
    public function fabrics()
    {
        $fabrics = Fabric::all();
        if($fabrics){
            return Response::json([
                'status' => '200',
                'message' => 'Fabrics list get successfully',
                'data' => $fabrics
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Fabrics data not found'
            ], 404);
        }
    }
    public function store(Request $request){
        $validator = Validator::make(request()->all(), [

            'fab_title'=>'required',

            'category_id'=>'required',

            'status'=>'required'

        ]);
        
        if ($validator->fails()) {
            return Response::json([
                'error_code' => '1007',
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);
            
        }else{
            $fabric = new Fabric;
            $fabric->fab_title = $request->fab_title;
            $fabric->category_id = $request->category_id;
            $fabric->status = $request->status;
            $fabric->save();
            if($fabric){
                return Response::json([
                    'error_code' => '1002',
                    'status' => '200',
                    'message' => 'Fabric data has been saved'
                ], 200);
            }else{
                return Response::json([
                    'error_code' => '1001',
                    'status' => '401',
                    'message' => 'Fabric data has been not saved'
                ], 401);
            }
        }
    }
    public function get_single_fabric($id){
        $fabric = Fabric::findorfail($id);
        if($fabric){
            return Response::json([
                'status' => '200',
                'message' => 'Fabric data get successfully',
                'data' => $fabric
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Fabric data not found'
            ], 404);
        }
    }
    public function update(Request $request, $id){
        $validator = Validator::make(request()->all(), [

            'fab_title'=>'required',

            'category_id'=>'required',

            'status'=>'required'

        ]);
        
        if ($validator->fails()) {
            return Response::json([
                'error_code' => '1007',
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);
            
        }else{
            $fabric = Fabric::find($id);
            $fabric->fab_title = $request->fab_title;
            $fabric->category_id = $request->category_id;
            $fabric->status = $request->status;
            $fabric->save();
            if($fabric){
                return Response::json([
                    'error_code' => '1002',
                    'status' => '200',
                    'message' => 'Fabric data has been Updated'
                ], 200);
            }else{
                return Response::json([
                    'error_code' => '1001',
                    'status' => '401',
                    'message' => 'Fabric data has been not Updated'
                ], 401);
            }
        }
    }
    public function delete($id){
        $fabric = Fabric::find($id);
        $fabric->delete();
        if($fabric){
            return Response::json([
                'status' => '200',
                'message' => 'Fabric move to trash successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Fabric has been not move in trash'
            ], 401);
        }
    }

    // Trash data section
    public function trash_fabric(){
        $fabric = Fabric::onlyTrashed()->get();
        if($fabric){
            return Response::json([
                'status' => '200',
                'message' => 'Trash fabric list get successfully',
                'data' => $fabric
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Trash fabric data not found'
            ], 404);
        }
    }
    public function trash_fabric_restore($id){
        $fabric = Fabric::onlyTrashed()->findOrFail($id);
        $fabric->restore();
        if($fabric){
            return Response::json([
                'status' => '200',
                'message' => 'Fabric restored successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Fabric has been not restored'
            ], 401);
        }
    }
    public function trash_fabric_delete($id){
        $fabric = Fabric::onlyTrashed()->findOrFail($id);
        $fabric->forceDelete();
        if($fabric){
            return Response::json([
                'status' => '200',
                'message' => 'Trash Fabric deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Fabric has been not deleted'
            ], 401);
        }
    }
    public function all_trash_fabric_delete(){
        $fabric = Fabric::onlyTrashed()->forceDelete();
        if($fabric){
            return Response::json([
                'status' => '200',
                'message' => 'All Trash fabric deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'fabric has been not deleted'
            ], 401);
        }
    }
}
