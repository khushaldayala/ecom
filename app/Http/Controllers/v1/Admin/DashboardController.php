<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(){

        $revenue = 1000000;
        $monthly_order = 50;
        $products = 500;
        $release_schedule_date = '15/12/2023';
        $users = 1000;

        $data = array();
        $data = [
            'revenue' => $revenue,
            'monthly_order' => $monthly_order,
            'products' => $products,
            'release_schedule_date' => $release_schedule_date,
            'users' => $users
        ];

        return Response::json([
            'status' => '200',
            'message' => 'Dashboard data list get successfully',
            'data' => $data
        ], 200);
    }
    
    public function login(Request $request){

        $validator = Validator::make(request()->all(), [

            'email'=>'required',

            'password'=>'required',

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'Email and password are required'
            ], 422);
        }else{

            $user = User::where('email', $request->email)->where('status','active')->first();
            if($user){
                $check_pass = Hash::check(request('password'), $user->password);
                $randomString = Str::random(90);
                if($check_pass){
                    // $user_id = $user->id;
                    // $data = User::find($user_id);
                    // $data->update();

                    $token = $user->createToken('my-app-token')->plainTextToken;

                    return Response::json([
                        'status' => '200',
                        'message' => 'Login successfully',
                        'user' => $user,
                        'token' => $token
                    ], 200);
                }else{
                    return Response::json([
                    'status' => '401',
                    'message' => 'Invalid credentials. Please try again.'
                ], 401);
                }
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Invalid credentials. Please try again.'
                ], 401);
            }
        }
    }
}
