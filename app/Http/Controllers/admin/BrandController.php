<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::latest('id');

        if (!empty($request->get('keyword'))) {
            $brands->where('brands.name', 'like', '%' . $request->get('keyword') . '%');
        }
    
        $brands = $brands->paginate(10);
        return view('admin.brands.list', compact('brands'));
    }
    

    public function create(){
        $categories = Category::orderBy('id', 'asc')->get();
        $data['categories']=$categories;
        return view('admin.brands.create', $data);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required', 
            'slug' => 'required|unique:brands',
            'status' => 'required', 
        ]);

        if($validator->passes()){
            
            $createBy = Auth::user()->id;

            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->sort = $request->sort;
            $brand->created_by = $createBy;
            $brand->save();

            $request->session()->flash('success', 'Brands creaded successfully');
            return response()->json([
                'status' => true, 
                'message' => 'Brands created successfully'
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }

    }

    public function edit($brandId, Request $request){
  
        $brand = Brand::find($brandId);
        if(empty($brand)){
            $request->session()->flash('error','Record not found');
            return redirect()->route('brands.index');
        }
        $data['brand'] = $brand;
        return view('admin.brands.edit', $data);

    }

    public function update($brandId, Request $request){

        $brand = Brand::find($brandId);

        if(empty($brand)){
            $request->session()->flash('error', 'Record not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Record not found'
            ]);
        }
        
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            //যদি কোনো slug আগে থেকেই brands টেবিলের slug কলামে থাকে, তাহলে একই নামে 2য় কোনো slug ইনসার্ট নিবে না।
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',
            'status' => 'required',
        ]);

        if($validator->passes()){
            
            $updatedBy = Auth::user()->id;

            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->sort = $request->sort;
            $brand->updated_by = $updatedBy;
            $brand->save();

            $request->session()->flash('success', 'Brand updated successfully');
            return response()->json([
                'status' => true, 
                'message' => 'Brand updated successfully'
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function destroy($brandId, Request $request){

        $brand = Brand::find($brandId);
        if(empty($brand)){
            $request->session()->flash('error', 'Record not found');
            return response()->json([
                'status' => true,
                'message' => 'Record not found'
            ]);
        }

        $brand->delete();

        $request->session()->flash('success', 'Brand deleted successfully');
        
        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully'
        ]);
        
    }
}
 