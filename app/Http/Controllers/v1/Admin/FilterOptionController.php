<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\FilterOption;
use Response;

class FilterOptionController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make(request()->all(), [

            'filter_id'=>'required',

            'title'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'Title are requeired'
            ], 422);

        }else{
            $filteroption = new FilterOption;
            $filteroption->filter_id = $request->filter_id;
            $filteroption->title = $request->title;
            $filteroption->description = $request->description;
            $filteroption->status = $request->status;
            $filteroption->save();

            if($filteroption){
                return Response::json([
                    'status' => '201',
                    'message' => 'Filter Option created successfully'
                ], 201);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Filter option create request fail'
                ], 401);
            }
        }
    }
}
