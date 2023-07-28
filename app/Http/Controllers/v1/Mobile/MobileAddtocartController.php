<?php

namespace App\Http\Controllers\v1\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Models\AddToCart;

class MobileAddtocartController extends Controller
{
    public function store(Request $request){


        $validator = Validator::make(request()->all(), [

            'user_id'=>'required',

            'product_id'=>'required',

            'product_name'=>'required',

            'price'=>'required',

            'image'=>'required',

            'qty'=>'required'

        ]);


        if ($validator->fails()) {
            return Response::json([
                'error_code' => '1007',
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{

            $total_price = $request->price*$request->qty;

            $addtocart = new AddToCart();
            $addtocart->user_id = $request->user_id;
            $addtocart->product_id = $request->product_id;
            $addtocart->filter = $request->filter;
            $addtocart->filteroption = $request->filteroption;
            $addtocart->product_name = $request->product_name;
            $addtocart->price = $request->price;
            $addtocart->total = $total_price;
            $addtocart->qty = $request->qty;
            $addtocart->image = $request->image;
            $addtocart->save();

            if($addtocart){
                return Response::json([
                    'status' => '200',
                    'message' => 'Product added successfully'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Product has been not added'
                ], 401);
            }
        }
    }
    public function remove_cart($id) {
        $addtocart = AddToCart::find($id);
        $addtocart->delete();
        if($addtocart){
            return Response::json([
                'status' => '200',
                'message' => 'Product removed successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Product has been not removed'
            ], 401);
        }
    }
    public function update_cart(Request $request){
        if($request->qty < 1){
            return Response::json([
                'status' => '400',
                'message' => '0 qty is not valid'
            ], 400);
        }
        $addtocart = AddToCart::find($request->addtocart_id);
        if($addtocart){
            $total_price = $addtocart->price*$request->qty;
            $addtocart->qty = $request->qty;
            $addtocart->total = $total_price;
            $addtocart->save();
            if($addtocart){
                return Response::json([
                    'status' => '200',
                    'message' => 'Add to cart updated successfully'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Add to cart has been updated'
                ], 401);
            }
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Product not found'
            ], 404);
        }
        exit;
    }
    public function get_addtocart_count($id){
        $addtocart_count = AddToCart::where('user_id',$id)->count();
        if($addtocart_count){
            return Response::json([
                'status' => '200',
                'message' => 'Add to cart count',
                'count' => $addtocart_count
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'No count of add to cart',
                'count' => 0
            ], 404);
        }
    }
    public function get_cart_items($id){
        $cart_items = AddToCart::where('user_id',$id)->get();
        if(count($cart_items)>0){
            return Response::json([
                'status' => '200',
                'message' => 'Add to cart count',
                'data' => $cart_items
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'No data found in cart'
            ], 404);
        }
    }
}
