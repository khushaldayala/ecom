<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Models\VariantOption;

class VariantOptionController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make(request()->all(), [

            'variant_id'=>'required',

            'option'=>'required',

            'status'=>'required'

        ]);
        
        if ($validator->fails()) {
            return Response::json([
                'error_code' => '1007',
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);
            
        }else{
            $variantoption = new VariantOption;
            $variantoption->variant_id = $request->variant_id;
            $variantoption->option = $request->option;
            $variantoption->status = $request->status;
            $variantoption->save();
            if($variantoption){
                return Response::json([
                    'error_code' => '1002',
                    'status' => '200',
                    'message' => 'Variant Option data has been saved'
                ], 200);
            }else{
                return Response::json([
                    'error_code' => '1001',
                    'status' => '401',
                    'message' => 'Variant Option data has been not saved'
                ], 401);
            }
        }
    }
}
