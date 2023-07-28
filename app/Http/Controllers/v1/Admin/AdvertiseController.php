<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Models\Advertise;

class AdvertiseController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make(request()->all(), [

            'title'=>'required',

            'image'=>'required',

            'link'=>'required',

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

            $destinationPath = public_path('/images/advertises');

            $image->move($destinationPath,$name);

            $advertise = new advertise;
            $advertise->section_id = $request->section_id;
            $advertise->title = $request->title;
            $advertise->description = $request->description;
            $advertise->image = $name;
            $advertise->link = $request->link;
            $advertise->status = $request->status;
            $advertise->save();
            if($advertise){
                return Response::json([
                    'error_code' => '1002',
                    'status' => '200',
                    'message' => 'advertise data has been saved'
                ], 200);
            }else{
                return Response::json([
                    'error_code' => '1001',
                    'status' => '401',
                    'message' => 'advertise data has been not saved'
                ], 401);
            }
        }
    }
}
