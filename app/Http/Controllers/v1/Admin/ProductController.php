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
    public function products(){
        $products = Product::with('productImages')->get();
        if($products){
            return Response::json([
                'status' => '200',
                'message' => 'Products list get successfully',
                'data' => $products
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Products data not found'
            ], 404);
        }
    }
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

            if(isset($request->image)){
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
            }

            if(isset($request->productVariants)){

                foreach($request->productVariants as $key=>$variants){

                    if($variants['discount_type'] != ''){
                        if($variants['discount_type'] == 'price'){
                            $dis_price = $variants['original_price'] - $variants['off_price'];
                        }else if($variants['discount_type'] == 'percentage'){
                            $dis_price = $variants['original_price'] - ($variants['original_price'] * ($variants['off_percentage'] / 100));
                        }
                    }

                    $image = $variants['image'];
    
                    $name = time().$key.'.'.$image->getClientOriginalExtension();
    
                    $destinationPath = public_path('/images/productsVariants');
    
                    $image->move($destinationPath,$name);
                    
                   $productvariant = new ProductVariant;
                   $productvariant->product_id = $productId;
                   $productvariant->variant_id = $variants['variant_id'];
                   $productvariant->variant_option_id = $variants['variant_option_id'];
                   $productvariant->qty = $variants['qty'];
                   $productvariant->sku = $variants['sku'];
                   $productvariant->weight = $variants['weight'];
                   $productvariant->color_code = $variants['color_code'];
                   $productvariant->discount_type = $variants['discount_type'];
                   $productvariant->off_price = $variants['off_price'];
                   $productvariant->off_percentage = $variants['off_percentage'];
                   $productvariant->original_price = $variants['original_price'];
                   $productvariant->image = $variants['image'];
                   $productvariant->discount_price = $dis_price;
                   $productvariant->status = 'active';
                   $productvariant->save();
               }
            }

            if($product){
                return Response::json([
                    'status' => '200',
                    'message' => 'Product data has been saved'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Product data has been not saved'
                ], 401);
            }
        }
    }
    public function get_single_product($id){
        $product = Product::with('productImages')->findorfail($id);
        if($product){
            return Response::json([
                'status' => '200',
                'message' => 'Product data get successfully',
                'data' => $product
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Product data not found'
            ], 404);
        }
    }   
    public function update(Request $request, $id){

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
                'status' => '422',
                'message' => 'All field are requeired'
            ], 422);

        }else{
            $product = Product::find($id);
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

            if(isset($request->existing_images)){
                ProductImage::where('product_id',$id)->delete();
                foreach($request->existing_images as $key=>$images){
                    $productImage = new ProductImage;
                    $productImage->product_id = $id;
                    $productImage->image = $images;
                    $productImage->status = 'active';
                    $productImage->save();
                }
            }else{
                ProductImage::where('product_id',$id)->delete();
            }
            
            if(isset($request->image)){
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
            }

            if(isset($request->productVariants)){
                ProductVariant::where('product_id',$id)->delete();
                foreach($request->productVariants as $key=>$variants){

                    if($variants['discount_type'] != ''){
                        if($variants['discount_type'] == 'price'){
                            $dis_price = $variants['original_price'] - $variants['off_price'];
                        }else if($variants['discount_type'] == 'percentage'){
                            $dis_price = $variants['original_price'] - ($variants['original_price'] * ($variants['off_percentage'] / 100));
                        }
                    }
                    
                   $productvariant = new ProductVariant;
                   $productvariant->product_id = $id;
                   $productvariant->variant_id = $variants['variant_id'];
                   $productvariant->variant_option_id = $variants['variant_option_id'];
                   $productvariant->qty = $variants['qty'];
                   $productvariant->sku = $variants['sku'];
                   $productvariant->weight = $variants['weight'];
                   $productvariant->color_code = $variants['color_code'];
                   $productvariant->discount_type = $variants['discount_type'];
                   $productvariant->off_price = $variants['off_price'];
                   $productvariant->off_percentage = $variants['off_percentage'];
                   $productvariant->original_price = $variants['original_price'];
                   $productvariant->discount_price = $dis_price;
                   $productvariant->status = 'active';
                   $productvariant->save();
               }
            }
            if($product){
                return Response::json([
                    'status' => '200',
                    'message' => 'Product data has been updated'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Product data has been not updated'
                ], 401);
            }
        }
    }
}
