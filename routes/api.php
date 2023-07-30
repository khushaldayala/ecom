<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\Mobile\UserController;
use App\Http\Controllers\v1\Mobile\HomeController;
use App\Http\Controllers\v1\Mobile\WishlistController;
use App\Http\Controllers\v1\Mobile\MobileProductController;
use App\Http\Controllers\v1\Mobile\RatingReviewController;
use App\Http\Controllers\v1\Mobile\MobileAddtocartController;
use App\Http\Controllers\v1\Mobile\MobileNotification;
use App\Http\Controllers\v1\Mobile\PaymentController;
use App\Http\Controllers\v1\Mobile\KeywordController;

use App\Http\Controllers\v1\Admin\BannerController;
use App\Http\Controllers\v1\Admin\CategoryController;
use App\Http\Controllers\v1\Admin\SectionController;
use App\Http\Controllers\v1\Admin\OfferController;
use App\Http\Controllers\v1\Admin\ProductController;
use App\Http\Controllers\v1\Admin\FabricController;
use App\Http\Controllers\v1\Admin\SubCategoryController;
use App\Http\Controllers\v1\Admin\VariantController;
use App\Http\Controllers\v1\Admin\VariantOptionController;
use App\Http\Controllers\v1\Admin\AdvertiseController;
use App\Http\Controllers\v1\Admin\BrandController;
use App\Http\Controllers\v1\Admin\IntroScreenController;
use App\Http\Controllers\v1\Admin\FilterController;
use App\Http\Controllers\v1\Admin\FilterOptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/v1/get_user_details', function (Request $request) {
    return $request->user();
});

