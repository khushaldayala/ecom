<?php

namespace App\Traits;

use App\Models\OfferProduct;
use App\Models\Product;
use App\Models\SectionOffer;

trait OfferTrait
{
    public function updateProductVariantPrice($type, $discount, $variants)
    {
        if ($type != '') {
            if ($type == 0) {
                $discountPrice = $variants->original_price - $discount;
                $offPrice = $discount;
                $offPercentage = Null;
                $discountType = 'price';
            } elseif ($type == 1) {
                $discountPrice = $variants->original_price - ($variants->original_price * ($discount / 100));
                $offPercentage = $discount;
                $offPrice = Null;
                $discountType = 'percentage';
            }
        }

        $variants->update([
            'discount_price' => $discountPrice,
            'off_price' => $offPrice,
            'off_percentage' => $offPercentage,
            'discount_type' => $discountType
        ]);
    }
    
    public function productAssignToOffer($offer, $productIds)
    {
        $currentProductIds = OfferProduct::where('offer_id', $offer->id)->pluck('product_id')->toArray();

        $productIdsToDelete = array_diff($currentProductIds, $productIds);

        if (!empty($productIdsToDelete)) {
            OfferProduct::where('offer_id', $offer->id)
                ->whereIn('product_id', $productIdsToDelete)
                ->delete();
        }

        foreach ($productIds as $product) {
            OfferProduct::updateOrCreate(
                [
                    'product_id' => $product,
                    'offer_id' => $offer->id
                ],
                [
                    'user_id' => 1
                ]
            ); 
        }
    }

    public function offerAssignTosection($offer, $sectionIds)
    {
        SectionOffer::where('offer_id', $offer->id)->delete();
        if(count($sectionIds) > 0)
        {
            foreach ($sectionIds as $section) {
                if($section)
                {
                    SectionOffer::create([
                        'section_id' => $section,
                        'offer_id' => $offer->id,
                        'user_id' => 1
                    ]);
                }
            }
        }
    }

    public function assignedProducts($itemId, $request)
    {
        switch ($request->key) {
            case 'brand':
                $query = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages')->where('brand_id', $itemId);
                break;
            case 'category':
                $query = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages')->where('category_id', $itemId);
                break;
            case 'subcategory':
                $query = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages')->where('subcategory_id', $itemId);
                break;
            case 'offer':
                $productIds = OfferProduct::where('offer_id', $itemId)->pluck('product_id')->unique()->values()->toArray();
                $query = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages')->whereIn('id', $productIds);
                break;
            default:
                $query = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages');
                break;
        }


        $filterTypes = $request->input('filterTypes', []);
        $dateValue = $request->input('dateValue', null);
        $priceValue = $request->input('priceValue', null);
        $brandValue = $request->input('brandValue', []);
        $categoryValue = $request->input('categoryValue', []);
        $subCategoryValue = $request->input('subCategoryValue', []);
        $inventoryValue = $request->input('inventoryValue', null);

        if (!empty($filterTypes)) {
            foreach ($filterTypes as $filterType) {
                switch ($filterType) {
                    case 'brand':
                        if (!empty($brandValue)) {
                            $query->whereIn('brand_id', $brandValue);
                        }
                        break;
                    case 'category':
                        if (!empty($categoryValue)) {
                            $query->whereIn('category_id', $categoryValue);
                        }
                        break;
                    case 'subcategory':
                        if (!empty($subCategoryValue)) {
                            $query->whereIn('subcategory_id', $subCategoryValue);
                        }
                        break;
                    case 'price':
                        if (!is_null($priceValue)) {
                            $query->whereHas('productVariants', function ($q) use ($priceValue) {
                                $q->whereBetween('original_price', [$priceValue['min'], $priceValue['max']]);
                            });
                        }
                        break;
                    case 'date':
                        if (!is_null($dateValue)) {
                            $startDate = $dateValue['startdate'];
                            $endDate = $dateValue['enddate'];
                            $query->whereBetween('created_at', [$startDate, $endDate]);
                        }
                        break;
                    case 'inventory':
                        if (!is_null($inventoryValue)) {
                            if ($inventoryValue['is_sum']) {
                                $query->whereRaw(
                                    'EXISTS (
                                        SELECT 1
                                        FROM product_variants pv
                                        WHERE pv.product_id = products.id
                                        GROUP BY pv.product_id
                                        HAVING SUM(pv.qty) BETWEEN ? AND ?
                                    )',
                                    [$inventoryValue['min'], $inventoryValue['max']]
                                );
                            } else {
                                $query->whereHas('productVariants', function ($q) use ($inventoryValue) {
                                    $q->whereBetween('qty', [$inventoryValue['min'], $inventoryValue['max']]);
                                });
                            }
                        }
                        break;
                    default:
                        // Handle unexpected filter types if necessary
                        break;
                }
            }
        }

