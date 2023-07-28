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
    public function store(Request $request){

        $validator = Validator::make(request()->all(), [

            'title'=>'required',

            'image'=>'required',

            'status'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'error_code' => '1007',
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{

            $image = $request->file('image');

            $name = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/images/brand');

            $image->move($destinationPath,$name);

            $keyword = Str::slug($request->title);

            $category = new Brand;
            $category->title = $request->title;
            $category->description = $request->description;
            $category->image = $name;
            $category->keyword = $keyword;
            $category->status = $request->status;
            $category->save();
            if($category){
                return Response::json([
                    'error_code' => '1002',
                    'status' => '200',
                    'message' => 'Brand data has been saved'
                ], 200);
            }else{
                return Response::json([
                    'error_code' => '1001',
                    'status' => '401',
                    'message' => 'Brand data has been not saved'
                ], 401);
            }
        }
    }
}
