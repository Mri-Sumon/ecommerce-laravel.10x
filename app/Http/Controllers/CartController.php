<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

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
                $message = $product->title.'Product added in cart';
            }else{
                $status = false;
                $message = $product->title.'Already added in cart';
            }


        //If cart is totally empty, add product to the cart. 
        } else {
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : ''],);
            $status = true;
            $message = $product->title.' added in cart';
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





    
    

}
