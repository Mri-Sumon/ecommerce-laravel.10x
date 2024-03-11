<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{


    public function index(Request $request)
    {
        $users = User::latest();
    
        if (!empty($request->get('keyword'))) {
            $users->where('name', 'like', '%' . $request->get('keyword') . '%');
            $users->orWhere('email', 'like', '%' . $request->get('keyword') . '%');
        }

        $users = $users->paginate(10);
        return view('admin.users.list', compact('users'));
    }
    



    public function create(){
        return view('admin.users.create');
    }




    public function store(Request $request){

        $validator = Validator ::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'phone' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            
            $createBy = Auth::user()->id;

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone;
            $user->status = $request->status;
            $user->created_by = $createBy;
            $user->save();

            $request->session()->flash('success', 'User added successfully');
            return response()->json([
                'status' => true, 
                'message' => 'User added successfully'
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }

    }



    public function edit($userId, Request $request){

        $user = User::find($userId);
        if(empty($user)){
            return redirect()->route('users.index')->with('error','User not found');
        }
        
        return view('admin.users.edit',compact('user'));
    }




    public function update($userId, Request $request){

        $user = User::find($userId);

        if(empty($user)){

            $request->session()->flash('error', 'Record not found');
            return response()->json([
                "status" => false,
                "notFound" => true,
                "message" => "Record not found"
            ]);

        }
        
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$userId.',id',
            'phone' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){

            $updatedBy = Auth::user()->id;

            $user->name = $request->name;
            $user->email = $request->email;
            if($request->password != ''){
                $user->password = Hash::make($request->password);
            }
            $user->phone = $request->phone;
            $user->status = $request->status;
            $user->updated_by = $updatedBy;
            $user->save();


            $request->session()->flash('success', 'User updated successfully');
            return response()->json([
                'status' => true, 
                'message' => 'User updated successfully'
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }
    }





    public function destroy($userId, Request $request){

        $user = User::find($userId);

        if(empty($user)){

            $request->session()->flash('error', 'Record not found');
            return response()->json([
                'status' => true,
                'message' => 'Record not found'
            ]);
        }


        $user->delete();
        $request->session()->flash('success', 'User deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
        
    }



}