        if ($request->search) {
            $data = $query->where('product_name', 'LIKE', '%' . $request->search . '%')
                ->paginate(10);
        } else {
            $data = $query->paginate(10);
        }

        $data->getCollection()->transform(function ($product) {
            $product->is_assign = true;
            return $product;
        });

        return $data;
    }

    public function unassignedProducts($request)
    {
        switch ($request->key) {
            case 'brand':
                $query = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages')->whereNull('brand_id');
                break;
            case 'category':
                $query = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages')->whereNull('category_id');
                break;
            case 'subcategory':
                $query = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages')->whereNull('subcategory_id');
                break;
            case 'offer':
                $productIds = OfferProduct::pluck('product_id')->unique()->values()->toArray();
                $query = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages')->whereNotIn('id', $productIds);
                break;
            default:
                $query = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages');
                break;
        }

        $filterTypes = $request->input('filterTypes', []);
        $dateValue = $request->input('dateValue', null);
        $priceValue = $request->input('priceValue', null);
        $brandValue = $request->input('brandValue', []);
        $categoryValue = $request->input('categoryValue', []);
        $subCategoryValue = $request->input('subCategoryValue', []);
        $inventoryValue = $request->input('inventoryValue', null);

        if (!empty($filterTypes)) {
            foreach ($filterTypes as $filterType) {
                switch ($filterType) {
                    case 'brand':
                        if (!empty($brandValue)) {
                            $query->whereIn('brand_id', $brandValue);
                        }
                        break;
                    case 'category':
                        if (!empty($categoryValue)) {
                            $query->whereIn('category_id', $categoryValue);
                        }
                        break;
                    case 'subcategory':
                        if (!empty($subCategoryValue)) {
                            $query->whereIn('subcategory_id', $subCategoryValue);
                        }
                        break;
                    case 'price':
                        if (!is_null($priceValue)) {
                            $query->whereHas('productVariants', function ($q) use ($priceValue) {
                                $q->whereBetween('original_price', [$priceValue['min'], $priceValue['max']]);
                            });
                        }
                        break;
                    case 'date':
                        if (!is_null($dateValue)) {
                            $startDate = $dateValue['startdate'];
                            $endDate = $dateValue['enddate'];
                            $query->whereBetween('created_at', [$startDate, $endDate]);
                        }
                        break;
                    case 'inventory':
                        if (!is_null($inventoryValue)) {
                            if ($inventoryValue['is_sum']) {
                                $query->whereRaw(
                                    'EXISTS (
                                        SELECT 1
                                        FROM product_variants pv
                                        WHERE pv.product_id = products.id
                                        GROUP BY pv.product_id
                                        HAVING SUM(pv.qty) BETWEEN ? AND ?
                                    )',
                                    [$inventoryValue['min'], $inventoryValue['max']]
                                );
                            } else {
                                $query->whereHas('productVariants', function ($q) use ($inventoryValue) {
                                    $q->whereBetween('qty', [$inventoryValue['min'], $inventoryValue['max']]);
                                });
                            }
                        }
                        break;
                    default:
                        // Handle unexpected filter types if necessary
                        break;
                }
            }
        }

        if ($request->search) {
            $data = $query->where('product_name', 'LIKE', '%' . $request->search . '%')
                ->paginate(10);
        } else {
            $data = $query->paginate(10);
        }

        $data->getCollection()->transform(function ($product) {
            $product->is_assign = false;
            return $product;
        });

        return $data;
    }

    public function commonProducts($request)
    {
        $itemId = $request->id;
        $query = Product::with('productImages', 'productVariants', 'productVariants.productVariantImages');
    
        $filterTypes = $request->input('filterTypes', []);
        $dateValue = $request->input('dateValue', null);
        $priceValue = $request->input('priceValue', null);
        $brandValue = $request->input('brandValue', []);
        $categoryValue = $request->input('categoryValue', []);
        $subCategoryValue = $request->input('subCategoryValue', []);
        $inventoryValue = $request->input('inventoryValue', null);
    
        // Apply the key-based filter first
        switch ($request->key) {
            case 'brand':
                if ($itemId) {
                    $query->where(function ($q) use ($itemId) {
                        $q->where('brand_id', $itemId)
                          ->orWhereNull('brand_id');
                    });
                } else {
                    $query->whereNull('brand_id');
                }
                break;
            case 'category':
                if ($itemId) {
                    $query->where(function ($q) use ($itemId) {
                        $q->where('category_id', $itemId)
                          ->orWhereNull('category_id');
                    });
                } else {
                    $query->whereNull('category_id');
                }
                break;
            case 'subcategory':
                if ($itemId) {
                    $query->where(function ($q) use ($itemId) {
                        $q->where('subcategory_id', $itemId)
                          ->orWhereNull('subcategory_id');
                    });
                } else {
                    $query->whereNull('subcategory_id');
                }
                break;
            case 'offer':
                if ($itemId) {
                    $productIds1 = OfferProduct::pluck('product_id')->unique()->values()->toArray();
                    $productIds = OfferProduct::where('offer_id', $itemId)->pluck('product_id')->unique()->values()->toArray();
                    $query->whereIn('id', $productIds)->orWhereNotIn('id', $productIds1);
                } else {
                    $productIds = OfferProduct::pluck('product_id')->unique()->values()->toArray();
                    $query->whereNotIn('id', $productIds);
                }
                break;
        }
    
        // Apply the filters
        if (!empty($filterTypes)) {
            foreach ($filterTypes as $filterType) {
                switch ($filterType) {
                    case 'brand':
                        if (!empty($brandValue)) {
                            $query->whereIn('brand_id', $brandValue);
                        }
                        break;
                    case 'category':
                        if (!empty($categoryValue)) {
                            $query->whereIn('category_id', $categoryValue);
                        }
                        break;
                    case 'subcategory':
                        if (!empty($subCategoryValue)) {
                            $query->whereIn('subcategory_id', $subCategoryValue);
                        }
                        break;
                    case 'price':
                        if (!is_null($priceValue)) {
                            $query->whereHas('productVariants', function ($q) use ($priceValue) {
                                $q->whereBetween('original_price', [$priceValue['min'], $priceValue['max']]);
                            });
                        }
                        break;
                    case 'date':
                        if (!is_null($dateValue)) {
                            $startDate = $dateValue['startdate'];
                            $endDate = $dateValue['enddate'];
                            $query->whereBetween('created_at', [$startDate, $endDate]);
                        }
                        break;
                    case 'inventory':
                        if (!is_null($inventoryValue)) {
                            if ($inventoryValue['is_sum']) {
                                $query->whereRaw(
                                    'EXISTS (
                                        SELECT 1
                                        FROM product_variants pv
                                        WHERE pv.product_id = products.id
                                        GROUP BY pv.product_id
                                        HAVING SUM(pv.qty) BETWEEN ? AND ?
                                    )',
                                    [$inventoryValue['min'], $inventoryValue['max']]
                                );
                            } else {
                                $query->whereHas('productVariants', function ($q) use ($inventoryValue) {
                                    $q->whereBetween('qty', [$inventoryValue['min'], $inventoryValue['max']]);
                                });
                            }
                        }
                        break;
                }
            }
        }
    
        // Apply search filter
        if ($request->search) {
            $query->where('product_name', 'LIKE', '%' . $request->search . '%');
        }
    
        // Paginate the results
        $data = $query->paginate(10);
    
        $requestKey = $request->key;
    
        // Assign is_assign based on the key
        $data->each(function ($product) use ($requestKey) {
            $status = null;
            $product->is_assign = $status;
        });
    
        return $data;
    }
}