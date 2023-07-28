<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Models\Fabric;

class FabricController extends Controller
{
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
}
