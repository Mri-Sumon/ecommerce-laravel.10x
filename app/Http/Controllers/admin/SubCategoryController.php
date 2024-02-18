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

            $request->session()->flash('success', 'Sub Category creaded successfully');
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

    public function edit($subCategoryId, Request $request){

        $categories = Category::orderBy('id', 'asc')->get();
        $data['categories']=$categories;
   
        $subCategory = SubCategory::find($subCategoryId);
        if(empty($subCategory)){
            $request->session()->flash('error','Record not found');
            return redirect()->route('sub-categories.index');
        }
        $data['subCategory'] = $subCategory;

        return view('admin.sub_category.edit', $data);

    }

    public function update($subCategoryId, Request $request){

        $subCategory = SubCategory::find($subCategoryId);

        if(empty($subCategory)){
            $request->session()->flash('error', 'Record not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Record not found'
            ]);
        }
        
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            //যদি কোনো slug আগে থেকেই sub_categories টেবিলের slug কলামে থাকে, তাহলে একই নামে 2য় কোনো slug ইনসার্ট নিবে না।
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            'category_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            
            $updatedBy = Auth::user()->id;

            $subCategory->category_id = $request->category_id;
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->updated_by = $updatedBy;
            $subCategory->save();

            $request->session()->flash('success', 'Sub Category updated successfully');
            return response()->json([
                'status' => true, 
                'message' => 'Sub Category updated successfully'
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function destroy($subCategoryId, Request $request){

        $subCategory = SubCategory::find($subCategoryId);
        if(empty($subCategory)){
            $request->session()->flash('error', 'Record not found');
            return response()->json([
                'status' => true,
                'message' => 'Record not found'
            ]);
        }

        $subCategory->delete();

        $request->session()->flash('success', 'Sub Category deleted successfully');
        
        return response()->json([
            'status' => true,
            'message' => 'Sub Category deleted successfully'
        ]);
        
    }
}
