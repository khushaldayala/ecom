<?php

namespace App\Traits;

use App\Models\SectionAdvertise;
use App\Models\SectionBanner;
use App\Models\SectionBrand;
use App\Models\SectionCategory;
use App\Models\SectionOffer;
use App\Models\SectionProduct;
use Illuminate\Support\Facades\Auth;

trait SectionTrait
{
    public function assignToSection($type, $section, $itemsIds)
    {
        $this->removeItemsSection($section);
        switch ($type) {
            case 'Product':
                SectionProduct::whereIn('product_id', $itemsIds)->delete();
                foreach ($itemsIds as $item) {
                    SectionProduct::create([
                        'section_id' => $section->id,
                        'product_id' => $item,
                        'user_id' => Auth::id()
                    ]);
                }
                break;
            case 'Offer':
                SectionOffer::whereIn('offer_id', $itemsIds)->delete();
                foreach ($itemsIds as $item) {
                    SectionOffer::create([
                        'section_id' => $section->id,
                        'offer_id' => $item,
                        'user_id' => Auth::id()
                    ]);
                }
                break;
            case 'Categories':
                SectionCategory::whereIn('category_id', $itemsIds)->delete();
                foreach ($itemsIds as $item) {
                    SectionCategory::create([
                        'section_id' => $section->id,
                        'category_id' => $item,
                        'user_id' => Auth::id()
                    ]);
                }
                break;
            case 'Brand':
                SectionBrand::whereIn('brand_id', $itemsIds)->delete();
                foreach ($itemsIds as $item) {
                    SectionBrand::create([
                        'section_id' => $section->id,
                        'brand_id' => $item,
                        'user_id' => Auth::id()
                    ]);
                }
                break;
            case 'SliderBanner':
                SectionBanner::whereIn('banner_id', $itemsIds)->delete();
                foreach ($itemsIds as $item) {
                    SectionBanner::create([
                        'section_id' => $section->id,
                        'banner_id' => $item,
                        'user_id' => Auth::id()
                    ]);
                }
                break;
            case 'Advertise':
                SectionAdvertise::whereIn('advertise_id', $itemsIds)->delete();
                foreach ($itemsIds as $item) {
                    SectionAdvertise::create([
                        'section_id' => $section->id,
                        'advertise_id' => $item,
                        'user_id' => Auth::id()
                    ]);
                }
                break;
            default:
                // Handle default case
                break;
        }
    }

    public function removeItemsSection($section)
    {
        try {
            switch ($section->keywords) {
                case 'Product':
                    SectionProduct::where('section_id', $section->id)->delete();
                    break;
                case 'Offer':
                    SectionOffer::where('section_id', $section->id)->delete();
                    break;
                case 'Categories':
                    SectionCategory::where('section_id', $section->id)->delete();
                    break;
                case 'Brand':
                    SectionBrand::where('section_id', $section->id)->delete();
                    break;
                case 'SliderBanner':
                    SectionBanner::where('section_id', $section->id)->delete();
                    break;
                case 'Advertise':
                    SectionAdvertise::where('section_id', $section->id)->delete();
                    break;
                default:
                    // Handle default case
                    break;
            }
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            return response()->json(['error' => 'Something went wrong.']);
        }
    }

    public function assignedItems($section)
    {
        try {
            switch ($section->keywords) {
                case 'Product':
                    return $section->load(
                        'section_products.product',
                        'section_products.product.productImages',
                        'section_products.product.productVariants',
                        'section_products.product.productVariants.productVariantImages');
                    break;
                case 'Offer':
                    return $section->load('section_offers.offer');
                    break;
                case 'Categories':
                    return $section->load('section_categories.category');
                    break;
                case 'Brand':
                    return $section->load('section_brands.brand');
                    break;
                case 'SliderBanner':
                    return $section->load('section_banners.banner');
                    break;
                case 'Advertise':
                    return $section->load('section_advertise.advertise');
                    break;
                default:
                    // Handle default case
                    break;
            }
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            return response()->json(['error' => 'Something went wrong.']);
        }
    }

    public function assignedItemsCount($section)
    {
        try {
            switch ($section->keywords) {
                case 'Product':
                    return $section->section_products()->count();
                    break;
                case 'Offer':
                    return $section->section_offers()->count();
                    break;
                case 'Categories':
                    return $section->section_categories()->count();
                    break;
                case 'Brand':
                    return $section->section_brands()->count();
                    break;
                case 'SliderBanner':
                    return $section->section_banners()->count();
                    break;
                case 'Advertise':
                    return $section->section_advertise()->count();
                    break;
                default:
                    return 0;
                    break;
            }
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            return response()->json(['error' => 'Something went wrong.']);
        }
    }

    public function assignedItemsPreview($section)
    {
        try {
            switch ($section->keywords) {
                case 'Product':
                    return $section->load('section_products.product');
                    break;
                case 'Offer':
                    return $section->load('section_offers.offer');
                    break;
                case 'Categories':
                    return $section->load('section_categories.category');
                    break;
                case 'Brand':
                    return $section->load('section_brands.brand');
                    break;
                case 'SliderBanner':
                    return $section->load('section_banners.banner');
                    break;
                case 'Advertise':
                    return $section->load('section_advertise.advertise');
                    break;
                default:
                    return 0;
                    break;
            }
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            return response()->json(['error' => 'Something went wrong.']);
        }
    }

}
