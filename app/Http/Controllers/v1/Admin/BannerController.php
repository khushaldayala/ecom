<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\Banner;

class BannerController extends Controller
{
    public function banners(){
        $banner = Banner::all();
        if($banner){
            return Response::json([
                'status' => '200',
                'message' => 'Banners list get successfully',
                'data' => $banner
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Banners data not found'
            ], 404);
        }
    }
    public function store(Request $request){

        $validator = Validator::make(request()->all(), [

            'title'=>'required',

            'image'=>'required',

            'showtype'=>'required',

            'status'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{
            $image = $request->file('image');

            $name = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/images/banners');

            $image->move($destinationPath,$name);

            $banner = new Banner;
            $banner->section_id = $request->section_id;
            $banner->title = $request->title;
            $banner->description = $request->description;
            $banner->image = $name;
            $banner->showtype = $request->showtype;
            $banner->status = $request->status;
            $banner->save();
            if($banner){
                return Response::json([
                    'status' => '200',
                    'message' => 'banner data has been saved'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'banner data has been not saved'
                ], 401);
            }
        }
    }
    public function get_single_banner($id){
        $banner = Banner::findorfail($id);
        if($banner){
            return Response::json([
                'status' => '200',
                'message' => 'Banner data get successfully',
                'data' => $banner
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Banners data not found'
            ], 404);
        }
    }
    public function update(Request $request, $id){
        $validator = Validator::make(request()->all(), [

            'title'=>'required',

            'showtype'=>'required',

            'status'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{

            if($request->hasFile('image')){
                $image = $request->file('image');

                $name = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/images/banners');

                $image->move($destinationPath,$name);
            }

            $banner = Banner::findOrFail($id);
            $banner->section_id = $request->section_id;
            $banner->title = $request->title;
            $banner->description = $request->description;
            if($request->hasFile('image')){
                $banner->image = $name;
            }
            $banner->showtype = $request->showtype;
            $banner->status = $request->status;
            $banner->save();
            if($banner){
                return Response::json([
                    'status' => '200',
                    'message' => 'Banner data updated successfully'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Banners has been not updated'
                ], 401);
            }
        }
    }
    public function delete($id){
        $banner = Banner::find($id);
        $banner->delete();
        if($banner){
            return Response::json([
                'status' => '200',
                'message' => 'Banner move to trash successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Banners has been not move in trash'
            ], 401);
        }
    }

    // Trash data section
    public function trash_banners(){
        $banner = Banner::onlyTrashed()->get();
        if($banner){
            return Response::json([
                'status' => '200',
                'message' => 'Trash banners list get successfully',
                'data' => $banner
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Trash banners data not found'
            ], 404);
        }
    }
    public function trash_restore($id){
        $banner = Banner::onlyTrashed()->findOrFail($id);
        $banner->restore();
        if($banner){
            return Response::json([
                'status' => '200',
                'message' => 'Banner restored successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Banners has been not restored'
            ], 401);
        }
    }
    public function trash_delete($id){
        $banner = Banner::onlyTrashed()->findOrFail($id);
        $banner->forceDelete();
        if($banner){
            return Response::json([
                'status' => '200',
                'message' => 'Trash banner deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Banners has been not deleted'
            ], 401);
        }
    }
    public function all_trash_delete(){
        $banner = Banner::onlyTrashed()->forceDelete();
        if($banner){
            return Response::json([
                'status' => '200',
                'message' => 'All Trash Banner deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Banners has been not deleted'
            ], 401);
        }
    }
}
