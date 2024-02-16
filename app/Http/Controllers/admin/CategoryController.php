<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request){
        $categories = Category::latest();
        if(!empty($request->get('keyword'))){
            $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
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
            // $category->image = $request->image;
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->created_by = $createBy;
            $category->save();

            // save image here 
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                
            }

            $request->session()->flash('success', 'Category added successfully!');

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

    public function edit(){

    }

    public function update(){

    }

    public function destroy(){

    }
}
