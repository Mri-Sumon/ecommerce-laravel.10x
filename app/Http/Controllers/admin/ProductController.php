<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
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
        $categories = Category::latest('id');
    
        if (!empty($request->get('keyword'))) {
            $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
    
        $categories = $categories->paginate(10);
    
        return view('admin.products.list', compact('categories'));
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

    // public function edit($categoryId, Request $request){
    //     $category = Category::find($categoryId);
    //     if(empty($category)){
    //         return redirect()->route('categories.index');
    //     }
    //     return view('admin.category.edit',compact('category'));
    // }

    // public function update($categoryId, Request $request){

    //     $category = Category::find($categoryId);

    //     if(empty($category)){
    //         $request->session()->flash('error', 'Record not found');
    //         return response()->json([
    //             "status" => false,
    //             "notFound" => true,
    //             "message" => "Record not found"
    //         ]);
    //     }
        
    //     $validator = Validator::make($request->all(),[
    //         'name' => 'required',
    //         //যদি কোনো slug আগে থেকেই categories টেবিলের slug কলামে থাকে, তাহলে একই নামে 2য় কোনো slug ইনসার্ট নিবে না।
    //         'slug' => 'required|unique:categories,slug,'.$category->id.',id',
    //     ]);

    //     if($validator->passes()){

    //         $updatedBy = Auth::user()->id;

    //         $category->name = $request->name;
    //         $category->slug = $request->slug;
    //         $category->status = $request->status;
    //         $category->sort = $request->sort;
    //         $category->updated_by = $updatedBy;
    //         $category->save();

    //         //remove old image when update category from laravel project public folder
    //         $oldImage = $category->image;


    //         // save image here 
    //         if(!empty($request->image_id)){
    //             $tempImage = TempImage::find($request->image_id);
    //             $extArray = explode('.',$tempImage->name);
    //             $ext = last($extArray);

    //             $newImageName = $category->id.'-'.time().'.'.$ext;
    //             $sPath = public_path().'/temp/'.$tempImage->name;
    //             $dPath = public_path().'/uploads/category/'.$newImageName;
    //             File::copy($sPath, $dPath);
    //             $category->image = $newImageName;
    //             $category->save();

    //             $dPath=public_path().'/uploads/category/thumb/'.$newImageName;
    //             if($sPath){
    //                 $manager = new ImageManager(new Driver());
    //                 $img = $manager->read($sPath);
    //                 // $img = $img->resize(450, 600);
    //                 // image resize ratio wise
    //                 $img->resize(450, 600, function ($constraint) {
    //                     $constraint->aspectRatio();
    //                 });
    //                 $img->save($dPath);
    //             }

    //             //delete old image from laravel project public folder when update category 
    //             File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
    //             File::delete(public_path().'/uploads/category/'.$oldImage);
    //         }

    //         $request->session()->flash('success', 'Category updated successfully');

    //         return response()->json([
    //             'status' => true, 
    //             'message' => 'Category updated successfully'
    //         ]);

    //     }else{
    //         return response()->json([
    //             'status' => false, 
    //             'errors' => $validator->errors(),
    //         ]);
    //     }
    // }

    // public function destroy($categoryId, Request $request){

    //     $category = Category::find($categoryId);

    //     if(empty($category)){

    //         $request->session()->flash('error', 'Record not found');

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Record not found'
    //         ]);
    //     }

    //     //delete image from laravel project public folder when delete category 
    //     File::delete(public_path().'/uploads/category/thumb/'.$category->image);
    //     File::delete(public_path().'/uploads/category/'.$category->image);

    //     $category->delete();

    //     $request->session()->flash('success', 'Category deleted successfully');
        
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Category deleted successfully'
    //     ]);
        
    // }
} 
