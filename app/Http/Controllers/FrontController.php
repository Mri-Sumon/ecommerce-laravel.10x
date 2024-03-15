<?php
namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class FrontController extends Controller
{

    public function index(){

        //when press any category or subcategory, that will show as active or stay open the dropdown.
        $categorySelected = '';
        $subCategorySelected = '';
        
        $categories=Category::orderBy('sort', 'ASC')
        //for relation create, sub_category function declare in category model.
        ->with(['sub_category' => function ($query){
            $query->orderBy('sort', 'ASC'); 
        }])->where('status', 1)->get();

        //Get all data
        $products = Product::orderBy('sort','DESC');

        //Apply Category filters here
        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            if($category){
                $products = $products->where('category_id', $category->id);
                $categorySelected = $category->id;
            }
        }

        //Apply SunCategory filters here
        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            if($subCategory){
                $products = $products->where('sub_category_id', $subCategory->id);
                $subCategorySelected = $subCategory->id;
            }
        }
        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;


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


    public function sendContactEmail(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required', 
            'email' => 'required|email',
            'subject' => 'required|min:10',
        ]);

        if($validator->passes()){
            
            //Send email here
            $mailData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'mail_subject' => 'You have received a contact email',
            ];

            $admin = User::where('id',1)->first();

            Mail::to($admin->email)->send(new ContactEmail($mailData));

            $request->session()->flash('success', 'Thanks for contacting us, we will get back to you soon');
            return response()->json([
                'status' => true, 
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }
    
    }


}


















