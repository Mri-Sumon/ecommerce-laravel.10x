<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{

    public function index(){

        $featuredProduct = Product::orderBy('sort', 'ASC')->where('is_featured','Yes')->where('status', 1)->take(8)->get();
        $topSellingProduct = Product::orderBy('sort', 'ASC')->where('is_top_selling','Yes')->where('status', 1)->take(8)->get();
        $latestProduct = Product::orderBy('id', 'DESC')->where('status', 1)->take(8)->get();

        $data['featuredProduct']=$featuredProduct;
        $data['topSellingProduct']=$topSellingProduct;
        $data['latestProduct']=$latestProduct;

        return view("front.home", $data);

    }



    public function addToWishlist(Request $request){

        if(Auth::check() ==  false){
            // If the user is not authenticated, return a JSON response with status false
            // and stop further execution.
            session(['url.intended' => url()->previous()]);
            return response()->json([
                'status' => false
            ]);
        }
    
        //Store or update data, If data already exist in database table it will update the product.
        Wishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ],
            
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ]
        );
    
        //If comming id's product not exit in product table, this will show product not found.
        $product = Product::where('id', $request->id)->first();
        if($product == NULL){
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Product not found</div>',
            ]);
        }

        // Return a JSON response indicating success.
        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"'.$product->title.'"</strong> has been successfully added to your wish list</div>',
        ]);

    }
    


}
