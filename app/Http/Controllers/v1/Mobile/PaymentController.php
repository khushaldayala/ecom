<?php

namespace App\Http\Controllers\v1\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderNote;
use App\Models\CheckoutProducts;
use Response;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function store_checkout(Request $request){
        $validator = Validator::make(request()->all(), [

            'user_id'=>'required',

            'full_name'=>'required',

            'phone'=>'required',

            'address'=>'required',

            'city'=>'required',

            'state'=>'required',

            'country'=>'required',

            'zipcode'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'All files are required'
            ], 422);
        }else{
            if($request->is_same_billing_shipping === true || $request->is_same_billing_shipping === 'true') {
                $billing_full_name = $request->full_name;
                $billing_phone = $request->phone;
                $billing_address = $request->address;
                $billing_city = $request->city;
                $billing_state = $request->state;
                $billing_coutry = $request->country;
                $billing_zipcode = $request->zipcode;
            }else{
                $billing_full_name = $request->billing_full_name;
                $billing_phone = $request->billing_phone;
                $billing_address = $request->billing_address;
                $billing_city = $request->billing_city;
                $billing_state = $request->billing_state;
                $billing_coutry = $request->billing_coutry;
                $billing_zipcode = $request->billing_zipcode;
            }

            $checkout = new Checkout;
            $checkout->user_id = $request->user_id;
            $checkout->full_name = $request->full_name;
            $checkout->phone = $request->phone;
            $checkout->address = $request->address;
            $checkout->city = $request->city;
            $checkout->state = $request->state;
            $checkout->country = $request->country;
            $checkout->zipcode = $request->zipcode;
            $checkout->billing_full_name = $billing_full_name;
            $checkout->billing_phone = $billing_phone;
            $checkout->billing_address = $billing_address;
            $checkout->billing_city = $billing_city;
            $checkout->billing_state = $billing_state;
            $checkout->billing_coutry = $billing_coutry;
            $checkout->billing_zipcode = $billing_zipcode;
            $checkout->order_comment = $request->order_comment;
            $checkout->save();

            foreach($request->images as $key=>$image){

                // $name = time().$key.'.'.$image->getClientOriginalExtension();

                // $destinationPath = public_path('/images/checkout');

                // $image->move($destinationPath,$name);

                $checkout_product = new CheckoutProducts;
                $checkout_product->checkout_id = $checkout->id;
                $checkout_product->product_id = $request->product_id[$key];
                $checkout_product->filter_id = $request->filter_id[$key];
                $checkout_product->filter_option_id = $request->filter_option_id[$key];
                $checkout_product->product_name = $request->product_name[$key];
                $checkout_product->price = $request->price[$key];
                $checkout_product->qty = $request->qty[$key];
                $checkout_product->total = $request->price[$key] * $request->qty[$key];
                $checkout_product->image = $image;
                $checkout_product->save();
            }

            if($request->is_new == true)
            {
                $address = new Address;
                $address->user_id = $request->user_id;
                $address->address = $request->address;
                $address->city = $request->city;
                $address->state = $request->state;
                $address->country = $request->country;
                $address->zipcode = $request->zipcode;
                $address->save();
            }

            return Response::json([
                'status' => '200',
                'message' => 'Checkout details has been saved',
                'checkout_id' => $checkout->id,
            ], 200);
        }
    }

    public function update_address(Request $request, $addressId)
    {
        $address = Address::find($addressId);
        $address->address = $request->address;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->country = $request->country;
        $address->zipcode = $request->zipcode;
        $address->update();

        return Response::json([
            'status' => '200',
            'message' => 'Address updated successfully'
        ], 200);
    }

    public function remove_address(Address $address)
    {
        $address->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Address deleted successfully'
        ], 200);
    }

    public function address_list($userId)
    {
        $address = Address::where('user_id',$userId)->get();

        return Response::json([
            'status' => '200',
            'message' => 'Address list successfully',
            'data' => $address
        ], 200);
    }

    public function payment(Request $request)
    {
        $order = new Order;
        $order->user_id = $request->user_id;
        $order->transaction_id = $request->transaction_id;
        $order->checkout_id = $request->checkout_id;
        $order->amount = $request->amount;
        $order->payment_status = $request->payment_status;
        $order->save();

        return Response::json([
            'status' => '200',
            'message' => 'Payment details stored successfully',
        ], 200);
    }

    public function get_order_list($userId)
    {
        $order = Checkout::with('checkoutProducts','order','order.orderNotes')->where('user_id',$userId)->get();

        return Response::json([
            'status' => '200',
            'message' => 'Get orders list successfully',
            'data' => $order
        ], 200);
    }

    public function store_order_note(Request $request)
    {
        $orderNote = new OrderNote;
        $orderNote->order_id = $request->order_id;
        $orderNote->note = $request->note;
        $orderNote->save();

        return Response::json([
            'status' => '200',
            'message' => 'Order note stored successfully',
        ], 200);
    }
}
