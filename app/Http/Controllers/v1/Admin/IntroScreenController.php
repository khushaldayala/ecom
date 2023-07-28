<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IntroScreen;
use Response;

class IntroScreenController extends Controller
{
    public function store(Request $request){

        $image = $request->file('image');

        $name = time().'.'.$image->getClientOriginalExtension();

        $destinationPath = public_path('/images/introscreen');

        $image->move($destinationPath,$name);

        $intro = new IntroScreen;
        $intro->title = $request->title;
        $intro->description = $request->description;
        $intro->image = $name;
        $intro->order = $request->order;
        $intro->status = $request->status;
        $intro->save();
        if($intro){
            return Response::json([
                'error_code' => '1002',
                'status' => '200',
                'message' => 'Intro screen data has been saved'
            ], 200);
        }else{
            return Response::json([
                'error_code' => '1001',
                'status' => '401',
                'message' => 'Intro screen data has been not saved'
            ], 401);
        }
    }
}
