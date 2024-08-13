<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IntroSctreenStoreRequest;
use App\Http\Requests\IntroSctreenUpdateRequest;
use Illuminate\Http\Request;
use App\Models\IntroScreen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class IntroScreenController extends Controller
{
    public function handle(){
        $userId = Auth::id();
        $introscreen_order = IntroScreen::where('user_id', $userId)->pluck('order');

        return Response::json([
            'status' => '200',
            'message' => 'IntroScreens Orders no get successfully',
            'data' => $introscreen_order
        ], 200);
    }
    public function intro_screens(){
        $userId = Auth::id();
        $introscreen = IntroScreen::where('user_id', $userId)->get();
        
        return Response::json([
            'status' => '200',
            'message' => 'IntroScreens list get successfully',
            'data' => $introscreen
        ], 200);
    }
    public function store(IntroSctreenStoreRequest $request){

        $userId = Auth::id();
        $image = $request->file('image');

        $name = time().'.'.$image->getClientOriginalExtension();

        $destinationPath = public_path('/images/introscreen');

        $image->move($destinationPath,$name);

        $intro = new IntroScreen;
        $intro->user_id = $userId;
        $intro->title = $request->title;
        $intro->description = $request->description;
        $intro->image = $name;
        $intro->order = $request->order;
        $intro->status = $request->status;
        $intro->save();

        return Response::json([
            'status' => '200',
            'message' => 'Intro screen data has been saved'
        ], 200);
    }

    public function get_single_intro_screen(IntroScreen $introScreen){

        return Response::json([
            'status' => '200',
            'message' => 'Introscreen data get successfully',
            'data' => $introScreen
        ], 200);
    }
    public function update(IntroSctreenUpdateRequest $request, $id){

        $userId = Auth::id();
        if($request->hasFile('image')){
            $image = $request->file('image');

            $name = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/images/introscreen');

            $image->move($destinationPath,$name);
        };

        $intro = IntroScreen::find($id);
        $intro->user_id = $userId;
        $intro->title = $request->title;
        $intro->description = $request->description;
        if($request->hasFile('image')){
            $intro->image = $name;
        }
        $intro->order = $request->order;
        $intro->status = $request->status;
        $intro->save();

        return Response::json([
            'status' => '200',
            'message' => 'Intro screen data has been updated'
        ], 200);
    }
    public function delete(IntroScreen $introScreen){
        $introScreen->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Intro screen data move to trash successfully'
        ], 200);
    }

    // Trash data section
    public function trash_intro_screen(){
        $userId = Auth::id();
        $introScreen = IntroScreen::where('user_id', $userId)->onlyTrashed()->get();

        return Response::json([
            'status' => '200',
            'message' => 'Trash introscreens list get successfully',
            'data' => $introScreen
        ], 200);
    }
    public function trash_intro_screen_restore($introScreen){
        $introScreen = IntroScreen::onlyTrashed()->findOrFail($introScreen);
        $introScreen->restore();

        return Response::json([
            'status' => '200',
            'message' => 'introscreen data restored successfully'
        ], 200);
    }
    public function trash_intro_screen_delete($introScreen){
        $introScreen = IntroScreen::onlyTrashed()->findOrFail($introScreen);
        $introScreen->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'Trash intro screen data deleted successfully'
        ], 200);
    }
    public function all_trash_intro_screen_delete(){
        $userId = Auth::id();
        IntroScreen::where('user_id', $userId)->onlyTrashed()->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'All Trash intro screen deleted successfully'
        ], 200);
    }
}
