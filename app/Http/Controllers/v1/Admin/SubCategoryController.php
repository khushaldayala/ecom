<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Models\Subcategory;

class SubCategoryController extends Controller
{
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
                    'message' => 'SubCategory data has been saved'
                ], 200);
            }else{
                return Response::json([
                    'error_code' => '1001',
                    'status' => '401',
                    'message' => 'SubCategory data has been not saved'
                ], 401);
            }
        }
    }
}
