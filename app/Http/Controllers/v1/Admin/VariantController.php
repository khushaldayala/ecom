<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Models\Variant;

class VariantController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make(request()->all(), [

            'title'=>'required',

            'status'=>'required'

        ]);
        
        if ($validator->fails()) {
            return Response::json([
                'error_code' => '1007',
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
                    'error_code' => '1002',
                    'status' => '200',
                    'message' => 'variant data has been saved'
                ], 200);
            }else{
                return Response::json([
                    'error_code' => '1001',
                    'status' => '401',
                    'message' => 'variant data has been not saved'
                ], 401);
            }
        }
    }
}
