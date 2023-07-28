<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Models\Offer;

class OfferController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make(request()->all(), [

            'title'=>'required',

            'image'=>'required',

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

            $destinationPath = public_path('/images/offers');

            $image->move($destinationPath,$name);

            $offer = new Offer;
            $offer->title = $request->title;
            $offer->description = $request->description;
            $offer->image = $name;
            $offer->coupon_code = $request->coupon_code;
            $offer->link = $request->link;
            $offer->status = $request->status;
            $offer->save();
            if($offer){
                return Response::json([
                    'error_code' => '1002',
                    'status' => '200',
                    'message' => 'offer data has been saved'
                ], 200);
            }else{
                return Response::json([
                    'error_code' => '1001',
                    'status' => '401',
                    'message' => 'offer data has been not saved'
                ], 401);
            }
        }
    }
}
