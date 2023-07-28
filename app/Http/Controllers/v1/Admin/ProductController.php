<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;

class ProductController extends Controller
{
    public function store(Request $request){

        $validator = Validator::make(request()->all(), [

            'category_id'=>'required',

            'subcategory_id'=>'required',

            'section_id'=>'required',

            'product_name'=>'required',

            'description'=>'required',

            'more_info'=>'required',

            'status'=>'required'

        ]);

        if ($validator->fails()) {
            return Response::json([
                'error_code' => '1007',
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{
            $product = new Product;
            $product->category_id = $request->category_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->fabric_id = $request->fabric_id;
            $product->section_id = $request->section_id;
            $product->wishlist = '0';
            $product->product_name = $request->product_name;
            $product->description = $request->description;
            $product->more_info = $request->more_info;
            $product->status = $request->status;
            $product->save();

            $productId = $product->id;

            foreach($request->image as $key=>$images){

                $image = $images;

                $name = time().$key.'.'.$image->getClientOriginalExtension();

                $destinationPath = public_path('/images/products');

                $image->move($destinationPath,$name);

                $productImage = new ProductImage;
                $productImage->product_id = $productId;
                $productImage->image = $name;
                $productImage->status = 'active';
                $productImage->save();
            }

            // This is the discount logic
            $type_count = count($request->discount_type);
            $dis_price = array();
            for($i=0;$i<$type_count;$i++){
                    if($request->discount_type[$i] == 'price'){
                    array_push($dis_price,$request->original_price[$i] - $request->off_price[$i]);
                }else if($request->discount_type[$i] == 'percentage'){
                    array_push($dis_price,$request->original_price[$i] - ($request->original_price[$i] * ($request->off_percentage[$i] / 100)));
                }
            }

             foreach($request->variant_option_id as $key=>$variant_option){
                $productvariant = new ProductVariant;
                $productvariant->product_id = $productId;
                $productvariant->variant_id = $request->variant_id;
                $productvariant->variant_option_id = $variant_option;
                $productvariant->qty = $request->qty[$key];
                $productvariant->sku = $request->sku[$key];
                $productvariant->weight = $request->weight[$key];
                $productvariant->color_code = $request->color_code[$key];
                $productvariant->discount_type = $request->discount_type[$key];
                $productvariant->off_price = $request->off_price[$key];
                $productvariant->off_percentage = $request->off_percentage[$key];
                $productvariant->original_price = $request->original_price[$key];
                $productvariant->discount_price = $dis_price[$key];
                $productvariant->status = 'active';
                $productvariant->save();
            }
            if($product){
                return Response::json([
                    'error_code' => '1002',
                    'status' => '200',
                    'message' => 'Product data has been saved'
                ], 200);
            }else{
                return Response::json([
                    'error_code' => '1001',
                    'status' => '401',
                    'message' => 'Product data has been not saved'
                ], 401);
            }
        }
    }
}
