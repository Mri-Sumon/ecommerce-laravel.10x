<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\OrderItem;
use Gloudemans\Shoppingcart\Facades\Cart;

class AuthController extends Controller
{
    public function login(){
        return view('front.account.login');
    }

    public function register(){
        return view('front.account.register');
    }

    public function processRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed'
        ]);

        if ($validator->passes()) {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success', 'You have been registerd successfully');

            return response()->json([
                'status' => true,
            ]);

        }else{

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function authenticate(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->has('remember'))) {
                
                // Check if checkout page is not empty
                if (Cart::count() > 0) {
                    // Check if there's an intended URL in the session
                    if (session()->has('url.intended')) {
                        // Retrieve and remove the intended URL from the session
                        $intendedUrl = session()->pull('url.intended');
                        // Redirect to the intended URL
                        return redirect($intendedUrl);
                    } else {
                        // If no intended URL, handle this scenario
                        // You might want to redirect to a different route or display an error message
                        return redirect()->route("account.profile");
                    }
                } else {
                    // If the cart is empty, handle this scenario
                    // You might want to redirect to a different route or display an error message
                    // For now, let's redirect to the profile route
                    return redirect()->route("account.profile");
                }

            } else {
                // If authentication fails, redirect back to the login page with an error message
                return redirect()->route("account.login")
                    ->withInput($request->only('email'))
                    ->with('error', 'Invalid Email/Password!');
            }

        } else {
            // If validation fails, redirect back to the login page with validation errors
            return redirect()->route("account.login")
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }
    


    public function profile(){
        return view('front.account.profile');
    }


    public function logout(){
        Auth::logout();
        return redirect()->route("account.login")
            ->with('success', 'You are logged out successfully!');
    }


    
}
