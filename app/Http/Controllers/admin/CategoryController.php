<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest('id');
    
        if (!empty($request->get('keyword'))) {
            $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
    
        $categories = $categories->paginate(10);
    
        return view('admin.category.list', compact('categories'));
    }
    

    public function create(){
        return view('admin.category.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required', 
            'slug' => 'required|unique:categories', 
        ]);

        if($validator->passes()){
            
            $createBy = Auth::user()->id;

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->sort = $request->sort;
            $category->created_by = $createBy;
            $category->save();

            // save image here 
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);
                $newImageName = $category->id.'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath, $dPath);
                $category->image = $newImageName;
                $category->save();

                $dPath=public_path().'/uploads/category/thumb/'.$newImageName;
                if($sPath){
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read($sPath);
                    // $img = $img->resize(450, 600);
                    // image resize ratio wise
                    $img->resize(450, 600, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->save($dPath);
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

    public function edit($categoryId, Request $request){
        $category = Category::find($categoryId);
        if(empty($category)){
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit',compact('category'));
    }

    public function update($categoryId, Request $request){

        $category = Category::find($categoryId);

        if(empty($category)){
            $request->session()->flash('error', 'Record not found');
            return response()->json([
                "status" => false,
                "notFound" => true,
                "message" => "Record not found"
            ]);
        }
        
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            //যদি কোনো slug আগে থেকেই categories টেবিলের slug কলামে থাকে, তাহলে একই নামে 2য় কোনো slug ইনসার্ট নিবে না।
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);

        if($validator->passes()){

            $updatedBy = Auth::user()->id;

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->sort = $request->sort;
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
                    // $img = $img->resize(450, 600);
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

            $request->session()->flash('success', 'Category updated successfully');

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

            $request->session()->flash('error', 'Record not found');

            return response()->json([
                'status' => true,
                'message' => 'Record not found'
            ]);
        }

        //delete image from laravel project public folder when delete category 
        File::delete(public_path().'/uploads/category/thumb/'.$category->image);
        File::delete(public_path().'/uploads/category/'.$category->image);

        $category->delete();

        $request->session()->flash('success', 'Category deleted successfully');
        
        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
        
    }
}
