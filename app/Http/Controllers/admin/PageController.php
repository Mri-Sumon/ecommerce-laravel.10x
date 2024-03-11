<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::latest('id');

        if (!empty($request->get('keyword'))) {
            $pages->where('pages.name', 'like', '%' . $request->get('keyword') . '%');
        }
    
        $pages = $pages->paginate(10);

        return view('admin.pages.list', compact('pages'));
    }
    


    public function create(){
        return view('admin.pages.create');
    }



    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required', 
            'slug' => 'required|unique:brands',
            'status' => 'required', 
        ]);

        if($validator->passes()){

            $createBy = Auth::user()->id;

            $page = new Page();
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->status = $request->status;
            $page->sort = $request->sort;
            $page->created_by = $createBy;
            $page->save();

            $request->session()->flash('success', 'Page creaded successfully');
            return response()->json([
                'status' => true, 
                'message' => 'Page created successfully'
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }

    }



    public function edit($pageId, Request $request){
  
        $page = Page::find($pageId);

        if(empty($page)){
            $request->session()->flash('error','Record not found');
            return redirect()->route('pages.index');
        }

        $data['page'] = $page;
        return view('admin.pages.edit', $data);

    }




    public function update($pageId, Request $request){

        $page = Page::find($pageId);

        if(empty($page)){
            $request->session()->flash('error', 'Record not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Record not found'
            ]);
        }
        
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:pages,slug,'.$page->id.',id',
            'status' => 'required',
        ]);

        if($validator->passes()){
            
            $updatedBy = Auth::user()->id;

            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->status = $request->status;
            $page->sort = $request->sort;
            $page->updated_by = $updatedBy;
            $page->save();

            $request->session()->flash('success', 'Page updated successfully');
            return response()->json([
                'status' => true, 
                'message' => 'Page updated successfully'
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }

    }





    public function destroy($pageId, Request $request){

        $page = Page::find($pageId);

        if(empty($page)){

            $request->session()->flash('error', 'Record not found');
            return response()->json([
                'status' => true,
                'message' => 'Record not found'
            ]);

        }

        $page->delete();

        $request->session()->flash('success', 'Page deleted successfully');
        
        return response()->json([
            'status' => true,
            'message' => 'Page deleted successfully'
        ]);
        
        
    }


















}
