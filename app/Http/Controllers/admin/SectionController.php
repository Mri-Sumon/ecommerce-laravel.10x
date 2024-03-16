<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{

    public function create(){
        $sections = Section::get();
        $data['sections'] = $sections;
        return view('admin.sections.create', $data);
    }



    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required', 
            'slug' => 'required|unique:brands',
            'status' => 'required', 
        ]);

        if($validator->passes()){
            
            $createBy = Auth::user()->id;

            $section = new Section();
            $section->name = $request->name;
            $section->slug = $request->slug;
            $section->status = $request->status;
            $section->created_by = $createBy;
            $section->save();

            $request->session()->flash('success', 'Section creaded successfully');
            return response()->json([
                'status' => true, 
                'message' => 'Section created successfully'
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }

    }



    public function edit($sectionId, Request $request){
  
        $section = Section::find($sectionId);
        if(empty($section)){
            $request->session()->flash('error','Record not found');
            return redirect()->route('sections.create');
        }
        $data['section'] = $section;
        return view('admin.sections.edit', $data);

    }



    public function update($sectionId, Request $request){

        $section = Section::find($sectionId);
        if(empty($section)){
            $request->session()->flash('error', 'Record not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Record not found'
            ]);
        }
        
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sections,slug,'.$section->id.',id',
            'status' => 'required',
        ]);

        if($validator->passes()){
            
            $updatedBy = Auth::user()->id;

            $section->name = $request->name;
            $section->slug = $request->slug;
            $section->status = $request->status;
            $section->updated_by = $updatedBy;
            $section->save();

            $request->session()->flash('success', 'Section updated successfully');
            return response()->json([
                'status' => true, 
                'message' => 'Section updated successfully'
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }
    }



    public function destroy($sectionId, Request $request){

        $section = Section::find($sectionId);
        if(empty($section)){
            $request->session()->flash('error', 'Record not found');
            return response()->json([
                'status' => true,
                'message' => 'Record not found'
            ]);
        }

        $section->delete();

        $request->session()->flash('success', 'Section deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Section deleted successfully'
        ]);
        
    }



}
















