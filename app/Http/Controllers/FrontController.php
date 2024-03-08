<?php
namespace App\Http\Controllers;
use App\Models\Product;
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

            //Store previous url in session.
            session(['url.intended' => url()->previous()]);

            return response()->json([
                'status' => false
            ]);
        }
    }




}
