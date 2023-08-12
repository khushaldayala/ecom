<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Models\Offer;

class OfferController extends Controller
{
    public function offers(){
        $offer = Offer::all();
        if($offer){
            return Response::json([
                'status' => '200',
                'message' => 'Offers list get successfully',
                'data' => $offer
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Offers data not found'
            ], 404);
        }
    }
    public function store(Request $request){
        $validator = Validator::make(request()->all(), [

            'title'=>'required',

            'image'=>'required',

            'status'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{
            $image = $request->file('image');

            $name = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/images/offers');

            $image->move($destinationPath,$name);

            $offer = new Offer;
            $offer->section_id = $request->section_id;
            $offer->title = $request->title;
            $offer->description = $request->description;
            $offer->image = $name;
            $offer->coupon_code = $request->coupon_code;
            $offer->link = $request->link;
            $offer->status = $request->status;
            $offer->save();
            if($offer){
                return Response::json([
                    'status' => '200',
                    'message' => 'offer data has been saved'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'offer data has been not saved'
                ], 401);
            }
        }
    }
    public function get_single_offer($id){
        $offer = Offer::findorfail($id);
        if($offer){
            return Response::json([
                'status' => '200',
                'message' => 'Offer data get successfully',
                'data' => $offer
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Offer data not found'
            ], 404);
        }
    }
    public function update(Request $request, $id){
        $validator = Validator::make(request()->all(), [

            'title'=>'required',

            'image'=>'required',

            'status'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{

            if($request->hasFile('image')){
                $image = $request->file('image');

                $name = time().'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/images/offers');

                $image->move($destinationPath,$name);
            }

            $offer = Offer::find($id);
            $offer->section_id = $request->section_id;
            $offer->title = $request->title;
            $offer->description = $request->description;
            if($request->hasFile('image')){
                $offer->image = $name;
            }
            $offer->coupon_code = $request->coupon_code;
            $offer->link = $request->link;
            $offer->status = $request->status;
            $offer->save();
            if($offer){
                return Response::json([
                    'status' => '200',
                    'message' => 'offer data has been Updated'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'offer data has been not Updated'
                ], 401);
            }
        }
    }
    public function delete($id){
        $offer = Offer::find($id);
        $offer->delete();
        if($offer){
            return Response::json([
                'status' => '200',
                'message' => 'Offer move to trash successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Offer has been not move in trash'
            ], 401);
        }
    }

    // Trash data section
    public function trash_offer(){
        $offer = Offer::onlyTrashed()->get();
        if($offer){
            return Response::json([
                'status' => '200',
                'message' => 'Trash Offers list get successfully',
                'data' => $offer
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Trash Offers data not found'
            ], 404);
        }
    }
    public function trash_offer_restore($id){
        $offer = Offer::onlyTrashed()->findOrFail($id);
        $offer->restore();
        if($offer){
            return Response::json([
                'status' => '200',
                'message' => 'Offer restored successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Offer has been not restored'
            ], 401);
        }
    }
    public function trash_offer_delete($id){
        $offer = Offer::onlyTrashed()->findOrFail($id);
        $offer->forceDelete();
        if($offer){
            return Response::json([
                'status' => '200',
                'message' => 'Trash Offer deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Offer has been not deleted'
            ], 401);
        }
    }
    public function all_trash_offer_delete(){
        $offer = Offer::onlyTrashed()->forceDelete();
        if($offer){
            return Response::json([
                'status' => '200',
                'message' => 'All Trash Offers deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Offers has been not deleted'
            ], 401);
        }
    }
}
