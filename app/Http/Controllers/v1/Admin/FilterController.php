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
    public function get_single_filter($id){
        $filter = Filter::findorfail($id);
        if($filter){
            return Response::json([
                'status' => '200',
                'message' => 'Filter data get successfully',
                'data' => $filter
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Filter data not found'
            ], 404);
        }
    }
    public function update(Request $request, $id){

        $validator = Validator::make(request()->all(), [

            'title'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'Title are requeired'
            ], 422);

        }else{
            $filter = Filter::find($id);
            $filter->title = $request->title;
            $filter->description = $request->description;
            $filter->status = $request->status;
            $filter->save();

            if($filter){
                return Response::json([
                    'status' => '201',
                    'message' => 'Filter updated successfully'
                ], 201);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Filter updated request fail'
                ], 401);
            }
        }
    }
    public function delete($id){
        $filter = Filter::find($id);
        $filter->delete();
        $filter->filteroptions()->delete();
        if($filter){
            return Response::json([
                'status' => '200',
                'message' => 'Filter data move to trash successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Filter data has been not move in trash'
            ], 401);
        }
    }

    // Trash data section
    public function trash_filter(){
        $filter = Filter::onlyTrashed()->get();
        if($filter){
            return Response::json([
                'status' => '200',
                'message' => 'Trash filter list get successfully',
                'data' => $filter
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Trash filter data not found'
            ], 404);
        }
    }
    public function trash_filter_restore($id){
        $filter = Filter::onlyTrashed()->findOrFail($id);
        $filter->restore();
        if($filter){
            return Response::json([
                'status' => '200',
                'message' => 'Filter data restored successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Filter data has been not restored'
            ], 401);
        }
    }
    public function trash_filter_delete($id){
        $filter = Filter::onlyTrashed()->findOrFail($id);
        $filter->forceDelete();
        if($filter){
            return Response::json([
                'status' => '200',
                'message' => 'Trash Filter data deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Filter data has been not deleted'
            ], 401);
        }
    }
    public function all_trash_filter_delete(){
        $filter = Filter::onlyTrashed()->forceDelete();
        if($filter){
            return Response::json([
                'status' => '200',
                'message' => 'All Trash filter deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'filter has been not deleted'
            ], 401);
        }
    }
}
