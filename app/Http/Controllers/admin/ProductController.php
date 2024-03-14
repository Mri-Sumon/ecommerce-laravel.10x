<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductRating;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        //with('product_images'): In product model we create product_images() method for create relationship.
        $products = Product::latest('id')->with('product_images');

        if (!empty($request->get('keyword'))) {
            $products->where('title', 'like', '%' . $request->get('keyword') . '%');
        }
        $products = $products->paginate(10);
        $data['products'] = $products;
        return view('admin.products.list', $data);
    }
    

    public function create(){

        $data = [];

        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $data['categories']=$categories;
        $data['brands']=$brands;

        return view('admin.products.create', $data);
    }


    public function store(Request $request){

        $rule = [
            'title' => 'required', 
            'slug' => 'required|unique:products',
            //Integer+Floating = numeric
            'price' => 'required|numeric', 
            'sku' => 'required|unique:products',
            //track_qty is required and its value must be within Yes or No.
            'track_qty' => 'required|in:Yes,No',
            //category is numeric because we will get category as category id.
            'category' => 'required|numeric', 
            'is_featured' => 'required|in:Yes,No',
            'is_top_selling' => 'required|in:Yes,No',
        ];

        //if we tikmark on track_qty, we must be insert qty, 
        //otherwise qty value will be null, thats why qty is optional.
        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        
        $validator = Validator::make($request->all(),$rule);

        if($validator->passes()){
            
            $createBy = Auth::user()->id;

            $product = new Product();
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
            $product->related_products = (!empty($request->related_products)) ? implode(',', $request->related_products) : '';
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->is_featured = $request->is_featured;
            $product->is_top_selling = $request->is_top_selling;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->sort = $request->sort;
            $product->created_by = $createBy;
            $product->save();

            //save product images 
            if(!empty($request->image_array)){

                foreach($request->image_array as $temp_image_id){

                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.',$tempImageInfo->name);
                    $ext = last($extArray);

                    $productImage = new ProductImage();
                    $productImage->product_id =  $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $product->id.'-'.$productImage->id.time().'.'.$ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    
                    // Generate and save large thumbnail
                    $sPath = public_path().'/temp/'.$tempImageInfo->name;
                    $dPath = public_path().'/uploads/product/large/'.$imageName;
                    if($sPath){
                        $manager = new ImageManager(new Driver());
                        $img = $manager->read($sPath);
                        //image maximum width set 1400 because our webside maximum width 1400.
                        $img->resize(1400, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $img->save($dPath);
                    }

                    // Generate and save small thumbnail
                    $dPath = public_path().'/uploads/product/small/'.$imageName;
                    if($sPath){
                        $manager = new ImageManager(new Driver());
                        $img = $manager->read($sPath);
                        //image maximum width set 1400 because our webside maximum width 1400.
                        $img->resize(300, 300, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $img->save($dPath);
                    }

                }
            }

            $request->session()->flash('success', 'Category added successfully');
            return response()->json([
                'status' => true, 
                'message' => 'Category added successfully'
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }
    }


    
    public function edit($productId, Request $request){

        $data = [];

        $product = Product::find($productId);

        if(empty($product)){
            return redirect()->route('products.index')->with('error','Product not found');
        }


        //Fetch Products image
        $productImages = ProductImage::where('product_id', $product->id)->get();


        //Fetch related Products
        $relatedProducts = [];
        if ($product->related_products != '') {
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->with('product_images')->get();
        }


        $categories = Category::orderBy('name','ASC')->get();

        $subCategories = SubCategory::where('category_id', $product->category_id)->get();

        $brands = Brand::orderBy('name','ASC')->get();

        $data['product']=$product;
        $data['productImages']=$productImages;
        $data['categories']=$categories;
        $data['subCategories']=$subCategories;
        $data['brands']=$brands;
        $data['relatedProducts']=$relatedProducts;

        if(empty($product)){
            return redirect()->route('products.index');
        }

        return view('admin.products.edit',$data);
    }




    public function update($productId, Request $request){

        $product = Product::find($productId);

        if(empty($product)){

            $request->session()->flash('error', 'Record not found');
            
            return response()->json([
                "status" => false,
                "notFound" => true,
                "message" => "Record not found"
            ]);
        }

        $rule = [
            'title' => 'required', 
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'price' => 'required|numeric', 
            'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric', 
            'is_featured' => 'required|in:Yes,No',
            'is_top_selling' => 'required|in:Yes,No',
        ];        

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        
        $validator = Validator::make($request->all(),$rule);

        if($validator->passes()){
            
            $updatedBy = Auth::user()->id;

            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->related_products = (!empty($request->related_products)) ? implode(',', $request->related_products) : '';
            $product->shipping_returns = $request->shipping_returns;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->is_featured = $request->is_featured;
            $product->is_top_selling = $request->is_top_selling;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->sort = $request->sort;
            $product->updated_by = $updatedBy;
            $product->save();


            $request->session()->flash('success', 'Product updated successfully');
            return response()->json([
                'status' => true, 
                'message' => 'Product updated successfully'
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }

    }

    public function destroy($productId, Request $request){

        $product = Product::find($productId);

        if(empty($product)){
            $request->session()->flash('error', 'Product not found');
            return response()->json([
                'status' => true,
                'message' => 'Record not found'
            ]);
        }

        $productImages = ProductImage::where('product_id', $productId)->get();

        if(!empty($productImages)){
            foreach($productImages as $productImage){
                //delete image from laravel project public folder when delete category 
                File::delete(public_path().'/uploads/product/small/'.$productImage->image);
                File::delete(public_path().'/uploads/product/large/'.$productImage->image);
            }
            //After complete foreach loop, this line be exicude
            ProductImage::where('product_id', $productId)->delete();
        }

        $product->delete();

        $request->session()->flash('success', 'Product deleted successfully');
        
        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
        
    }


    public function getProducts(Request $request){

        $tempProduct = [];

        if ($request->term != '') {
            $products = Product::where('title','like','%'.$request->term.'%')->get();

            if ($products != null) {
                foreach ($products as $product) {
                    $tempProduct[] = array('id' => $product->id, 'text' => $product->title);
                }
            }
        }
        return response()->json([
            'tags' => $tempProduct,
            'status' => true
        ]);
    }



    public function productRatings(Request $request){

        $ratings = ProductRating::select('product_ratings.*', 'products.title as productTitle')
        ->orderBy('product_ratings.created_at', 'DESC');
        $ratings = $ratings->leftJoin('products', 'products.id', '=', 'product_ratings.product_id');

        if (!empty($request->get('keyword'))) {
            $ratings->orWhere('title', 'like', '%' . $request->get('keyword') . '%');
            $ratings->orWhere('product_ratings.username', 'like', '%' . $request->get('keyword') . '%');
        }

        $ratings =  $ratings->paginate(10);

        return view('admin.products.ratings',[
            'ratings' => $ratings,
        ]);
    }



    public function changeRatingStatus(Request $request){

        $productRating = ProductRating::find($request->productId); 
        $productRating->status = $request->status; 
        $productRating->save();
    
        session()->flash('success', 'Status changed successfully.');
    
        return response()->json([
            'status' => true,
        ]);

    }
    
    //Delete product rating
    public function deleteRating($ratingId, Request $request){

        $rating = ProductRating::find($ratingId);

        if(empty($rating)){
            $request->session()->flash('error', 'Record not found');
            return response()->json([
                'status' => true,
                'message' => 'Record not found'
            ]);
        }

        $rating->delete();

        $request->session()->flash('success', 'Rating deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Rating deleted successfully'
        ]);
        
    }





} 



















