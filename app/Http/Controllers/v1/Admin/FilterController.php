<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Filter;
use Response;

class FilterController extends Controller
{
    public function filters(){
        $filter = Filter::all();
        if($filter){
            return Response::json([
                'status' => '200',
                'message' => 'Filters list get successfully',
                'data' => $filter
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Filters data not found'
            ], 404);
        }
    }
    public function store(Request $request){
        $validator = Validator::make(request()->all(), [

            'title'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'Title are requeired'
            ], 422);

        }else{
            $filter = new Filter;
            $filter->title = $request->title;
            $filter->description = $request->description;
            $filter->status = $request->status;
            $filter->save();

            if($filter){
                return Response::json([
                    'status' => '201',
                    'message' => 'Filter created successfully'
                ], 201);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Filter create request fail'
                ], 401);
            }
        }
    }
}
