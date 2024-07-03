<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use DB;
use App\Models\ProductReleaseSchedule;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductReleaseScheduleController extends Controller
{
    public function get_inactive_product()
    {
        // $products = Product::with('productImages')->where('status','inactive')->get();
        $products = Product::with('productImages')
        ->where('status', 'inactive')
        ->whereNotIn('id', function ($query) {
            $query->select('product_id')
                ->from('product_release_schedules')
                ->whereRaw('product_release_schedules.product_id = products.id')
                ->whereNull('is_done');
        })
        ->get();
        if(count($products)>0){
            return Response::json([
                'status' => '200',
                'message' => 'Inactive Products list get successfully',
                'data' => $products
            ], 200);
        }else{
            return Response::json([
                'status' => '200',
                'message' => 'Inactive Products list is empty',
                'data' => $products
            ], 200);
        }
    }
    public function productSchedules()
    {
        $ProductReleaseSchedule = DB::table('product_release_schedules')
        ->select('slug', DB::raw('count(*) as total'), DB::raw('MAX(title) as title'), DB::raw('MAX(release_date) as release_date'))
        ->groupBy('slug')
        ->get();

        if($ProductReleaseSchedule){
            return Response::json([
                'status' => '200',
                'message' => 'Product schedule list get successfully',
                'data' => $ProductReleaseSchedule
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Product schedule data not found'
            ], 404);
        }
    }
    public function store(Request $request)
    {
        if (is_array($request->product_id)) {
            $proposedSlug = Str::slug($request->title);
            // Check if the proposed slug already exists in the database
            $slugExists = ProductReleaseSchedule::where('slug', $proposedSlug)->exists();

            if ($slugExists) {
                // If the proposed slug already exists, append a unique suffix
                $count = 1;
                while ($slugExists) {
                    $proposedSlug = Str::slug($request->title) . '-' . $count;
                    $slugExists = ProductReleaseSchedule::where('slug', $proposedSlug)->exists();
                    $count++;
                }
            }
            foreach ($request->product_id as $productId) {
                ProductReleaseSchedule::create([
                    'product_id' => $productId,
                    'title' => $request->title,
                    'slug' => $proposedSlug,
                    'release_date' => $request->releaseDate
                ]);
            }
        }
        return Response::json([
            'status' => '200',
            'message' => 'Product schedule created successfully'
        ], 200);
    }
    public function delete($slug)
    {
        $productschedule = ProductReleaseSchedule::where('slug', $slug)->delete();
        if($productschedule){
            return Response::json([
                'status' => '200',
                'message' => 'Product schedule deleted successfully',
            ], 200);
        }else{
            return Response::json([
                'status' => '401',
                'message' => 'Product schedule has been not deleted'
            ], 401);
        }
    }
    
    public function get_single_schedule_deltails($slug)
    {
        $productschedule = ProductReleaseSchedule::where('slug', $slug)->get();

        $productIds = $productschedule->pluck('product_id');

        $products = Product::with('productImages','productVariants','productVariants.productVariantImages')->whereIn('id', $productIds)->get();

        return Response::json([
            'status' => '200',
            'message' => 'Schedule products details get successfully',
            'data' => $products
        ], 200);
    }

    public function delete_scheduling_product($id)
    {
        ProductReleaseSchedule::where('product_id', $id)->delete();

        return Response::json([
            'status' => '200',
            'message' => 'product deleted successfully',
        ], 200);
    }

    public function add_scheduling_product(Request $request)
    {
        if (is_array($request->product_id)) {
            foreach ($request->product_id as $productId) {
                ProductReleaseSchedule::create([
                    'product_id' => $productId,
                    'title' => $request->title,
                    'slug' => $request->slug,
                    'release_date' => $request->releaseDate
                ]);
            }
        }
        return Response::json([
            'status' => '200',
            'message' => 'product added successfully',
        ], 200);
    }
}
