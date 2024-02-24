<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request) {

        //productId comes through ajax.
        //with('product_images'): In product model we create product_images() method for create relationship.
        $product = Product::with('product_images')->find($request->productId);

        //if product dose't exist in database
        if($product == null){
            return response()->json([
                'status' => false,
                'message' => 'Product not found',
            ]);
        }

        //if product already added in cart
        if(Cart::count() > 0){
            echo 'Product already in cart';
        } else {
            //if cart is empty, add product to the cart.

            echo 'Cart is empty, now adding product in cart';
            
            Cart::add($product->id, $product->title, 1, $product->price, 
                  ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : ''],
            );
        }
        
    }    


    public function cart(){
        return view('front.cart'); 
    }



}
