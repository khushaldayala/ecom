<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Models\Product;
use App\Models\ProductVariantImage;
use App\Models\ProductImage;
use App\Models\ProductVariant;

class ProductController extends Controller
{
    public function products(){
        $products = Product::with('productImages','productVariants','productVariants.productVariantImages')->orderBy('id','desc')->get();
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
    public function store(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',

            'category_id' => 'required',

            'status' => 'required',

            'productVariants' => 'required|array',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        } else {
            // Create a new product
            $product = new Product;
            $product->category_id = $request->category_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->fabric_id = $request->fabric_id;
            $product->brand_id = $request->brand_id;
            $product->section_id = $request->section_id;
            $product->wishlist = '0';
            $product->product_name = $request->product_name;
            $product->description = $request->description;
            $product->more_info = $request->more_info;
            $product->status = $request->status;
            $product->save();

            $productId = $product->id;

            // Handle product images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    $name = time() . $key . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/products');
                    $image->move($destinationPath, $name);

                    $productImage = new ProductImage;
                    $productImage->product_id = $productId;
                    $productImage->image = $name;
                    $productImage->status = 'active';
                    $productImage->save();
                }
            }

            // Handle product variants
            if ($request->has('productVariants')) {
                foreach ($request->input('productVariants') as $variants) {
                    // Handle discount calculation
                    $dis_price = 0;
                    if ($variants['discount_type'] != '') {
                        if ($variants['discount_type'] == 'price') {
                            $dis_price = $variants['original_price'] - $variants['off_price'];
                            $variants['off_percentage'] = Null;
                        } elseif ($variants['discount_type'] == 'percentage') {
                            $dis_price = $variants['original_price'] - ($variants['original_price'] * ($variants['off_percentage'] / 100));
                            $variants['off_price'] = Null;
                        }
                    }

                    $productVariant = new ProductVariant;
                    $productVariant->product_id = $productId;
                    $productVariant->variant_id = $variants['variant_id'];
                    $productVariant->variant_option_id = $variants['variant_option_id'];
                    $productVariant->qty = $variants['qty'];
                    $productVariant->sku = $variants['sku'];
                    $productVariant->weight = $variants['weight'];
                    $productVariant->color = $variants['color'];
                    $productVariant->discount_type = $variants['discount_type'];
                    $productVariant->off_price = $variants['off_price'];
                    $productVariant->off_percentage = $variants['off_percentage'];
                    $productVariant->original_price = $variants['original_price'];
                    $productVariant->discount_price = $dis_price;
                    $productVariant->status = 'active';
                    $productVariant->save();

                    // Handle variant images
                    if (isset($variants['variantImages']) && count($variants['variantImages']) > 0) {
                        foreach ($variants['variantImages'] as $key => $imageFile) {
                            $image = $imageFile;
                            $name = time() . $key . '.' . $image->getClientOriginalExtension();
                            $destinationPath = public_path('/images/productsVariants');
                            $image->move($destinationPath, $name);

                            $productVariantImage = new ProductVariantImage;
                            $productVariantImage->product_variant_id = $productVariant->id;
                            $productVariantImage->image = $name;
                            $productVariantImage->save();
                        }
                    }
                }
            }

