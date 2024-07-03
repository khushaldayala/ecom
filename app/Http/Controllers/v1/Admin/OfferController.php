<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfferStoreRequest;
use App\Http\Requests\OfferUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\Product;
use App\Models\SectionOffer;
use App\Traits\OfferTrait;

class OfferController extends Controller
{
    use OfferTrait;

    public function offers(){
        $offer = Offer::with('offer_product.product')->get();
        
        $offer->each(function ($offer) {
            $offer->product_count = $offer->offer_product->filter(function ($offerProduct) {
                return $offerProduct->product !== null;
            })->count();
        });


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
        $offer->title = $request->title;
        $offer->description = $request->description;
        $offer->image = $name;
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
        $offer = Offer::with('section_offers.section', 'offer_product.product', 'offer_product.product.productImages', 'offer_product.product.productVariants', 'offer_product.product.productVariants.productVariantImages')->findorfail($id);
        if($offer){
            $offerProductCount = $offer->offer_product->count();
            return Response::json([
                'status' => '200',
                'message' => 'Offer data get successfully',
                'offer_product_count' => $offerProductCount,
                'data' => $offer,
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
        $offer->title = $request->title;
        $offer->description = $request->description;
        if($request->hasFile('image')){
            $offer->image = $name;
        }
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
        OfferProduct::where('offer_id', $id)->delete();
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
    
    public function remove_product_offer($productId, $offerId)
    {
        OfferProduct::where('offer_id', $offerId)->where('product_id', $productId)->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Product has been successfully removed from the offer.'
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
    
    public function assigned_products($offer_id)
    {
        $productIds = OfferProduct::where('offer_id', $offer_id)->pluck('product_id')->unique()->values()->toArray();
        $data = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages')->whereIn('id', $productIds)->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Assigned products list.',
            'data' => $data
        ], 200);
    }

    public function unassigned_products()
    {
        $productIds = OfferProduct::pluck('product_id')->unique()->values()->toArray();
        $data = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages')->whereNotIn('id', $productIds)->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Unassigned products list.',
            'data' => $data
        ], 200);
    }
    
    public function search(Request $request)
    {
        $title = $request->input('search');

        $offers = Offer::with('offer_product.product')
        ->where('title', 'LIKE', '%' . $title . '%')
            ->get();

        $offers->each(function ($offer) {
            $offer->product_count = $offer->offer_product->filter(function ($offerProduct) {
                return $offerProduct->product !== null;
            })->count();
        });

        return Response::json([
            'status' => '200',
            'message' => 'Offers list retrieved successfully',
            'data' => $offers
        ],
            200
        );
    }
}
