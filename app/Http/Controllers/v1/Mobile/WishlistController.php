<?php

namespace App\Http\Controllers\v1\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make(request()->all(), [

            'user_id'=>'required',

            'product_id'=>'required',

            'product_name'=>'required',

            'price'=>'required',

            'image'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'error_code' => '1007',
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{
            $wishlist = Wishlist::where('user_id', $request->user_id)->where('product_id', $request->product_id)->first();
            if(($wishlist)){
                $wishlist->delete();
            }else{
                $wishlist = new Wishlist;
                $wishlist->user_id = $request->user_id;
                $wishlist->product_id = $request->product_id;
                $wishlist->product_name = $request->product_name;
                $wishlist->price = $request->price;
                $wishlist->image = $request->image;
                $wishlist->save();
            }
            if($wishlist){
                return Response::json([
                    'status' => '200',
                    'message' => 'wishlist updated successfully'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'wishlist has been not updated'
                ], 401);
            }
        }
    }
}
