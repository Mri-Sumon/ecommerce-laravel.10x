<?php
namespace App\Http\Controllers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug="NULL", $subCategorySlug="NULL"){

        //when press any category or subcategory, that will show as active or stay open the dropdown.
        $categorySelected = '';
        $subCategorySelected = '';
        


        $categories=Category::orderBy('sort', 'ASC')
        //for relation create, sub_category function declare in category model.
        ->with(['sub_category' => function ($query){
            $query->orderBy('sort', 'ASC'); 
        }])->where('status', 1)->get();

        $brands=Brand::orderBy('sort', 'ASC')->where('status', 1)->get();

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



        // Apply brand Filter, when press any brand , that will show as tik mark.
        $brandArray = [];
        if(!empty($request->get('brand'))){
            $brandArray = explode(',',$request->get('brand'));
            //Get products through brand_id.
            $products = $products->whereIn('brand_id',$brandArray);
        }



        // Apply Range filters here
        if($request->get('price_max') != '' && $request->get('price_min') != ''){
            if($request->get('price_max') == 10000){
                $products = $products->whereBetween('price', [intval($request->get('price_min')), 1000000]);
            }else{
                $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
            }
        }



        //Apply searching 
        if(!empty($request->get('search'))){
            $products = $products->where('title','like','%'.$request->get('search').'%');
        }



        //Apply sort filter here
        if($request->get('sort') != ''){
            if($request->get('sort') == 'latest'){
                $products = $products->orderBy('id','DESC');
            }elseif($request->get('sort') == 'price_asc'){
                $products = $products->orderBy('price','asc');
            }else{
                $products = $products->orderBy('price','desc');
            }
        }else{
            $products = $products->orderBy('id','DESC');
        }




        $products = $products->where('status', 1)->paginate(9);

        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;
        $data['brandArray'] = $brandArray;
        $data['priceMin'] = intval($request->get('price_min'));
        $data['priceMax'] = (intval($request->get('price_max')) == 0) ? 10000 : intval($request->get('price_max'));        
        $data['sort'] = $request->get('sort');

        return view("front.shop",$data);

    }


    //Function created for related product
    public function product($slug){
        
        $product=Product::where('slug', $slug)
        //Count specific product rating by the helping of product id
        ->withCount('product_ratings')
        //Sum of specific product by the helping of product id
        ->withSum('product_ratings','rating')
        ->with('product_images','product_ratings')
        ->first();

        if($product == NULL){
            abort(404);
        }


        //Fetch related Products
        $relatedProducts = [];
        if ($product->related_products != '') {
            $productArray = explode(',', $product->related_products);
            //with('product_images'): In product model we create product_images() method for create relationship.
            $relatedProducts = Product::whereIn('id', $productArray)->with('product_images')->where('status', 1)->get();
        }


        $data['product'] = $product;
        $data['relatedProducts'] = $relatedProducts;


        //Calculate average rating
        //আমরা $product কে dd()  করলে নিচের কলাম দুটি এ্যারেতে পাবো।
        // "product_ratings_count" => 0
        // "product_ratings_sum_rating" => null
        $averageRating = 0.00;
        $averageRatingPercent = 0;

        if($product->product_ratings_count>0){
            $averageRating = number_format(($product->product_ratings_sum_rating/$product->product_ratings_count),2);
            $averageRatingPercent = ($averageRating*100)/5;
        }

        $data['averageRating'] = $averageRating;
        $data['averageRatingPercent'] = $averageRatingPercent;

        return view("front.product",$data);
    }



    public function saveRating($productId, Request $request){

        $validator = Validator::make($request->all(),[
            'username' => 'required|min:5',
            'email' => 'required|email',
            'comment' => 'required|min:10', 
            'rating' => 'required',
        ]);

        if($validator->fails()){
            
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);

        }else{

            //If customer already rated the product, the customer can't again rated the product. 
            $count = ProductRating::where('email', $request->email)->count();
            if($count>0){
                $request->session()->flash('error', 'You already rated this product.');
                return response()->json([
                    'status' => true, 
                ]);
            }

            $productRating = new ProductRating();
            $productRating->product_id = $productId;
            $productRating->username = $request->username;
            $productRating->email = $request->email;
            $productRating->comment = $request->comment;
            $productRating->rating = $request->rating;
            $productRating->status = 0;
            $productRating->save();

            $request->session()->flash('success', 'Thanks for your rating.');
            return response()->json([
                'status' => true, 
                'message' => 'Thanks for your rating.'
            ]);
        }

    }





}