Route::group(array('prefix' => 'v1'), function()
{
    Route::controller(UserController::class)->group(function () {                                   // This API group uses for get User authentication
        Route::middleware(['auth:sanctum'])->group(function (){
            Route::post('store_forgot_password','store_forgot_password')->name('store_forgot_password');
            Route::post('update_profile','update_profile')->name('update_profile');
            Route::post('logout','logout')->name('logout');
        });
        Route::post('send_otp','send_otp')->name('send_otp');
        Route::post('checkotp','checkotp')->name('checkotp');
        Route::post('registration','registration')->name('registration');
        Route::post('resend_otp','resend_otp')->name('resend_otp');
        Route::post('login','login')->name('login');
        Route::post('forgot_password','forgot_password')->name('forgot_password');
    });

    Route::controller(HomeController::class)->group(function () {                                   // This API group uses for get home screen data
        Route::get('instrtoscreen','instrtoscreen')->name('instrtoscreen');
        Route::get('get_home_data','index')->name('get_home_data');
        Route::get('section','section')->name('section');
        Route::get('advertise/{id}','advertise')->name('advertise');
        Route::get('banner/{id}','banner')->name('banner');
        Route::get('categories/{id}','categories')->name('categories');
        Route::get('offer/{id}','offer')->name('offer');
        Route::get('brand/{id}','brand')->name('brand');
        Route::get('product/{slug}','product')->name('product');
        Route::post('search','search')->name('search');
        Route::get('testcurrency','testcurrency')->name('testcurrency');
    });

    Route::controller(MobileAddtocartController::class)->group(function () {
        Route::post('store_addtocart','store')->name('store_addtocart');
        Route::delete('remove_cart/{id}','remove_cart')->name('remove_cart')->middleware('auth:sanctum');
        Route::post('update_cart','update_cart')->name('update_cart');
        Route::get('get_addtocart_count/{id}','get_addtocart_count')->name('get_addtocart_count');
        Route::get('get_cart_items/{id}','get_cart_items')->name('get_cart_items');
    });

    Route::controller(KeywordController::class)->group(function () {
        Route::get('get_keywords','get_keywords')->name('get_keywords');
        Route::get('get_product_keywords_options','get_product_keywords_options')->name('get_product_keywords_options');
    });

    Route::controller(PaymentController::class)->group(function () {
        Route::post('store_checkout','store_checkout')->name('store_checkout');
    });

    Route::controller(MobileNotification::class)->group(function () {
        Route::get('notifications/{id}','notifications')->name('notifications');
        Route::get('notification_count/{id}','notification_count')->name('notification_count');
        Route::get('notification_detail/{id}','notification_detail')->name('notification_detail');
    });

    Route::controller(MobileProductController::class)->group(function () {                          // This API group uses for get Product data
        Route::get('get_single_product/{id}','get_single_product')->name('get_single_product');
        Route::get('get_all_product','get_all_product')->name('get_all_product');
        Route::get('get_category_wise_product/{id}','get_category_wise_product')->name('get_category_wise_product');
        Route::get('get_brand_wise_product/{id}','get_brand_wise_product')->name('get_brand_wise_product');
    });

    Route::post('wishlist_store',[WishlistController::class,'store'])->name('wishlist_store');

    Route::middleware(['auth:sanctum'])->group(function (){
        Route::post('store_rating',[RatingReviewController::class,'store_rating'])->name('store_rating');
        Route::post('store_review',[RatingReviewController::class,'store_review'])->name('store_review');
    });

    // Admin panel
    Route::controller(BannerController::class)->group(function () {
        Route::get('banners','banners')->name('banners');
        Route::get('trash_banners','trash_banners')->name('trash_banners');
        Route::delete('delete_banner/{id}','delete')->name('delete_banner');
        Route::delete('trash_delete_banner/{id}','trash_delete')->name('trash_delete_banner');
        Route::get('trash_delete_all_banner','all_trash_delete')->name('trash_delete_all_banner');
        Route::get('trash_restore_banner/{id}','trash_restore')->name('trash_restore_banner');
        Route::get('get_single_banner/{id}','get_single_banner')->name('get_single_banner');
        Route::post('update_banner/{id}','update')->name('update_banner');
        Route::post('banner_store','store')->name('banner_store');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('categories','categories')->name('categories');
        Route::post('category_store','store')->name('category_store');
        Route::post('update_category/{id}','update')->name('update_category');
        Route::get('get_single_category/{id}','get_single_category')->name('get_single_category');
        Route::delete('delete_category/{id}','delete')->name('delete_category');
        Route::get('trash_categories','trash_categories')->name('trash_categories');
        Route::get('trash_restore/{id}','trash_restore')->name('trash_restore');
        Route::delete('trash_delete/{id}','trash_delete')->name('trash_delete');
        Route::get('all_trash_delete','all_trash_delete')->name('all_trash_delete');
    });

    Route::controller(SectionController::class)->group(function () {
        Route::get('sections','sections')->name('sections');
        Route::post('section_store','store')->name('section_store');
        Route::get('get_single_section/{id}','get_single_section')->name('get_single_section');
        Route::post('update_section/{id}','update_section')->name('update_section');
        Route::delete('delete_section/{id}','delete')->name('delete_section');
        Route::get('trash_section','trash_section')->name('trash_section');
        Route::get('trash_restore_section/{id}','trash_restore_section')->name('trash_restore_section');
        Route::delete('trash_delete_section/{id}','trash_delete_section')->name('trash_delete_section');
        Route::get('all_trash_delete_section','all_trash_delete_section')->name('all_trash_delete_section');
    });

    Route::controller(OfferController::class)->group(function () {
        Route::get('offers','offers')->name('offers');
        Route::post('offer_store','store')->name('offer_store');
        Route::post('update_offer/{id}','update')->name('update_offer');
        Route::get('get_single_offer/{id}','get_single_offer')->name('get_single_offer');
        Route::delete('delete_offer/{id}','delete')->name('delete_offer');
        Route::get('trash_offer','trash_offer')->name('trash_offer');
        Route::get('trash_offer_restore/{id}','trash_offer_restore')->name('trash_offer_restore');
        Route::delete('trash_offer_delete/{id}','trash_offer_delete')->name('trash_offer_delete');
        Route::get('all_trash_offer_delete','all_trash_offer_delete')->name('all_trash_offer_delete');
    });

    Route::controller(VariantController::class)->group(function () {
        Route::get('variants','variants')->name('variants');
        Route::post('variant_store','store')->name('variant_store');
        Route::post('update_variant/{id}','update')->name('update_variant');
        Route::get('get_single_variant/{id}','get_single_variant')->name('get_single_variant');
        Route::delete('delete_variant/{id}','delete')->name('delete_variant');
        Route::get('trash_variant','trash_variant')->name('trash_variant');
        Route::get('trash_variant_restore/{id}','trash_variant_restore')->name('trash_variant_restore');
        Route::delete('trash_variant_delete/{id}','trash_variant_delete')->name('trash_variant_delete');
        Route::get('all_trash_variant_delete','all_trash_variant_delete')->name('all_trash_variant_delete');
    });

    Route::controller(VariantOptionController::class)->group(function () {
        Route::get('variant_options','variant_options')->name('variant_options');
        Route::post('variant_option_store','store')->name('variant_option_store');
        Route::post('update_variant_option/{id}','update')->name('update_variant_option');
        Route::get('get_single_variant_option/{id}','get_single_variant_option')->name('get_single_variant_option');
        Route::delete('delete_variant_option/{id}','delete')->name('delete_variant_option');
        Route::get('trash_variant_option','trash_variant_option')->name('trash_variant_option');
        Route::get('trash_variant_option_restore/{id}','trash_variant_option_restore')->name('trash_variant_option_restore');
        Route::delete('trash_variant_option_delete/{id}','trash_variant_option_delete')->name('trash_variant_option_delete');
        Route::get('all_trash_variant_option_delete','all_trash_variant_option_delete')->name('all_trash_variant_option_delete');
    });

    Route::controller(AdvertiseController::class)->group(function () {
        Route::get('advertises','advertises')->name('advertises');
        Route::post('advertise_store','store')->name('advertise_store');
        Route::post('update_advertise/{id}','update')->name('update_advertise');
        Route::get('get_single_advertise/{id}','get_single_advertise')->name('get_single_advertise');
        Route::delete('delete_advertise/{id}','delete')->name('delete_advertise');
        Route::get('trash_advertise','trash_advertise')->name('trash_advertise');
        Route::get('trash_advertise_restore/{id}','trash_advertise_restore')->name('trash_advertise_restore');
        Route::delete('trash_advertise_delete/{id}','trash_advertise_delete')->name('trash_advertise_delete');
        Route::get('all_trash_advertise_delete','all_trash_advertise_delete')->name('all_trash_advertise_delete');
    });

    Route::post('product_store',[ProductController::class,'store'])->name('product_store');
    Route::post('fabric_store',[FabricController::class,'store'])->name('fabric_store');
    Route::post('subcategory_store',[SubCategoryController::class,'store'])->name('subcategory_store');
    Route::post('brand_store',[BrandController::class,'store'])->name('brand_store');
    Route::post('intro_screen_store',[IntroScreenController::class,'store'])->name('intro_screen_store');
    Route::post('filteroption_store',[FilterOptionController::class,'store'])->name('filteroption_store');


    Route::controller(FilterController::class)->group(function () {
        Route::get('filters','filters')->name('filters');
        Route::post('filter_store','store')->name('filter_store');
    });
});
