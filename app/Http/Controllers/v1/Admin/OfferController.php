<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfferProductFilterRequest;
use App\Http\Requests\OfferStoreRequest;
use App\Http\Requests\OfferUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\SectionOffer;
use App\Traits\OfferTrait;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    use OfferTrait;

    public function offers(Request $request)
    {
        $userId = Auth::id();
        $sort = $request->input('sort');
        $sortType = $request->input('sort_type');
        $search = $request->input('search');
        $isActive = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);

        $offers = Offer::with('offer_product.product')->where('user_id', $userId);

        if ($search) {
            $offers = $offers->where('title', 'LIKE', '%' . $search . '%');
        }

        if ($sortType) {
            switch ($sortType) {
                case 'product':
                    $sortOrder = ($sort === 'asc') ? 'asc' : 'desc';
                    $offers = $offers->withCount('offer_product')->orderBy('offer_product_count', $sortOrder);
                    break;
                case 'name':
                    $offers->orderBy('title', $sort);
                    break;
            }
        } else {
            $offers = $offers->latest();
        }

        if ($isActive) {
            $offers = $offers->get();
        } else {
            $offers = $offers->paginate();
        }

        // Add product count to each offer
        $offers->each(function ($offer) {
            $offer->product_count = $offer->offer_product->filter(function ($offerProduct) {
                return $offerProduct->product !== null;
            })->count();
        });
       
        return Response::json([
            'status' => '200',
            'message' => 'Filter products list.',
            'data' => $offers
        ], 200);
    }

    public function store(OfferStoreRequest $request){

        $image = $request->file('image');
        $name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/images/offers');
        $image->move($destinationPath,$name);

        $userId = Auth::id();

        $offer = new Offer;
        $offer->user_id = $userId;
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

        // if ($request->section_id) {
        //     $this->offerAssignTosection($offer, $request->section_id);
        // }

        return Response::json([
            'status' => '200',
            'message' => 'offer data has been saved'
        ], 200);
    }
    public function get_single_offer(Offer $offer){

        $assignedProductIds = $offer->offer_products->pluck('product_id')->toArray();

        $offer = $offer->load([
            'section_offers.section',
            'offer_product' => function ($query) {
                $query->take(10)->with([
                    'product' => function ($query) {
                        $query->with([
                            'productImages',
                            'productVariants' => function ($query) {
                                $query->with('productVariantImages');
                            }
                        ]);
                    }
                ]);
            }
        ]);

        $offerProductCount = $offer->offer_product->count();
        
        return Response::json([
            'status' => '200',
            'message' => 'Offer data get successfully',
            'offer_product_count' => $offerProductCount,
            'data' => $offer,
            'assigned_product_ids' => $assignedProductIds
        ], 200);
    }
    public function update(OfferUpdateRequest $request, Offer $offer)
    {
        $typeChanged = $offer->type !== $request->type;
        $discountChanged = $offer->discount !== $request->discount;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/offers');
            $image->move($destinationPath, $name);
        }

        $userId = Auth::id();

        $offer->user_id = $userId;
        $offer->title = $request->title;
        $offer->description = $request->description;
        if ($request->hasFile('image')) {
            $offer->image = $name;
        }
        $offer->type = $request->type;
        $offer->discount = $request->discount;
        $offer->status = $request->status;
        $offer->save();

        if ($typeChanged || $discountChanged) {
            $offerProductVariants = $offer->productVarients()->get();
            if ($offerProductVariants) {
                foreach ($offerProductVariants as $variant) {
                    $this->updateProductVariantPrice($request->type, $request->discount, $variant);
                }
            }
        }

        if (is_array($request->assigned_product_ids) && isset($request->assigned_product_ids)) {
            $this->productAssignToOffer($offer, $request->assigned_product_ids);
        } else {
            OfferProduct::where('offer_id', $offer->id)
                ->delete();
        }

        // $this->offerAssignTosection($offer, $request->section_id);

        return Response::json([
            'status' => '200',
            'message' => 'offer data has been Updated'
        ], 200);
    }
    public function delete(Offer $offer){
        OfferProduct::where('offer_id', $offer->id)->delete();
        $offer->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Offer move to trash successfully'
        ], 200);
    }

    // Trash data section
    public function trash_offer(){
        $userId = Auth::id();
        $offer = Offer::where('user_id', $userId)->onlyTrashed()->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Trash Offers list get successfully',
            'data' => $offer
        ], 200);
    }
    public function trash_offer_restore($offer){
        $offer = Offer::onlyTrashed()->findOrFail($offer);
        $offer->restore();

        return Response::json([
            'status' => '200',
            'message' => 'Offer restored successfully'
        ], 200);
    }
    public function trash_offer_delete($offer){
        $offer = Offer::onlyTrashed()->findOrFail($offer);
        $offer->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'Trash Offer deleted successfully'
        ], 200);
    }
    public function all_trash_offer_delete(){
        $userId = Auth::id();
        Offer::onlyTrashed()->where('user_id', $userId)->forceDelete();

        return Response::json([
            'status' => '200',
            'message' => 'All Trash Offers deleted successfully'
        ], 200);
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
    
    public function filterProducts(OfferProductFilterRequest $request)
    {
        $type = $request->type;
        $itemId = $request->id;
        
        $data = [];
        if($type == 'assign')
        {
            $data = $this->assignedProducts($itemId, $request);
        }else if($type == 'unassign') {
            $data = $this->unassignedProducts($request);
        } else {
            $data = $this->commonProducts($request);
        }

        return Response::json([
            'status' => '200',
            'message' => 'Filter products list.',
            'data' => $data
        ], 200);
    }

    // public function unassigned_products(OfferProductFilterRequest $request)
    // {
    //     $productIds = OfferProduct::pluck('product_id')->unique()->values()->toArray();
    //     $query = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages')->whereNotIn('id', $productIds);

    //     $filterTypes = $request->input('filterTypes', []);
    //     $dateValue = $request->input('dateValue', null);
    //     $priceValue = $request->input('priceValue', null);
    //     $brandValue = $request->input('brandValue', []);
    //     $categoryValue = $request->input('categoryValue', []);
    //     $subCategoryValue = $request->input('subCategoryValue', []);

    //     if (!empty($filterTypes)) {
    //         foreach ($filterTypes as $filterType) {
    //             switch ($filterType) {
    //                 case 'brand':
    //                     if (!empty($brandValue)) {
    //                         $query->whereIn('brand_id', $brandValue);
    //                     }
    //                     break;
    //                 case 'category':
    //                     if (!empty($categoryValue)) {
    //                         $query->whereIn('category_id', $categoryValue);
    //                     }
    //                     break;
    //                 case 'subcategory':
    //                     if (!empty($subCategoryValue)) {
    //                         $query->whereIn('subcategory_id', $subCategoryValue);
    //                     }
    //                     break;
    //                 case 'price':
    //                     if (!is_null($priceValue)) {
    //                         $query->whereHas('productVariants', function ($q) use ($priceValue) {
    //                             $q->whereBetween('original_price', [$priceValue['min'], $priceValue['max']]);
    //                         });
    //                     }
    //                     break;
    //                 case 'date':
    //                     if (!is_null($dateValue)) {
    //                         $startDate = $dateValue['startdate'];
    //                         $endDate = $dateValue['enddate'];
    //                         $query->whereBetween('created_at', [$startDate, $endDate]);
    //                     }
    //                     break;
    //                 default:
    //                     // Handle unexpected filter types if necessary
    //                     break;
    //             }
    //         }
    //     }

    //     if($request->search)
    //     {
    //         $data = $query->where('product_name', 'LIKE', '%' . $request->search . '%')
    //             ->paginate(10);
    //     } else {
    //         $data = $query->paginate(10);
    //     }

    //     return Response::json([
    //         'status' => '200',
    //         'message' => 'Unassigned products list.',
    //         'data' => $data
    //     ], 200);
    // }
    
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

    public function statusUpdate(Offer $offer)
    {
        if ($offer->status == 'active') {
            $status = 'inactive';
        } else {
            $status = 'active';
        }

        $offer->update(['status' => $status]);

        return Response::json([
            'status' => '200',
            'message' => 'Offer status updated successfully.',
        ], 200);
    }
}
