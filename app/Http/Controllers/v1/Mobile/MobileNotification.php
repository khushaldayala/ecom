<?php

namespace App\Http\Controllers\v1\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use App\Models\Notification;

class MobileNotification extends Controller
{
    public function notifications($id){
        $notification = Notification::where('user_id', $id)->get();
        if(count($notification)>0){
            return Response::json([
                'status' => '200',
                'message' => 'Get Notifications Success',
                'data' => $notification
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'No data found in notification'
            ], 404);
        }
    }
    public function notification_count($id){
        $notification = Notification::where('user_id', $id)->where('is_read','false')->count();
        if($notification){
            return Response::json([
                'status' => '200',
                'message' => 'Notification count',
                'count' => $notification
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'No count of Notification count',
                'count' => 0
            ], 404);
        }
    }
    public function notification_detail($id){
        $notification = Notification::findOrFail($id);
        $notification->is_read = 'true';
        $notification->save();
        if($notification){
            return Response::json([
                'status' => '200',
                'message' => 'Get Notifications Success',
                'data' => $notification
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'No data found in notification'
            ], 404);
        }
    }
}
