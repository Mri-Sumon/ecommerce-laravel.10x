<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    

    public function showChangePasswordForm(){
        return view('admin.change-password');
    }





    public function changePassword(Request $request){

        $validator = Validator::make($request->all(),[
            'old_password' => 'required', 
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password', 
        ]);

        if($validator->passes()){

            $user = User::select('id','password')->where('id', Auth::user()->id)->first();

            if(!Hash::check($request->old_password, $user->password)){

                session()->flash('error', 'Your old password is incorrect, please try again.');

                return response()->json([
                    'status' => true, 
                ]);

            }

            User::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            $request->session()->flash('success', 'You have successfully changed your password.');
            return response()->json([
                'status' => true, 
                'message' => 'You have successfully changed your password'
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }

    }


    public function settings(){
        return view('admin.settings'); 
    }

}
