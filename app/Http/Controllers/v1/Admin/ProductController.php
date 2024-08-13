<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Offer;
use App\Models\OfferProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\Product;
use App\Models\ProductVariantImage;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantAttribute;
use App\Models\SectionProduct;
use App\Traits\ProductTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ProductTrait;

    public function products(Request $request)
    {
        $userId = Auth::id();
        $sort = $request->input('sort');
        $search = $request->input('search');
        $isActive = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);

        $products = Product::where('user_id', $userId)
            ->with([
                'productImages',
                'productVariants' => function ($query) {
                    $query->with([
                        'productVariantImages',
                        'productVariantAttribute.attribut',
                        'productVariantAttribute.attributOption'
                    ]);
                }
            ]);

        if ($search) {
            $products = $products->where('product_name', 'LIKE', '%' . $search . '%');
        }

        if ($sort) {
            switch ($sort) {
                case 'asc':
                    $products->orderBy('product_name', 'asc');
                    break;
                case 'desc':
                    $products->orderBy('product_name', 'desc');
                    break;
            }
        } else {
            $products->latest();
        }

        if ($isActive) {
            $products = $products->get();
        } else {
            $products = $products->paginate();
        }

        return Response::json([
            'status' => '200',
            'message' => 'Products list get successfully',
            'data' => $products
        ], 200);
    }

    public function store(ProductStoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $userId = Auth::id();
            $product = new Product;
            $product->user_id = $userId;
            $product->category_id = $request->category_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->brand_id = $request->brand_id;
            $product->wishlist = '0';
            $product->product_name = $request->product_name;
            $product->description = $request->description;
            $product->status = $request->status;
            $product->save();

            if ($request->section_id) {
                $this->productAssignTosection($product, $request->section_id);
            }

            // if ($request->offer_id) {
            //     $this->productAssignToOffer($product, $request->offer_id);
            // }

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
                foreach ($request->productVariants as $index => $variants) {

                    if ($variants['offer_id']) {
                        $this->productAssignToOffer($product, $variants['offer_id']);
                    }

                    $offer = Offer::find($variants['offer_id']);
                    $discountPrice = null;
                    $discountType = null;
                    $offPrice = null;
                    $offPercentage = null;

                    if ($offer) {
                        if ($offer->type != '') {
                            if ($offer->type == 0) {
                                $discountPrice = $variants['price'] - $offer->discount;
                                $offPrice = $offer->discount;
                                $offPercentage = Null;
                                $discountType = 'price';
                            } elseif ($offer->type == 1) {
                                $discountPrice = $variants['price'] - ($variants['price'] * ($offer->discount / 100));
                                $offPercentage = $offer->discount;
                                $offPrice = Null;
                                $discountType = 'percentage';
                            }
                        }
                    }

                    $productVariant = new ProductVariant;
                    $productVariant->product_id = $productId;
                    // $productVariant->attribute_id = $variants['attribute_id'];
                    // $productVariant->attribute_option_id = $variants['attribute_option_id'];
                    $productVariant->offer_id = $variants['offer_id'];
                    $productVariant->name = $variants['name'];
                    $productVariant->qty = $variants['qty'];
                    $productVariant->sku = $variants['sku'];
                    $productVariant->discount_type = $discountType;
                    $productVariant->off_price = $offPrice;
                    $productVariant->off_percentage = $offPercentage;
                    $productVariant->original_price = $variants['price'];
                    $productVariant->discount_price = $discountPrice < 0 ? 0 : $discountPrice;
                    $productVariant->status = 'active';
                    $productVariant->save();

                    if ($variants['attribute_id']) {
                        foreach ($variants['attribute_id'] as $key => $attribute) {
                            ProductVariantAttribute::create([
                                'user_id' => Auth::id(),
                                'variant_id' => $productVariant->id,
                                'attribute_id' => $attribute,
                                'attribute_option_id' => $variants['attribute_option_id'][$key]
                            ]);
                        }
                    }

                    // Handle variant images
                    if (isset($variants['variantImages']) && count($variants['variantImages']) > 0) {
                        foreach ($variants['variantImages'] as $key => $imageFile) {
                            $image = $imageFile;
                            $name = time() . $index . $key . '.' . $image->getClientOriginalExtension();
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

            DB::commit();

            return response()->json([
                'status' => '200',
                'message' => 'Product data has been saved'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => '500',
                'message' => 'An error occurred while saving the product',
                'error' => $e->getMessage(),
            ],
                500
            );
        }
    }
    public function get_single_product(Product $product)
    {
        $product = $product->load([
            'productImages',
            'productVariants' => function ($query) {
                $query->with([
                    'productVariantImages',
                    'attribut',
                    'productVariantAttribute' => function ($subQuery) {
                        $subQuery->with([
                            'attribut',
                            'attributOption'
                        ]);
                    }
                ]);
            },
            'section_products.section',
            'offer_product.offer'
        ]);

        return Response::json([
            'status' => '200',
            'message' => 'Product data get successfully',
            'data' => $product
        ], 200);
    }
    public function update(ProductUpdateRequest $request, Product $product)
    {
        DB::beginTransaction();

        try {
            $userId = Auth::id();
            $product->user_id = $userId;
            $product->category_id = $request->category_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->brand_id = $request->brand_id;
            $product->wishlist = '0';
            $product->product_name = $request->product_name;
            $product->description = $request->description;
            $product->status = $request->status;
            $product->update();

            if ($request->section_id) {
                $this->productAssignTosection($product, $request->section_id);
            } else {
                SectionProduct::where('product_id', $product->id)->delete();
            }

            // $this->productAssignToOffer($product, $request->offer_id);

            $productId = $product->id;

            if ($request->hasFile('images')) {
                foreach ($request->images as $key => $images) {
                    $image = $images;
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

            // Remove product from offer
            OfferProduct::where('product_id', $product->id)->delete();

            if ($request->has('productVariants')) {
                ProductVariant::where('product_id', $productId)->delete();

                foreach ($request->productVariants as $index => $variants) {
                    if ($variants['offer_id']) {
                        $this->productAssignToOffer($product, $variants['offer_id']);
                    }

                    $offer = Offer::find($variants['offer_id']);
                    $discountPrice = null;
                    $discountType = null;
                    $offPrice = null;
                    $offPercentage = null;

                    if ($offer) {
                        if ($offer->type != '') {
                            if ($offer->type == 0) {
                                $discountPrice = $variants['price'] - $offer->discount;
                                $offPrice = $offer->discount;
                                $offPercentage = null;
                                $discountType = 'price';
                            } elseif ($offer->type == 1) {
                                $discountPrice = $variants['price'] - ($variants['price'] * ($offer->discount / 100));
                                $offPercentage = $offer->discount;
                                $offPrice = null;
                                $discountType = 'percentage';
                            }
                        }
                    }

                    if (isset($variants['productVariantId'])) {
                        $productvariant = ProductVariant::find($variants['productVariantId']);
                        $productvariant->product_id = $productId;
                        $productvariant->offer_id = $variants['offer_id'];
                        $productvariant->qty = $variants['qty'];
                        $productvariant->sku = $variants['sku'];
                        $productvariant->discount_type = $discountType;
                        $productvariant->off_price = $offPrice;
                        $productvariant->off_percentage = $offPercentage;
                        $productvariant->original_price = $variants['price'];
                        $productvariant->discount_price = $discountPrice < 0 ? 0 : $discountPrice;
                        $productvariant->status = 'active';
                        $productvariant->update();

                        ProductVariantAttribute::where('variant_id', $variants['productVariantId'])->delete();

                        if ($variants['attribute_id']) {
                            foreach ($variants['attribute_id'] as $key => $attribute) {
                                ProductVariantAttribute::create([
                                    'user_id' => Auth::id(),
                                    'variant_id' => $productvariant->id,
                                    'attribute_id' => $attribute,
                                    'attribute_option_id' => $variants['attribute_option_id'][$key]
                                ]);
                            }
                        }

                        if (isset($variants['existing_images'])) {
                            foreach ($variants['existing_images'] as $key => $existingImages) {
                                ProductVariantImage::create([
                                    'product_variant_id' => $productvariant->id,
                                    'image' => $existingImages
                                ]);
                            }   
                        }

                    } else {
                        $productvariant = new ProductVariant;
                        $productvariant->product_id = $productId;
                        $productvariant->offer_id = $variants['offer_id'];
                        $productvariant->qty = $variants['qty'];
                        $productvariant->sku = $variants['sku'];
                        $productvariant->discount_type = $discountType;
                        $productvariant->off_price = $offPrice;
                        $productvariant->off_percentage = $offPercentage;
                        $productvariant->original_price = $variants['price'];
                        $productvariant->discount_price = $discountPrice < 0 ? 0 : $discountPrice;
                        $productvariant->status = 'active';
                        $productvariant->save();

                        if (isset($variants['attribute_id'])) {
                            foreach ($variants['attribute_id'] as $key => $attribute) {
                                ProductVariantAttribute::create([
                                    'user_id' => Auth::id(),
                                    'variant_id' => $productvariant->id,
                                    'attribute_id' => $attribute,
                                    'attribute_option_id' => $variants['attribute_option_id'][$key]
                                ]);
                            }
                        }

                        if (isset($variants['existing_images'])) {
                            foreach ($variants['existing_images'] as $key => $existingImages) {
                                ProductVariantImage::create([
                                    'product_variant_id' => $productvariant->id,
                                    'image' => $existingImages
                                ]);
                            }   
                        }
                    }

                    if (isset($variants['variantImages']) && count($variants['variantImages']) > 0) {
                        foreach ($variants['variantImages'] as $key => $imageFile) {
                            $image = $imageFile;
                            $name = time() . $index . $key . '.' . $image->getClientOriginalExtension();
                            $destinationPath = public_path('/images/productsVariants');
                            $image->move($destinationPath, $name);

                            $productVariant = new ProductVariantImage;
                            $productVariant->product_variant_id = $productvariant->id;
                            $productVariant->image = $name;
                            $productVariant->save();
                        }
                    }
                }
            } else {
                ProductVariant::where('product_id', $productId)->delete();
            }

            DB::commit();
            return Response::json([
                'status' => '200',
                'message' => 'Product data has been updated'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::json([
                'status' => '500',
                'message' => 'An error occurred while updating the product data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete_product_image(ProductImage $productImage)
    {
        $productImage->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Product Image has been deleted'
        ], 200);
    }
    public function delete_product_variant_image(ProductVariantImage $ProductVariantImage)
    {
        $ProductVariantImage->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Product Variant Image has been deleted'
        ], 200);
    }
    public function delete(Product $product)
    {
        $product->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Product move to trash successfully'
        ], 200);
    }

    // Trash data section
    public function trash_products()
    {
        $userId = Auth::id();
        $products = Product::with('productImages')->where('user_id', $userId)->onlyTrashed()->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Trash Products list get successfully',
            'data' => $products
        ], 200);
    }
    public function trash_product_restore($product)
    {
        $product = Product::onlyTrashed()->findOrFail($product);
        $product->restore();

        return Response::json([
            'status' => '200',
            'message' => 'Product data restored successfully'
        ], 200);
    }
    public function trash_product_delete($product)
    {
        $product = Product::onlyTrashed()->findOrFail($product);
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

        return Response::json([
            'status' => '200',
            'message' => 'Trash Product data deleted successfully'
        ], 200);
    }
    public function all_trash_products_delete()
    {
        $userId = Auth::id();
        $products = Product::where('user_id', $userId)->onlyTrashed()->get();

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

        return Response::json([
            'status' => '200',
            'message' => 'All Trash Products deleted successfully'
        ], 200);
    }
    public function delete_product_variant(ProductVariant $productVariant)
    {
        $productVariant->productVariantImages()->delete();
        $productVariant->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Product variant deleted successfully'
        ], 200);
    }

    public function remove_product_section(SectionProduct $section)
    {
        $section->delete();

        return Response::json([
            'status' => '200',
            'message' => 'Product has been successfully removed from the section.'
        ], 200);
    }

    public function assigned()
    {
        $userId = Auth::id();
        $productIds = SectionProduct::where('user_id', $userId)->pluck('product_id')->unique()->values()->toArray();
        $data = Product::where('user_id', $userId)->whereIn('id', $productIds)->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Assigned product list.',
            'data' => $data
        ], 200);
    }

    public function unassigned()
    {
        $userId = Auth::id();
        $productIds = SectionProduct::where('user_id', $userId)->pluck('product_id')->unique()->values()->toArray();
        $data = Product::where('user_id', $userId)->whereNotIn('id', $productIds)->paginate(10);

        return Response::json([
            'status' => '200',
            'message' => 'Unassigned product list.',
            'data' => $data
        ], 200);
    }

    public function statusUpdate(Product $product)
    {
        if ($product->status == 'active') {
            $status = 'inactive';
        } else {
            $status = 'active';
        }

        $product->update(['status' => $status]);

        return Response::json([
            'status' => '200',
            'message' => 'Product status updated successfully.',
        ], 200);
    }
}
