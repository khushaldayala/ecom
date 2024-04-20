<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfferStoreRequest;
use App\Http\Requests\OfferUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\Offer;
use App\Models\SectionOffer;
use App\Traits\OfferTrait;

class OfferController extends Controller
{
    use OfferTrait;

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
    public function store(OfferStoreRequest $request){
        
        $image = $request->file('image');

        $name = time().'.'.$image->getClientOriginalExtension();

        $destinationPath = public_path('/images/offers');

        $image->move($destinationPath,$name);

        $offer = new Offer;
        $offer->section_id = $request->section_id ? $request->section_id[0] : null;
        $offer->title = $request->title;
        $offer->description = $request->description;
        $offer->image = $name;
        $offer->coupon_code = $request->coupon_code;
        $offer->link = $request->link;
        $offer->type = $request->type;
        $offer->discount = $request->discount;
        $offer->status = $request->status;
        $offer->save();

        if($request->product_ids)
        {
            $this->productAssignToOffer($offer, $request->product_ids);
        }

        if ($request->section_id) {
            $this->offerAssignTosection($offer, $request->section_id);
        }

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
    public function get_single_offer($id){
        $offer = Offer::with('section_offers.section', 'offer_product.product')->findorfail($id);
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
    public function update(OfferUpdateRequest $request, $id){
        
        if($request->hasFile('image')){
            $image = $request->file('image');

            $name = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/images/offers');

            $image->move($destinationPath,$name);
        }

        $offer = Offer::find($id);
        $offer->section_id = $request->section_id ? $request->section_id[0] : null;
        $offer->title = $request->title;
        $offer->description = $request->description;
        if($request->hasFile('image')){
            $offer->image = $name;
        }
        $offer->coupon_code = $request->coupon_code;
        $offer->link = $request->link;
        $offer->type = $request->type;
        $offer->discount = $request->discount;
        $offer->status = $request->status;
        $offer->save();

        if ($request->product_ids) {
            $this->productAssignToOffer($offer, $request->product_ids);
        }

        if($request->section_id)
        {
            $this->offerAssignTosection($offer, $request->section_id);
        }

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

    public function remove_offer_section(SectionOffer $section)
    {
        $section->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Offer has been successfully removed from the section.'
        ], 200);
    }

    public function assigned()
    {
        $offerIds = SectionOffer::pluck('offer_id')->unique()->values()->toArray();
        $data = Offer::whereIn('id', $offerIds)->get();

        return Response::json([
            'status' => '200',
            'message' => 'Assigned offer list.',
            'data' => $data
        ], 200);
    }

    public function unassigned()
    {
        $offerIds = SectionOffer::pluck('offer_id')->unique()->values()->toArray();
        $data = Offer::whereNotIn('id', $offerIds)->get();

        return Response::json([
            'status' => '200',
            'message' => 'Unassigned offer list.',
            'data' => $data
        ], 200);
    }
}
