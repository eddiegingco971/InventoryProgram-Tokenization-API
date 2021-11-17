<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class OrdersControllerAPI extends Controller {

    public $successStatus = 200;

    public function getAllOrders(Request $request) {
        $token = $request['t']; // t = token
        $user_id = $request['cashier']; // u = user_id

          // $orders = DB::table('orders')
        //     ->leftJOIN('products', 'orders.product_id', '=', 'products.id')
        //     ->select( 'orders.order_name','orders.address','products.product_name','orders.quantity_order', 'orders.created_at','orders.updated_at')
        //     ->get();

        //     return response()->json($orders, $this->successStatus);

        $users = User::where('id', $user_id)->where('remember_token', $token)->first();

        if ($users != null) {
            $orders = Orders::all();

            return response()->json($orders, $this->successStatus);
        } else {
            return response()->json(['response' => 'Bad Call'], 501);
        }        
    }

   

    public function getOrders(Request $request) {

        $product_id = $request['product_id'];
        $token = $request['t']; // t = token
        $user_id = $request['cashier']; // u = user_id

        $users = User::where('id', $user_id)->where('remember_token', $token)->first();

        if ($users != null) {
            $orders = Orders::where('product_id', $product_id)->get();

            if ($orders != null) {
                return response()->json($orders, $this->successStatus);
            } else {
                return response()->json(['response' => 'Orders not found!'], 404);
            }            
        } else {
            return response()->json(['response' => 'Bad Call'], 501);
        }  
    }

    public function searchOrders(Request $request) {

        $product_id = $request['search'];
        $token = $request['t']; // t = token
        $user_id = $request['cashier']; // u = user_id

        $users = User::where('id', $user_id)->where('remember_token', $token)->first();

        if ($users != null) {
            $orders = Orders::where('order_name', 'LIKE', '%' . $product_id . '%')
                ->orWhere('address', 'LIKE', '%' . $product_id . '%')
                ->get();
            // SELECT * FROM posts WHERE description LIKE '%params%' OR title LIKE '%params%'
            if ($orders != null) {
                return response()->json($orders, $this->successStatus);
            } else {
                return response()->json(['response' => 'Orders not found!'], 404);
            }            
        } else {
            return response()->json(['response' => 'Bad Call'], 501);
        }  
    }
}

?> 