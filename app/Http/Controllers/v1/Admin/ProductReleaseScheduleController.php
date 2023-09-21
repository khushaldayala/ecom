<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Models\ProductReleaseSchedule;
use App\Models\Product;

class ProductReleaseScheduleController extends Controller
{
    public function get_inactive_product()
    {
        $products = Product::with('productImages')->where('status','inactive')->get();
        if(count($products)>0){
            return Response::json([
                'status' => '200',
                'message' => 'Inactive Products list get successfully',
                'data' => $products
            ], 200);
        }else{
            return Response::json([
                'status' => '404',
                'message' => 'Inactive Products data not found'
            ], 404);
        }
    }
    public function store(Request $request)
    {
        try {
            if (is_array($request->product_id)) {
                foreach ($request->product_id as $productId) {
                    ProductReleaseSchedule::create([
                        'product_id' => $productId,
                        'title' => $title,
                        'release_date' => $releaseDate
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error creating product release schedules: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while creating product release schedules'], 500);
        }

    }
}
