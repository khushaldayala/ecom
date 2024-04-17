<?php

namespace App\Http\Controllers\v1\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use DateTime;
use Response;
use App\Models\User;
use Twilio\Rest\Client;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function send_otp(Request $req){
        $validator = Validator::make(request()->all(), [

            'phone_number'=>'required',

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'Mobile number are requeired'
            ], 422);

        }else{
            $users = User::where('phone_number',$req->phone_number)->where('status','active')->get();
            if(count($users)>0){
                return Response::json([
                        'status' => '409',
                        'message' => 'Mobile number already exists'
                    ], 409);
            }else{
                $digits = 4;
                $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);

                $user = new User;
                $user->phone_number = $req->phone_number;
                $user->otp = $otp;
                $user->status = 'inactive';
                $user->save();

                // $accountSid = getenv("TWILIO_SID");
                // $authToken = getenv("TWILIO_TOKEN");
                // $twilioNumber = getenv("TWILIO_FROM");

                // $receiver = '+91'.$req->phone_number;
                // $message = 'OTP '.$otp.' is used to verify your device for the E-commerce App.Message ID : #0+4ABKIxA#';
                // $client = new Client($accountSid, $authToken);

                // $client->messages->create($receiver, [
                //     'from' => $twilioNumber,
                //     'body' => $message
                // ]);

                if ($user){
                    return Response::json([
                        'otp' => $otp,
                        'status' => '200',
                        'message' => 'OTP has been sent successfully!'
                    ], 200);
                }else{
                    return Response::json([
                        'status' => '500',
                        'message' => 'OTP has been not sent'
                    ], 500);
                }
            }
        }
    }
    public function checkotp(Request $request){

        $validator = Validator::make(request()->all(), [

            'phone_number'=>'required',

            'otp'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);
        }else{
            $getotp = User::where('phone_number',$request->phone_number)->latest()->get();
            if(count($getotp)>0){
                $userId = $getotp[0]['id'];
                if($getotp[0]['otp'] == $request->otp){
                    return Response::json([
                        'userId' => $userId,
                        'status' => '200',
                        'message' => 'otp match successfully'
                    ], 200);

                }else{
                    return Response::json([
                        'status' => '401',
                        'message' => 'otp has been not match'
                    ], 401);
                }
            }else{
                return Response::json([
                    'status' => '404',
                    'message' => 'User account does not exist!'
                ], 404);
            }
        }

    }
    public function registration(Request $request){
        $validator = Validator::make(request()->all(), [

            'fname'=>'required',

            'lname'=>'required',

            'email'=>'required|unique:users',

            'password'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'All field are requeired and Email is must be unique'
            ], 422);

        }else{
            $version = $request->header('version');
            $id = $request->userId;
            $randomString = Str::random(90);

            $user = User::find($id);
            $user->name = $request->fname.' '.$request->lname;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->version = $version;
            $user->status = 'active';
            $user->save();

            $token = $user->createToken('my-app-token')->plainTextToken;

            if($user){
                return Response::json([
                    'status' => '200',
                    'message' => 'User registrater successfully',
                    'user' => $user,
                    'token' => $token
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'User registration request fail'
                ], 401);
            }
        }
    }
    public function resend_otp(Request $request){

        $validator = Validator::make(request()->all(), [

            'phone_number'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'Phone number are requeired'
            ], 422);
        }else{

           $getotp = User::where('phone_number',$request->phone_number)->orderBy('id', 'desc')->first();
            if($getotp){

                $otp = $getotp->otp;

                $phone_number = $getotp->phone_number;

                // $accountSid = getenv("TWILIO_SID");
                // $authToken = getenv("TWILIO_TOKEN");
                // $twilioNumber = getenv("TWILIO_FROM");

                // $receiver = '+91'.$request->phone_number;
                // $message = 'OTP '.$otp.' is used to verify your device for the E-commerce App.Message ID : #0+4ABKIxA#';
                // $client = new Client($accountSid, $authToken);

                // $client->messages->create($receiver, [
                //     'from' => $twilioNumber,
                //     'body' => $message
                // ]);

                return Response::json([
                    'otp' => $otp,
                    'status' => '200',
                    'message' => 'Otp resent successfully'
                ], 200);

            }else{
                return Response::json([
                    'status' => '404',
                    'message' => 'User account does not exist!'
                ], 404);
            }
        }
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
    public function logout(Request $request){

        auth()->user()->currentAccessToken()->delete();

        return Response::json([
            'status' => '200',
            'message' => 'User logout successfully'
        ], 201);

        // $user_id = $request->userId;

        // $user = User::find($user_id);
        // $user->fcm_token = NULL;
        // $user->update();

        // if($user){
        // }else{
        //     return Response::json([
        //         'status' => '401',{{}}
        //         'message' => 'Logout request faild'
        //     ], 401);
        // }
    }
    public function forgot_password(Request $request){
        $validator = Validator::make(request()->all(), [

            'phone_number'=>'required',

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'Phone number are requeired'
            ], 422);
        }else{

            $user = User::where('phone_number', $request->phone_number)->where('status','active')->first();

            if ($user) {
                $digits = 4;
                $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);

                $user = User::find($user->id);
                $user->otp = $otp;
                $user->update();

                $token = $user->createToken('my-app-token')->plainTextToken;

                if($user){
                    return Response::json([
                        'otp' => $otp,
                        'status' => '200',
                        'message' => 'Send otp on your mobile phone successfully',
                        'token' => $token
                    ], 200);
                }else{
                    return Response::json([
                        'status' => '401',
                        'message' => 'User forgot password request faild'
                    ], 401);
                }
            } else {
                return Response::json([
                    'status' => '401',
                    'message' => 'User account does not exist!'
                ], 404);
            }
        }
    }
    public function store_forgot_password(Request $request){
        $validator = Validator::make(request()->all(), [

            'password'=>'required',

            'confirm_password'=>'required',

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'password and confirm password both are requeired'
            ], 422);
        }else{
            if($request->password == $request->confirm_password){

                $token = $request->header('token');

                    $id = $request->userId;
                    $user = User::find($id);
                if($token == $user->remember_token){
                    $user->password = Hash::make($request->password);
                    $user->save();

                    if($user){
                        return Response::json([
                            'status' => '200',
                            'message' => 'New password store successfully'
                        ], 200);
                    }else{
                        return Response::json([
                            'status' => '401',
                            'message' => 'New password store request failed'
                        ], 401);
                    }
                }else{
                    return Response::json([
                        'status' => '422',
                        'message' => 'Token is not valid or expired'
                    ], 422);
                }

            }else{
                return Response::json([
                    'status' => '422',
                    'message' => 'password and confirm password are not same'
                ], 422);
            }
        }
    }
    public function update_profile(Request $request){
        $validator = Validator::make(request()->all(), [

            'userId'=>'required',

            'email'=>'required',

            'phone_number'=>'required',

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'Email and phone number are required'
            ], 422);
        }else{

        if($request->hasFile('image')){

            $image = $request->file('image');

            $name = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/images/user');

            $image->move($destinationPath,$name);
        }else{
            $name = $request->image;
        }

        $token = $request->header('token');
        $user = User::find($request->userId);
        if($user->remember_token == $token){
                $user->name = $request->name;
                $user->gender = $request->gender;
                $user->email = $request->email;
                $user->birthdate = $request->birthdate;
                $user->phone_number = $request->phone_number;
                $user->password = Hash::make($request->password);
                $user->image = $name;
                $user->save();
                if($user){
                    return Response::json([
                        'status' => '200',
                        'message' => 'User data updated successfully'
                    ], 200);
                }else{
                    return Response::json([
                        'status' => '422',
                        'message' => 'User data update request is faild'
                    ], 422);
                }
            }else{
                return Response::json([
                    'status' => '422',
                    'message' => 'Autorazation token is invalid'
                ], 422);
            }
        }
    }
}
