<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\FilterOption;
use Illuminate\Support\Facades\Response;

class FilterOptionController extends Controller
{
    public function filteroptions(){
        $filterOption = FilterOption::all();
        if($filterOption){
            return Response::json([
                'status' => '200',
                'message' => 'Filter options list get successfully',
                'data' => $filterOption
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
    public function get_single_filteroption($id){
        $filterOptions = FilterOption::findorfail($id);
        if($filterOptions){
            return Response::json([
                'status' => '200',
                'message' => 'Filter option data get successfully',
                'data' => $filterOptions
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Filter option data not found'
            ], 404);
        }
    }
    public function update(Request $request, $id){

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
            $filteroption = FilterOption::find($id);
            $filteroption->filter_id = $request->filter_id;
            $filteroption->title = $request->title;
            $filteroption->description = $request->description;
            $filteroption->status = $request->status;
            $filteroption->save();

            if($filteroption){
                return Response::json([
                    'status' => '201',
                    'message' => 'Filter Option updated successfully'
                ], 201);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Filter option updated request fail'
                ], 401);
            }
        }
    }
    public function delete($id){
        $filterOption = FilterOption::find($id);
        $filterOption->delete();
        if($filterOption){
            return Response::json([
                'status' => '200',
                'message' => 'Filter option data move to trash successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Filter option data has been not move in trash'
            ], 401);
        }
    }

    // Trash data section
    public function trash_filteroption(){
        $filterOption = FilterOption::onlyTrashed()->get();
        if($filterOption){
            return Response::json([
                'status' => '200',
                'message' => 'Trash filter option list get successfully',
                'data' => $filterOption
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Trash filter option data not found'
            ], 404);
        }
    }
    public function trash_filteroption_restore($id){
        $filterOption = FilterOption::onlyTrashed()->findOrFail($id);
        $filterOption->restore();
        if($filterOption){
            return Response::json([
                'status' => '200',
                'message' => 'Filter option data restored successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Filter option data has been not restored'
            ], 401);
        }
    }
    public function trash_filteroption_delete($id){
        $filterOption = FilterOption::onlyTrashed()->findOrFail($id);
        $filterOption->forceDelete();
        if($filterOption){
            return Response::json([
                'status' => '200',
                'message' => 'Trash Filter option data deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Filter option data has been not deleted'
            ], 401);
        }
    }
    public function all_trash_filteroption_delete(){
        $filterOption = FilterOption::onlyTrashed()->forceDelete();
        if($filterOption){
            return Response::json([
                'status' => '200',
                'message' => 'All Trash filter option deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'filter option has been not deleted'
            ], 401);
        }
    }

}
