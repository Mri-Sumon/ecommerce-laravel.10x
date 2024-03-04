<?php
namespace App\Http\Controllers;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCharge;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

        $discount = 0;

        //If cart empty.
        if(Cart::count() == 0){
            return redirect()->route('front.cart');
        }
        
        //If unauthorized user try to checkout.
        if(Auth::check() == false){
            //Store checkout page url in session.
            if(!session()->has('url.intended')){
                session(['url.intended' => url()->current()]);
            }
            return redirect()->route('account.login');
        }

        //Get authenticate user address.
        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();

        //Remove checkout page url.
        session()->forget('url.intended');

        //Get countries from country table.
        $countries = Country::orderBy('name', 'ASC')->get();


        // Apply discount here
        $subTotal = Cart::subtotal(2, '.', '');
        $discount = 0;

        if (session()->has('code')) {

            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount/100)*$subTotal;
            }else{
                $discount = $code->discount_amount;
            }
        }


        // Calculate shipping here
        if ($customerAddress !== null) {

            $userCountry = $customerAddress->country_id;
            $shippingInfo = ShippingCharge::where('country_id', $userCountry)->first();

            // Check if $shippingInfo is not null before accessing its properties
            if ($shippingInfo !== null) {
                $totalQty = 0;
                $totalShippingCharge = 0;
                $grandTotal = 0;

                foreach (Cart::content() as $item) {
                    $totalQty += $item->qty;
                }

                $totalShippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal-$discount) + $totalShippingCharge;
            } else {
                $totalShippingCharge = 0; 
                $grandTotal = ($subTotal-$discount); 
            }

        } else {
            $totalShippingCharge = 0; 
            $grandTotal = ($subTotal-$discount);
        }


        return view('front.checkout', [
            'countries' => $countries,
            'customerAddress' => $customerAddress,
            'totalShippingCharge' => $totalShippingCharge,
            'discount' => $discount,
            'grandTotal' => $grandTotal
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
            // $grandTotal = ($subTotal-$discount)+$shipping;

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




    public function getOrderSummery(Request $request){

        $subTotal = Cart::subtotal(2, '.', '');


        //Apply discount in checkout page order summary
        $discount = 0;
        $discountString = '';
        if (session()->has('code')) {
            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount/100)*$subTotal;
            }else{
                $discount = $code->discount_amount;
            }

            $discountString = '<div class="mt-4" id="discount_response">
                <strong> '.session()->get('code')->code.' </strong>
                <a class="btn btn-sm btn-danger" id="remove_discount"><i class="fa fa-times"></i></a>
            </div>';
        }

        


        //Shipping charge manage here
        if ($request->country_id > 0) {
            $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {

                $shippingCharge = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shippingCharge;


                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 2),
                    'discount' => $discount,
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge, 2)
                ]);
            }else{

                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();
                $shippingCharge = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format(($subTotal-$discount), 2),
                    'discount' => $discount,
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge, 2),
                ]);
            }
        }else{
            return response()->json([
                'status' => true,
                'grandTotal' => number_format($subTotal, 2),
                'discount' => $discount,
                'discountString' => $discountString,
                'shippingCharge' => number_format(0, 2),
            ]);
        }
    }




    public function applyDiscount(Request $request){

        //Get Discountcoupon from databse
        $code = DiscountCoupon::where('code', $request->code)->first();

        if ($code == null) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Discount code'
            ]);
        }

        //Check coupon start date valid or not
        $now = Carbon::now();
        if ($code->starts_at != "") {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);
            //If the current date is less than the start date of the coupon, it returns a JSON response indicating that the discount coupon is invalid.
            if ($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Discount coupon1'
                ]);
            }
        }

        
        if ($code->expires_at != "") {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);
            //If the current date is greater than the expiration date of the coupon, it returns a JSON response indicating that the discount coupon is invalid.
            if ($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Discount coupon2'
                ]);
            }
        }

        // // Max uses check
        // if ($code->max_uses > 0) {
        //     $couponUsed = Order::where('coupon_code_id', $code->id)->count();
        //     if ($couponUsed >= $code->max_uses) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'Over maximum limit'
        //         ]);
        //     }
        // }

        // // Max uses user check
        // if ($code->max_uses_user > 0) {
        //     $couponUsedByUser = Order::where(['coupon_code_id' => $code->id, 'user_id' => Auth::user()->id])->count();
        //     if ($couponUsedByUser >= $code->max_uses_user) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'You already used this coupon'
        //         ]);
        //     }
        // }

        // $subTotal = Cart::subtotal(2, '.', '');
        // // Min amount condition check
        // if ($code->min_amount > 0) {
        //     if ($subTotal < $code->min_amount) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'Your min amount must be $'.$code->min_amount.'.'
        //         ]);
        //     }
        // }

        session()->put('code', $code);
        return $this->getOrderSummery($request);

    }




    public function removeCoupon(Request $request){
        session()->forget('code');
        return $this->getOrderSummery($request);
    }




}

































