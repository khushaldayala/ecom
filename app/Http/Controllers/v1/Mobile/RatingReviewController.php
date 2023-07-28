<?php

namespace App\Http\Controllers\v1\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;
use Response;

class RatingReviewController extends Controller
{
    public function store_rating(Request $request){
        $validator = Validator::make(request()->all(), [

            'user_id'=>'required',

            'product_id'=>'required',

            'rating'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'error_code' => '1007',
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{
            $rating = new Rating;
            $rating->user_id = $request->user_id;
            $rating->product_id = $request->product_id;
            $rating->rating = $request->rating;
            $rating->save();

            if($rating){
                return Response::json([
                    'status' => '200',
                    'message' => 'Rating has been saved'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Rating has been not saved'
                ], 401);
            }
        }
    }
    public function store_review(Request $request){
        $validator = Validator::make(request()->all(), [

            'user_id'=>'required',

            'product_id'=>'required',

            'review'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'error_code' => '1007',
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{
            if($request->hasFile('image')){

                $image = $request->file('image');

                $name = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/images/review');

                $image->move($destinationPath,$name);
            }else{
                $name = '';
            }
            $review = new Review;
            $review->user_id = $request->user_id;
            $review->product_id = $request->product_id;
            $review->review = $request->review;
            $review->image = $name;
            $review->save();

            if($review){
                return Response::json([
                    'status' => '200',
                    'message' => 'Review has been saved'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Review has been not saved'
                ], 401);
            }
        }
    }
}
