<?php
namespace App\Http\Controllers;

use App\Models\Page;
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
            session(['url.intended' => url()->previous()]);
            return response()->json([
                'status' => false
            ]);
        }
    
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
    
        $product = Product::where('id', $request->id)->first();
        if($product == NULL){
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Product not found</div>',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"'.$product->title.'"</strong> has been successfully added to your wish list</div>',
        ]);

    }
    


    public function page($slug){
        
        $page = Page::where('slug', $slug)->first();

        if($page == NULL){
            abort(404);
        }

        return view('front.page', [
            'page' => $page
        ]);

    }




}


















