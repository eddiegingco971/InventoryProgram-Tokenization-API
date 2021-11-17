<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\Sales_Details;
use App\Models\Orders;
use App\Models\Products;
use App\Models\User;
use App\Models\Logs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Flash;
use Response;

class UsersControllerAPI extends Controller {

//     public $successStatus = 200;

//     public function usersAPI() {
//         $users = User::all();

//         if (count($users) > 0) {
//             return response()->json($users, $this->successStatus);
//         } else {
//             return response()->json(['Error' => 'There is no posts in the database'], 404);
//         }        
//     }
// }

public $successStatus = 200;


    public function login() {

        if (Auth::attempt(['username' => request('username'), 'password' => request('password')])) {
            $users = Auth::user();

            $success['token'] = Str::random(64);
            $success['username'] = $users->username;
            $success['id'] = $users->id;
            $success['name'] = $users->name;

            //SAVE LOGS INTO 
            $logs = new Logs();

            $logs->user_id = $users->id;
            $logs->log = "Login";
            $logs['logdetails'] = "User $users->username has logged in into my system";
            $logs['logtype'] = "API login";
            $logs->save();

            // SAVE TOKEN
            $users->remember_token = $success['token'];
            $users->save();

            return response()->json($success, $this->successStatus);
        } else {
            return response()->json(['response' => 'User not found'], 404);  
        }
    }

    public function register(Request $request) {
        $validators = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validators->fails()) {
            return response()->json(['response' => $validators->errors()], 401);
        } else {
            $input = $request->all();

            if (User::where('email', $input['email'])->exists()) {
                return response()->json(['response' => 'Email already exists'], 401);
            } elseif(User::where('username', $input['username'])->exists()) {
                return response()->json(['response' => 'Username already exists'], 401);
            } else {
                $input['password'] = bcrypt($input['password']);
                $users = User::create($input);

                $success['token'] = Str::random(64);
                $success['username'] = $users->username;
                $success['id'] = $users->id;
                $success['name'] = $users->name;

                return response()->json($success, $this->successStatus);
            }
        }
    }

    public function orderAdd(Request $request) {
        $validators = Validator::make($request->all(), [
            'order_name' => 'required',
            'address' => 'required',
            'product_id' => 'required',
            'quantity_order' => 'required',
        ]);
        if ($validators->fails()) {
            return response()->json(['response' => $validators->errors()], 401);
      
        } else {
            $input = $request->all();
    
        if(Orders::where('order_name', $input['order_name'])->exists()) {
            return response()->json(['response' => 'Product is Invalid'], 401);
        }else{
                $orders = Orders::create($input);
    
                $success['order_name'] = $orders->order_name;
                $success['address'] = $orders->address;
                $success['product_id'] = $orders->product_id;
                $success['quantity_order'] = $orders->quantity_order;
    
                return response()->json($success, $this->successStatus);
            }
        }
    
    }

    public function productAdd(Request $request) {

        $validators = Validator::make($request->all(), [
            'product_name'=> 'required',
            'brandname'=> 'required',
            'description'=> 'required',
            'pricing'=> 'required',
            'discount'=> 'required',
            'stock'=> 'required',
        ]);
        if ($validators->fails()) {
            return response()->json(['response' => $validators->errors()], 401);
      
        } else {
            $input = $request->all();
    
        if(Products::where('product_name', $input['product_name'])->exists()) {
            return response()->json(['response' => 'Product is Invalid'], 401);
        }else{
                $products = Products::create($input);
    
                $success['product_name'] = $products->product_name;
                $success['brandname'] = $products->brandname;
                $success['description'] = $products->description;
                $success['pricing'] = $products->pricing;
                $success['discount'] = $products->discount;
                $success['stock'] = $products->stock;
    
    
                return response()->json($success, $this->successStatus);
            }
        }
    
    }

    public function resetPassword(Request $request) {
        $users = User::where('email', $request['email'])->first();

        if ($users != null) {
            $users->password = bcrypt($request['password']);
            $users->save();

            return response()->json(['response' => 'User has successfully resetted his/her password'], $this->successStatus);
        } else {
            return response()->json(['response' => 'User not found'], 404);
        }
    }
}
?>