            return response()->json([
                'status' => '200',
                'message' => 'Product data has been saved'
            ], 200);
        }
    }
    public function get_single_product($id){
        $product = Product::with('productImages','productVariants','productVariants.productVariantImages')->findorfail($id);
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

            'product_name'=>'required',

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
            $product->brand_id = $request->brand_id;
            $product->section_id = $request->section_id;
            $product->wishlist = '0';
            $product->product_name = $request->product_name;
            $product->description = $request->description;
            $product->more_info = $request->more_info;
            $product->status = $request->status;
            $product->update();

            $productId = $product->id;

            // if(isset($request->existing_images)){
            //     ProductImage::where('product_id',$id)->delete();
            //     foreach($request->existing_images as $key=>$images){
            //         $productImage = new ProductImage;
            //         $productImage->product_id = $id;
            //         $productImage->image = $images;
            //         $productImage->status = 'active';
            //         $productImage->save();
            //     }
            // }else{
            //     ProductImage::where('product_id',$id)->delete();
            // }
            
            if ($request->hasFile('images')) {
                foreach($request->images as $key=>$images){
    
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

            if ($request->has('productVariants')) {
                foreach($request->productVariants as $key=>$variants){

                    if($variants['discount_type'] != ''){
                        if($variants['discount_type'] == 'price'){
                            $dis_price = $variants['original_price'] - $variants['off_price'];
                            $variants['off_percentage'] = Null;
                        }else if($variants['discount_type'] == 'percentage'){
                            $dis_price = $variants['original_price'] - ($variants['original_price'] * ($variants['off_percentage'] / 100));
                            $variants['off_price'] = Null;
                        }
                    }
                    
                    if(isset($variants['productVariantId'])){
                        $productvariant = ProductVariant::find($variants['productVariantId']);
                       $productvariant->product_id = $id;
                       $productvariant->variant_id = $variants['variant_id'];
                       $productvariant->variant_option_id = $variants['variant_option_id'];
                       $productvariant->qty = $variants['qty'];
                       $productvariant->sku = $variants['sku'];
                       $productvariant->weight = $variants['weight'];
                       $productvariant->color = $variants['color'];
                       $productvariant->discount_type = $variants['discount_type'];
                       $productvariant->off_price = $variants['off_price'];
                       $productvariant->off_percentage = $variants['off_percentage'];
                       $productvariant->original_price = $variants['original_price'];
                       $productvariant->discount_price = $dis_price;
                       $productvariant->status = 'active';
                       $productvariant->update();
                    }else{
                        $productvariant = new ProductVariant;
                       $productvariant->product_id = $id;
                       $productvariant->variant_id = $variants['variant_id'];
                       $productvariant->variant_option_id = $variants['variant_option_id'];
                       $productvariant->qty = $variants['qty'];
                       $productvariant->sku = $variants['sku'];
                       $productvariant->weight = $variants['weight'];
                       $productvariant->color = $variants['color'];
                       $productvariant->discount_type = $variants['discount_type'];
                       $productvariant->off_price = $variants['off_price'];
                       $productvariant->off_percentage = $variants['off_percentage'];
                       $productvariant->original_price = $variants['original_price'];
                       $productvariant->discount_price = $dis_price;
                       $productvariant->status = 'active';
                       $productvariant->save();
                    }

                    if (isset($variants['variantImages']) && count($variants['variantImages']) > 0) {

                        foreach ($variants['variantImages'] as $key=>$imageFile) {

                            $image = $imageFile;

                            $name = time().$key.'.'.$image->getClientOriginalExtension();

                            $destinationPath = public_path('/images/productsVariants');

                            $image->move($destinationPath,$name);

                            $productVariant = new ProductVariantImage;
                            $productVariant->product_variant_id = $productvariant->id;
                            $productVariant->image = $name;
                            $productVariant->save();

                        }
                    }
               }
            }else{
                ProductVariant::where('product_id',$id)->delete();
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
    public function delete_product_image($id){
        $productImage = ProductImage::find($id)->delete();
        if($productImage){
                return Response::json([
                    'status' => '200',
                    'message' => 'Product Image has been deleted'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Product Image has been not deleted'
                ], 401);
            }
    }
    public function delete_product_variant_image($id){
        $ProductVariantImage = ProductVariantImage::find($id)->delete();
        if($ProductVariantImage){
                return Response::json([
                    'status' => '200',
                    'message' => 'Product Variant Image has been deleted'
                ], 200);
            }else{
                return Response::json([
                    'status' => '401',
                    'message' => 'Product Variant Image has been not deleted'
                ], 401);
            }
    }
    public function delete($id){
        $product = Product::find($id);
        $product->delete();
        if($product){
            return Response::json([
                'status' => '200',
                'message' => 'Product move to trash successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Product has been not move in trash'
            ], 401);
        }
    }

    // Trash data section
    public function trash_products(){
        $products = Product::onlyTrashed()->get();
        if($products){
            return Response::json([
                'status' => '200',
                'message' => 'Trash Products list get successfully',
                'data' => $products
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Trash Products data not found'
            ], 404);
        }
    }
    public function trash_product_restore($id){
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        if($product){
            return Response::json([
                'status' => '200',
                'message' => 'Product data restored successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Product data has been not restored'
            ], 401);
        }
    }
    public function trash_product_delete($id){
        $product = Product::onlyTrashed()->findOrFail($id);
            foreach ($product->productImages as $image) {
                $image->delete();
            }
            // Delete related product variants and their images
            foreach ($product->productVariants as $variant) {
                // Delete variant images
                foreach ($variant->productVariantImages as $variantImage) {
                    $variantImage->delete();
                }
                // Delete the variant itself
                $variant->forceDelete();
            }
        $product->forceDelete();
        if($product){
            return Response::json([
                'status' => '200',
                'message' => 'Trash Product data deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Product data has been not deleted'
            ], 401);
        }
    }
    public function all_trash_products_delete(){
        $products = Product::onlyTrashed()->get();

        foreach ($products as $product) {
            // Delete related product images
            foreach ($product->productImages as $image) {
                $image->delete();
            }

            // Delete related product variants and their images
            foreach ($product->productVariants as $variant) {
                // Delete variant images
                foreach ($variant->productVariantImages as $variantImage) {
                    $variantImage->delete();
                }
                // Delete the variant itself
                $variant->forceDelete();
            }

            // Permanently delete the product
            $product->forceDelete();
        }
        if($product){
            return Response::json([
                'status' => '200',
                'message' => 'All Trash Products deleted successfully'
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Products has been not deleted'
            ], 401);
        }
    }
}
