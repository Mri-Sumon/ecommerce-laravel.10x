<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\TempImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $subCategories = SubCategory::latest('id')
            ->leftJoin('categories', 'categories.id', '=', 'sub_categories.category_id')
            ->select('sub_categories.*', 'categories.name as categoryName');
    
        if (!empty($request->get('keyword'))) {
            $subCategories->where('sub_categories.name', 'like', '%' . $request->get('keyword') . '%');
            $subCategories->orWhere('categories.name', 'like', '%' . $request->get('keyword') . '%');
        }
    
        $subCategories = $subCategories->paginate(10);
        return view('admin.sub_category.list', compact('subCategories'));
    }
    

    public function create(){
        $categories = Category::orderBy('id', 'asc')->get();
        $data['categories']=$categories;
        return view('admin.sub_category.create', $data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required', 
            'slug' => 'required|unique:sub_categories',
            'category_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            
            $createBy = Auth::user()->id;

            $subCategory = new SubCategory();
            $subCategory->category_id = $request->category_id;
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->created_by = $createBy;
            $subCategory->save();

            $request->session()->flash('success', 'Sub Category creaded successfully!');
            return response()->json([
                'status' => true, 
                'message' => 'Sub Category created successfully'
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }

    }

    public function edit($categoryId, Request $request){
        $category = Category::find($categoryId);
        if(empty($category)){
            return redirect()->route('categories.index');
        }
        return view('admin.sub_category.edit',compact('category'));
    }

    public function update($categoryId, Request $request){

        $category = Category::find($categoryId);

        if(empty($category)){

            $request->session()->flash('error', 'Category not found');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }
        
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);

        if($validator->passes()){

            $updatedBy = Auth::user()->id;

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->updated_by = $updatedBy;
            $category->save();

            //remove old image when update category from laravel project public folder
            $oldImage = $category->image;


            // save image here 
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath, $dPath);
                $category->image = $newImageName;
                $category->save();

                $dPath=public_path().'/uploads/category/thumb/'.$newImageName;
                if($sPath){
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read($sPath);
                    // image resize ratio wise
                    $img->resize(450, 600, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->save($dPath);
                }

                //delete old image from laravel project public folder when update category 
                File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
                File::delete(public_path().'/uploads/category/'.$oldImage);
            }

            $request->session()->flash('success', 'Category updated successfully!');

            return response()->json([
                'status' => true, 
                'message' => 'Category updated successfully'
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function destroy($categoryId, Request $request){

        $category = Category::find($categoryId);

        if(empty($category)){

            $request->session()->flash('error', 'Category not found');

            return response()->json([
                'status' => true,
                'message' => 'Category not found'
            ]);
        }

        //delete image from laravel project public folder when delete category 
        File::delete(public_path().'/uploads/category/thumb/'.$category->image);
        File::delete(public_path().'/uploads/category/'.$category->image);

        $category->delete();

        $request->session()->flash('success', 'Category deleted successfully!');
        
        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
        
    }
}
