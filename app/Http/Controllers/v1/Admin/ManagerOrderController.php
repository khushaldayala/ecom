<?php

namespace App\Http\Controllers\v1\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CheckoutProducts;
use App\Models\Checkout;
use App\Models\Order;
use Response;
use DB;

class ManagerOrderController extends Controller
{
    public function getOrderList(){
        $orderList = CheckoutProducts::select(
        'checkout_id',
        DB::raw('MAX(product_name) as product_name'),
        DB::raw('MAX(price) as price'),
        DB::raw('MAX(qty) as qty'),
        DB::raw('MAX(total) as total'),
        DB::raw('MAX(image) as image'),
        DB::raw('MAX(order_status) as order_status'),
        DB::raw('COUNT(*) as total_products')
    )
    ->with(['checkout.order'])
    ->groupBy('checkout_id')
    ->get();
    
    return Response::json([
            'status' => '200',
            'message' => 'Order list get successfuly',
            'data' => $orderList
        ], 200);
        }

    public function getSingleOrder($id){
        $checkout = Checkout::with('checkoutProducts','order','order.orderNotes')->find($id);

        return Response::json([
            'status' => '200',
            'message' => 'Get single order data successfuly',
            'data' => $checkout
        ], 200);
    }
    
    public function paymentHistory()
    {
        $orders = Order::with('user')->get();

        return Response::json([
            'status' => '200',
            'message' => 'Get payment history successfuly',
            'data' => $orders
        ], 200);
    }
}
