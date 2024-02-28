<?php
namespace App\Http\Controllers;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCharge;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request) {

        //productId comes through ajax. //with('product_images'): In product model we create product_images() method for create relationship.
        $product = Product::with('product_images')->find($request->productId);

        //If product dose't exist in database
        if($product == null){
            return response()->json([
                'status' => false,
                'message' => 'Product not found', 
            ]);
        }

        //If any product already added in cart.
        if(Cart::count() > 0){

            //Get all the added item from cart.
            $cartContent = Cart::content();
            $productAlreadyExist = false;

            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                    break;
                }
            }

            if($productAlreadyExist == false){
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : ''],);
                $status = true;
                $message = '<strong>' . $product->title . '</strong> added in your cart successfully';
                session()->flash('success', $message);
            }else{
                $status = false;
                $message = 'This product already added in cart';
            }

        //If cart is totally empty, add product to the cart. 
        } else {
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : ''],);
            $status = true;
            $message = '<strong>' . $product->title . '</strong> added in your cart successfully';
            session()->flash('success', $message);
        }
        
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);

    }    

    public function cart(){
        //Get all the added item from cart.
        $cartContent = Cart::content();
        $data['cartContent'] = $cartContent;
        return view('front.cart', $data); 
    }

    
    public function updateCart(Request $request){
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);
        $product = Product::find($itemInfo->id);

        if ($product->track_qty == 'Yes') {

            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = 'Cart updated successfully';
                $status = true;
                session()->flash('success', $message);
            }else{
                $message = 'Requested Qty('.$qty.') not available in stock.';
                $status = false;
                session()->flash('error', $message);
            }
        }else{
            Cart::update($rowId, $qty);
            $message = 'Cart updated successfully';
            $status = true;
            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    
    public function deleteItem(Request $request){

        $itemInfo = Cart::get($request->rowId);

        if ($itemInfo == null) {
            $errorMessage = 'Item not found in cart';
            session()->flash('error', $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        Cart::remove($request->rowId);

        $message = 'Item removed successfully';
        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    

    public function checkout(){

        if(Cart::count() == 0){
            return redirect()->route('front.cart');
        }
        
        if(Auth::check() == false){
            if(!session()->has('url.intended')){
                session(['url.intended' => url()->current()]);
            }

            return redirect()->route('account.login');

        }

        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();

        session()->forget('url.intended');

        $countries = Country::orderBy('name', 'ASC')->get();

        return view('front.checkout', [
            'countries' => $countries,
            'customerAddress' => $customerAddress,
        ]);        

    }



    public function processCheckout(Request $request){

        //step-1 Apply validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:3',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required|min:20',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'message' => 'Please fill the required feild',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }






        //step-2 Store Customer Address
        $user = Auth::user();
        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'country_id' => $request->country,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip
            ]
        );






        //step-3: Store order-data in orders table
        if ($request->payment_method == 'cod') {

            // $discountCodeId = NULL;
            // $promoCode = '';
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2,'.','');
            $grandTotal = ($subTotal-$discount)+$shipping;

            // Apply discount here
            // if (session()->has('code')) {
            //     $code = session()->get('code');

            //     if ($code->type == 'percent') {
            //         $discount = ($code->discount_amount/100)*$subTotal;
            //     }else{
            //         $discount = $code->discount_amount;
            //     }

            //     $discountCodeId = $code->id;
            //     $promoCode = $code->code;
            // }

            //Calculate Shipping
            // $shippingInfo = ShippingCharge::where('country_id', $request->country)->first();

            // $totalQty = 0;
            // foreach (Cart::content() as $item) {
            //     $totalQty += $item->qty;
            // }

            // if ($shippingInfo != null) {

            //     $shipping = $totalQty*$shippingInfo->amount;
            //     $grandTotal = ($subTotal-$discount)+$shipping;

            // }else{

            //     $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();

            //     $shipping = $totalQty*$shippingInfo->amount;
            //     $grandTotal = ($subTotal-$discount)+$shipping;
            // }

            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grant_total = $grandTotal;
            $order->discount = $discount;
            // $order->coupon_code_id = $discountCodeId;
            // $order->coupon_code = $promoCode;
            // $order->payment_status = 'not paid';
            // $order->status = 'pending';
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->country_id = $request->country;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            $order->city = $request->city;
            $order->state = $request->state;
            $order->zip = $request->zip;
            $order->notes = $request->order_notes;
            // $order->account_name = $request->account_name;
            // $order->account_number = $request->account_number;
            // $order->trax_id = $request->trax_id;
            $order->save();






            //step-4 Store order items in order_items table
            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem;
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price*$item->qty;
                $orderItem->save();
            }

            // Send ordr email
            // orderEmail($order->id, 'customer');

            //Successful message
            session()->flash('success', 'You have placed your order successfully');

            //After submitting all data, session card will be empty
            Cart::destroy();

            // session()->forget('code');

            return response()->json([
                'message' => 'Order saved successfully',
                'orderId' => $order->id,
                'status' => true
            ]);

        }else{

        }







        if ($request->payment_method == 'bkash') {
            $discountCodeId = NULL;
            $promoCode = '';
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2,'.','');

            // Apply discount here
            if (session()->has('code')) {
                $code = session()->get('code');

                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount/100)*$subTotal;
                }else{
                    $discount = $code->discount_amount;
                }

                $discountCodeId = $code->id;
                $promoCode = $code->code;
            }

            //Calculate Shipping
            $shippingInfo = ShippingCharge::where('country_id', $request->country)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {

                $shipping = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shipping;

            }else{

                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();

                $shipping = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shipping;
            }

            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grant_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;
            $order->payment_status = 'not paid';
            $order->status = 'pending';
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->country_id = $request->country;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            $order->city = $request->city;
            $order->state = $request->state;
            $order->zip = $request->zip;
            $order->notes = $request->order_notes;
            $order->account_name = $request->account_name;
            $order->account_number = $request->account_number;
            $order->trax_id = $request->trax_id;
            $order->save();

            // step-4 Save order items in order_items table
            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem;
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price*$item->qty;
                $orderItem->save();
            }

            // Send ordr email
            // orderEmail($order->id, 'customer');

            // session()->flash('success', 'You have placed your order successfully');
            // Cart::destroy();

            // session()->forget('code');

            // return response()->json([
            //     'message' => 'Order saved successfully',
            //     'orderId' => $order->id,
            //     'status' => true
            // ]);
        }
    }


    public function thankYou($id){
        return view('front.thanks', [
            'id' => $id
        ]);
    }

}

































