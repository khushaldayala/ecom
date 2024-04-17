<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IntroScreen;
use Illuminate\Support\Facades\Response;

class IntroScreenController extends Controller
{
    public function handle(){
        $introscreen_order = IntroScreen::pluck('order');
        if($introscreen_order){
            return Response::json([
                'status' => '200',
                'message' => 'IntroScreens Orders no get successfully',
                'data' => $introscreen_order
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'No existing order numbers found.'
            ], 404);
        }
    }
    public function intro_screens(){
        $introscreen = IntroScreen::all();
        if($introscreen){
            return Response::json([
                'status' => '200',
                'message' => 'IntroScreens list get successfully',
                'data' => $introscreen
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'IntroScreens data not found'
            ], 404);
        }
    }
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
                'status' => '200',
                'message' => 'Intro screen data has been saved'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Intro screen data has been not saved'
            ], 401);
        }
    }
    public function get_single_intro_screen($id){
        $introScreen = IntroScreen::findorfail($id);
        if($introScreen){
            return Response::json([
                'status' => '200',
                'message' => 'Introscreen data get successfully',
                'data' => $introScreen
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Introscreen data not found'
            ], 404);
        }
    }
    public function update(Request $request, $id){

        if($request->hasFile('image')){
            $image = $request->file('image');

            $name = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/images/introscreen');

            $image->move($destinationPath,$name);
        };

        $intro = IntroScreen::find($id);
        $intro->title = $request->title;
        $intro->description = $request->description;
        if($request->hasFile('image')){
            $intro->image = $name;
        }
        $intro->order = $request->order;
        $intro->status = $request->status;
        $intro->save();
        if($intro){
            return Response::json([
                'status' => '200',
                'message' => 'Intro screen data has been updated'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Intro screen data has been not updated'
            ], 401);
        }
    }
    public function delete($id){
        $introScreen = IntroScreen::find($id);
        $introScreen->delete();
        if($introScreen){
            return Response::json([
                'status' => '200',
                'message' => 'Intro screen data move to trash successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Intro screen data has been not move in trash'
            ], 401);
        }
    }

    // Trash data section
    public function trash_intro_screen(){
        $introScreen = IntroScreen::onlyTrashed()->get();
        if($introScreen){
            return Response::json([
                'status' => '200',
                'message' => 'Trash introscreens list get successfully',
                'data' => $introScreen
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Trash introscreens data not found'
            ], 404);
        }
    }
    public function trash_intro_screen_restore($id){
        $introScreen = IntroScreen::onlyTrashed()->findOrFail($id);
        $introScreen->restore();
        if($introScreen){
            return Response::json([
                'status' => '200',
                'message' => 'introscreen data restored successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'introscreen data has been not restored'
            ], 401);
        }
    }
    public function trash_intro_screen_delete($id){
        $introScreen = IntroScreen::onlyTrashed()->findOrFail($id);
        $introScreen->forceDelete();
        if($introScreen){
            return Response::json([
                'status' => '200',
                'message' => 'Trash intro screen data deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'intro screen data has been not deleted'
            ], 401);
        }
    }
    public function all_trash_intro_screen_delete(){
        $introScreen = IntroScreen::onlyTrashed()->forceDelete();
        if($introScreen){
            return Response::json([
                'status' => '200',
                'message' => 'All Trash intro screen deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'intro screen has been not deleted'
            ], 401);
        }
    }
}
