<?php

namespace App\Http\Controllers\v1\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Rating;
use DB;
use Illuminate\Support\Str;

class MobileProductController extends Controller
{
    public function get_single_product($id){

        $product_count = Product::findOrFail($id);
        $product_count->increment('view_count');

        $product = Product::
        with(['ratings' => function ($query) {
            $query->select('product_id', DB::raw('avg(rating) as rating_avg'))
                ->groupBy('product_id');
        },  'reviews' => function($query) {
            $query->select('id','user_id','product_id','review','image');
        },  'productImages' => function($query) {
            $query->select('id','product_id','image');
        }, 'productVariants' => function($query) {
            $query->select('id','product_id','variant_id','variant_option_id','discount_type','off_price','off_percentage','original_price','discount_price','qty','sku','weight','color');
        }, 'productVariants.variantOptions' => function($query) {
            $query->select('id','option');
        }])
        ->select('id','category_id','subcategory_id','fabric_id','section_id','wishlist','product_name','description','more_info')
        ->where('status','active')
        ->findOrFail($id);

        $related_product = Product::
        with(['productImages' => function($query) {
            $query->select('id','product_id','image');
        }, 'productVariants' => function($query) {
            $query->select('id','product_id','variant_id','variant_option_id','discount_type','off_price','off_percentage','original_price','discount_price','qty','sku','weight','color');
        }, 'productVariants.variantOptions' => function($query) {
            $query->select('id','option');
        }])
        ->select('id','category_id','subcategory_id','fabric_id','section_id','wishlist','product_name','description','more_info')
        ->where('status','active')
        ->where('category_id', $product->category_id)
        ->where('id', '!=', $id)
        ->limit(4)
        ->get();

        return Response::json([
            'status' => '200',
            'message' => 'Product get successful',
            'product' => $product,
            'related_products' => $related_product
        ], 200);
    }
    public function get_all_product(){
        $products = Product::with(['productImages' => function($query) {
            $query->select('id','product_id','image');
        }, 'productVariants' => function($query) {
            $query->select('id','product_id','variant_id','variant_option_id','discount_type','off_price','off_percentage','original_price','discount_price','qty','sku','weight','color');
        }])
        ->select('id','category_id','subcategory_id','fabric_id','section_id','wishlist','product_name','description','more_info')
        ->where('status','active')
        ->get();

        if($products){
            return Response::json([
                'status' => '200',
                'message' => 'Product list get successful',
                'data' => $products
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Product data not found',
            ], 404);
        }
    }
    public function get_category_wise_product($id){

        $products = Product::with(['productImages', 'productVariants', 'ratings' => function ($query) {
            $query->select('product_id', DB::raw('avg(rating) as rating_avg'))
                ->groupBy('product_id');
        }])
        ->where('category_id', $id)
        ->where('status', 'active')
        ->get();

        if(count($products)>0){
            return Response::json([
                'status' => '200',
                'message' => 'Product list get category wise successful',
                'data' => $products
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Product data not found',
            ], 404);
        }
    }
    public function get_brand_wise_product($id){

        $products = Product::with(['productImages', 'productVariants', 'ratings' => function ($query) {
            $query->select('product_id', DB::raw('avg(rating) as rating_avg'))
                ->groupBy('product_id');
        }])
        ->where('brand_id', $id)
        ->where('status', 'active')
        ->get();

        if(count($products)>0){
            return Response::json([
                'status' => '200',
                'message' => 'Product list get brand wise successful',
                'data' => $products
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Product data not found',
            ], 404);
        }
    }
}
