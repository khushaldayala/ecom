<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterStoreRequest;
use App\Http\Requests\FilterUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Filter;
use App\Traits\AttributeTrait;
use Illuminate\Support\Facades\Response;

class FilterController extends Controller
{
    use AttributeTrait;

    public function filters(){
        $filter = Filter::with('attributes')->paginate(10);
        if($filter){
            return Response::json([
                'status' => '200',
                'message' => 'Attributes list get successfully',
                'data' => $filter
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Attributes data not found'
            ], 404);
        }
    }
    public function store(FilterStoreRequest $request){
        $filter = new Filter;
        $filter->title = $request->title;
        $filter->display_name = $request->display_name;
        $filter->status = $request->status;
        $filter->save();

        if(isset($request->attributeValue) && $request->attributeValue)
        {
            $this->addAttributesValue($filter, $request);
        }

        if($filter){
            return Response::json([
                'filter_id' => $filter->id,
                'status' => '201',
                'message' => 'Attribute created successfully'
            ], 201);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Attribute create request fail'
            ], 401);
        }
    }
    public function get_single_filter($id){
        $filter = Filter::with('attributes')->findorfail($id);
        if($filter){
            return Response::json([
                'status' => '200',
                'message' => 'Attribute data get successfully',
                'data' => $filter
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Attribute data not found'
            ], 404);
        }
    }
    public function update(FilterUpdateRequest $request, $id){
        $filter = Filter::find($id);
        $filter->title = $request->title;
        $filter->display_name = $request->display_name;
        $filter->status = $request->status;
        $filter->save();

        $this->updateAttributesValue($filter, $request);

        if($filter){
            return Response::json([
                'status' => '201',
                'message' => 'Attribute updated successfully'
            ], 201);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Attribute updated request fail'
            ], 401);
        }
    }
    public function delete($id){
        $filter = Filter::find($id);
        $filter->attributes()->delete();
        $filter->delete();
        if($filter){
            return Response::json([
                'status' => '200',
                'message' => 'Attribute data move to trash successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Attribute data has been not move in trash'
            ], 401);
        }
    }

    // Trash data section
    public function trash_filter(){
        $filter = Filter::onlyTrashed()->get();
        if($filter){
            return Response::json([
                'status' => '200',
                'message' => 'Trash Attribute list get successfully',
                'data' => $filter
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Trash Attribute data not found'
            ], 404);
        }
    }
    public function trash_filter_restore($id){
        $filter = Filter::onlyTrashed()->findOrFail($id);
        $filter->restore();
        if($filter){
            return Response::json([
                'status' => '200',
                'message' => 'Attribute data restored successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Attribute data has been not restored'
            ], 401);
        }
    }
    public function trash_filter_delete($id){
        $filter = Filter::onlyTrashed()->findOrFail($id);
        $filter->forceDelete();
        if($filter){
            return Response::json([
                'status' => '200',
                'message' => 'Trash Attribute data deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Attribute data has been not deleted'
            ], 401);
        }
    }
    public function all_trash_filter_delete(){
        $filter = Filter::onlyTrashed()->forceDelete();
        if($filter){
            return Response::json([
                'status' => '200',
                'message' => 'All Trash Attributes deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Attributes has been not deleted'
            ], 401);
        }
    }
}
