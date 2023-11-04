<?php

namespace App\Http\Controllers\v1\mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use DB;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Section;
use App\Models\Offer;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\Advertise;
use App\Models\Brand;
use App\Models\IntroScreen;
use App\Models\Wishlist;

class HomeController extends Controller
{
    public function search(Request $request)
    {

        $data = Product::with('productVariants', 'productVariants.productVariantImages', 'productVariants.productVariantImages', 'productImages')
            ->where(function ($query) use ($request) {
                $query->where('status', 'active')
                    ->where(function ($query) use ($request) {
                        $query->where('product_name', 'like', '%' . $request->search . '%')
                            ->orWhere('description', 'like', '%' . $request->search . '%')
                            ->orWhere('more_info', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('productVariants', function ($query) use ($request) {
                        $query->where('discount_price', 'like', '%' . $request->search . '%')
                            ->orWhere('sku', 'like', '%' . $request->search . '%')
                            ->orWhere('weight', 'like', '%' . $request->search . '%')
                            ->orWhere('color', 'like', '%' . $request->search . '%');
                    });
            })
            ->get();

        $data->each(function ($product) {
            $product->wishlist_flag = $product->wishlists->isNotEmpty();
            unset($product->wishlists); // Remove the wishlists data if needed
        });

        $data->each(function ($product) {
            $product->addtocart_flag = $product->addtocart->isNotEmpty();
            unset($product->addtocart); // Remove the wishlists data if needed
        });

        return Response::json([
            'status' => '200',
            'message' => 'Product list get successfully',
            'data' => $data
        ], 200);
    }
    public function instrtoscreen()
    {
        $intro = IntroScreen::where('status', 'active')->get();
        if ($intro) {
            return Response::json([
                'status' => '200',
                'message' => 'Intro screen data get successfully',
                'data' => $intro
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Data not found'
            ], 404);
        }
    }
    public function index()
    {
        // // get all active advertise
        // $advertise = Advertise::where('status','active')->get(['id','title','description','image','link']);
        // // get all active banners
        // $banners = Banner::where('status','active')->where('showtype','mobile')->get(['id','title','description','image']);
        // // get all active categories
        // $category = Category::where('status','active')->get(['id','title','description','image']);
        // // get all active offers
        // $offer = Offer::where('status','active')->get(['id','title','description','image','coupon_code','link']);
        // // get all section + product + product images
        // $products = Section::with(['products' => function($query) {
        //     $query->select('id','category_id','subcategory_id','fabric_id','color_id','section_id','wishlist','product_name','description','more_info');
        // }, 'products.productImages' => function($query) {
        //     $query->select('id','product_id','image');
        // }])
        // ->select('id','title','description')
        // ->where('status','active')
        // ->get();

        // return Response::json([
        //     'status' => '200',
        //     'message' => 'Home page data',
        //     'advertise' => $advertise,
        //     'banner' => $banners,
        //     'category' => $category,
        //     'offer' => $offer,
        //     'Product' => $products
        // ], 200);

        // The following query is use full
        // $product = Section::with('products.productImages','products.productVariants')->get();
        $section = Section::where('status', 'active')->get();
        if (count($section) > 0) {
            return Response::json([
                'status' => '200',
                'message' => 'Section data get successful',
                'data' => $section
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Section data not found',
            ]);
        }
    }
    public function section()
    {
        $section = Section::where('status', 'active')->get();
        if (count($section) > 0) {
            return Response::json([
                'status' => '200',
                'message' => 'Section data get successful',
                'data' => $section
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Section data not found',
            ]);
        }
    }
    public function advertise($id)
    {
        // $advertise = Advertise::where('status','active')->get();
        $advertise = Section::with(['advertise' => function ($query) {
                $query->where('status', 'active');
            }])
            ->where('id', $id)
            ->where('status', 'active')
            ->get();

        if (count($advertise) > 0) {
            return Response::json([
                'status' => '200',
                'message' => 'Advertise data get successful',
                'data' => $advertise
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Advertise data not found',
            ]);
        }
    }
    public function banner($id)
    {
        $banner = Section::with(['banner' => function ($query) {
                $query->where('status', 'active');
            }])
            ->where('id', $id)
            ->where('status', 'active')
            ->get();

        if (count($banner) > 0) {
            return Response::json([
                'status' => '200',
                'message' => 'Banner data get successful',
                'data' => $banner
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Banner data not found',
            ]);
        }
    }
    public function categories($id)
    {
        $categories = Section::with(['category' => function ($query) {
                $query->where('status', 'active');
            }])
            ->where('id', $id)
            ->where('status', 'active')
            ->get();
        if (count($categories) > 0) {
            return Response::json([
                'status' => '200',
                'message' => 'Categories data get successful',
                'data' => $categories
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Categories data not found',
            ]);
        }
    }
    public function offer($id)
    {
        $offer = Section::with(['offer' => function ($query) {
                $query->where('status', 'active');
            }])
            ->where('id', $id)
            ->where('status', 'active')
            ->get();
        if (count($offer) > 0) {
            return Response::json([
                'status' => '200',
                'message' => 'Offer data get successful',
                'data' => $offer
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Offer data not found',
            ]);
        }
    }
    public function brand($id)
    {
        $brand = Section::with(['brand' => function ($query) {
                $query->where('status', 'active');
            }])
            ->where('id', $id)
            ->where('status', 'active')
            ->get();
        if (count($brand) > 0) {
            return Response::json([
                'status' => '200',
                'message' => 'Brand data get successful',
                'data' => $brand
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Brand data not found',
            ], 404);
        }
    }
    public function product($slug)
    {
        if ($slug == 'NEW-ARRIVALS') {

            $data = Product::with(['productImages', 'productVariants', 'ratings' => function ($query) {
                $query->select('product_id', DB::raw('avg(rating) as rating_avg'))
                    ->groupBy('product_id');
            }])
                ->latest()
                ->limit(10)
                ->where('status', 'active')
                ->get();
        } else if ($slug == 'RECENTLY-VIEWED') {

            $data = Product::with(['productImages', 'productVariants', 'ratings' => function ($query) {
                $query->select('product_id', DB::raw('avg(rating) as rating_avg'))
                    ->groupBy('product_id');
            }])
                ->whereHas('wishlists', function ($query) {
                    $query->orderBy('created_at', 'desc');
                })
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        } else if ($slug == 'BEST-SELLERS') {

            $data = Product::with(['productImages', 'productVariants', 'ratings' => function ($query) {
                $query->select('product_id', DB::raw('avg(rating) as rating_avg'))
                    ->groupBy('product_id');
            }])
                ->whereHas('oders', function ($query) {
                    $query->orderBy('created_at', 'desc');
                })
                ->take(10)
                ->get();
        } else if ($slug == 'TOP-RATED') {

            $data = Product::with(['productVariants', 'productImages'])
                ->withCount(['ratings as avg_rating' => function ($query) {
                    $query->select(DB::raw('SUM(rating) / COUNT(*)'));
                }])
                ->orderBy('avg_rating', 'desc')
                ->take(10)
                ->get();
        } else if ($slug == 'MOST-VIEWED') {

            $data = Product::with(['productImages', 'productVariants', 'ratings' => function ($query) {
                $query->select('product_id', DB::raw('avg(rating) as rating_avg'))
                    ->groupBy('product_id');
            }])
                ->orderBy('view_count', 'desc')
                ->take(10)
                ->get();
        } else if ($slug == 'OTHER') {
            return 'other';
        } else {

            return Response::json([
                'status' => '200',
                'message' => 'This type is not match with any of the product types listed'
            ], 200);
        }
        if (count($data) > 0) {
            return Response::json([
                'status' => '200',
                'message' => 'Product data getting successfully',
                'key-word' => $slug,
                'data' => $data
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Product data not found',
            ], 404);
        }
    }
    public function categoriesSubcategory()
    {
        $categories = Category::with(['subcategory' => function ($query) {
                $query->where('status', 'active');
            }])
            ->where('status', 'active')
            ->get();
        if (count($categories) > 0) {
            return Response::json([
                'status' => '200',
                'message' => 'Categories data get successful',
                'data' => $categories
            ], 200);
        } else {
            return Response::json([
                'status' => '404',
                'message' => 'Categories data not found',
            ]);
        }
    }
    public function testcurrency()
    {
        echo convertCurrency(1500, 'USD', 'INR');
    }
    public function subcategoryProduct($id)
    {
        // $subCategories = Subcategory::with('products')->where('id',$id)->get();
        $subCategories = Product::with(['productImages', 'productVariants', 'ratings' => function ($query) {
            $query->select('product_id', DB::raw('avg(rating) as rating_avg'))
                ->groupBy('product_id');
        }])
            ->where('subcategory_id', $id)
            ->where('status', 'active')
            ->get();

        $subCategories->each(function ($product) {
            $product->wishlist_flag = $product->wishlists->isNotEmpty();
            unset($product->wishlists); // Remove the wishlists data if needed
        });

        $subCategories->each(function ($product) {
            $product->addtocart_flag = $product->addtocart->isNotEmpty();
            unset($product->addtocart); // Remove the wishlists data if needed
        });

        if ($subCategories) {
            return Response::json([
                'status' => '200',
                'message' => 'Subcategory wise product data get successful',
                'data' => $subCategories
            ], 200);
        } else {
            return Response::json([
                'status' => '200',
                'message' => 'Subcategory wise product data is empty',
                'data' => $subCategories
            ], 200);
        }
    }

    public function price_range_filter(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $id = $request->subcategory_id; // Assuming you have this value in your request

        if ($id) {
            $products = Product::with(['productImages', 'productVariants', 'ratings' => function ($query) {
                $query->select('product_id', DB::raw('avg(rating) as rating_avg'))
                    ->groupBy('product_id');
            }])
                ->when($id, function ($query) use ($start, $end) {
                    return $query->whereHas('productVariants', function ($variantQuery) use ($start, $end) {
                        $variantQuery->whereBetween('discount_price', [$start, $end]);
                    });
                })
                ->where('subcategory_id', $id)
                ->where('status', 'active')
                ->get();
        } else {
            $products = Product::with(['productImages', 'productVariants', 'ratings' => function ($query) {
                $query->select('product_id', DB::raw('avg(rating) as rating_avg'))
                    ->groupBy('product_id');
            }])
                ->when($id, function ($query) use ($start, $end) {
                    return $query->whereHas('productVariants', function ($variantQuery) use ($start, $end) {
                        $variantQuery->whereBetween('discount_price', [$start, $end]);
                    });
                })
                ->where('status', 'active')
                ->get();
        }

        $products->each(function ($product) {
            $product->wishlist_flag = $product->wishlists->isNotEmpty();
            unset($product->wishlists); // Remove the wishlists data if needed
        });

        $products->each(function ($product) {
            $product->addtocart_flag = $product->addtocart->isNotEmpty();
            unset($product->addtocart); // Remove the wishlists data if needed
        });

        if ($products) {
            return Response::json([
                'status' => '200',
                'message' => 'Product data filter successfully',
                'data' => $products
            ], 200);
        } else {
            return Response::json([
                'status' => '200',
                'message' => 'Product data is empty',
                'data' => $products
            ], 200);
        }
    }

    public function color_filter(Request $request)
    {
        $color = $request->color; // Assuming you have this value in your request
        $id = $request->subcategory_id; // Assuming you have this value in your request

        if ($id) {
            $products = Product::with(['productImages', 'productVariants', 'ratings' => function ($query) {
                $query->select('product_id', DB::raw('avg(rating) as rating_avg'))
                    ->groupBy('product_id');
            }])
                ->where('status', 'active')
                ->when($color, function ($query) use ($color) {
                    return $query->whereHas('productVariants', function ($variantQuery) use ($color) {
                        $variantQuery->where('color', $color);
                    });
                })
                ->where('subcategory_id', $id)
                ->get();
        } else {
            $products = Product::with(['productImages', 'productVariants', 'ratings' => function ($query) {
                $query->select('product_id', DB::raw('avg(rating) as rating_avg'))
                    ->groupBy('product_id');
            }])
                ->where('status', 'active')
                ->when($color, function ($query) use ($color) {
                    return $query->whereHas('productVariants', function ($variantQuery) use ($color) {
                        $variantQuery->where('color', $color);
                    });
                })
                ->get();
        }

        $products->each(function ($product) {
            $product->wishlist_flag = $product->wishlists->isNotEmpty();
            unset($product->wishlists); // Remove the wishlists data if needed
        });

        $products->each(function ($product) {
            $product->addtocart_flag = $product->addtocart->isNotEmpty();
            unset($product->addtocart); // Remove the wishlists data if needed
        });

        if ($products) {
            return Response::json([
                'status' => '200',
                'message' => 'Product data filter successfully',
                'data' => $products
            ], 200);
        } else {
            return Response::json([
                'status' => '200',
                'message' => 'Product data is empty',
                'data' => $products
            ], 200);
        }
    }
}
