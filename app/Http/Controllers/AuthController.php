<?php
namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


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







    public function authenticate(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

                if (session()->has('url.intended')) {
                    return redirect(session()->get('url.intended'));
                }

                return redirect()->route("account.profile");

            }else{

                return redirect()->route("account.login")
                    ->withInput($request->only('email'))
                    ->with('error', 'Invalid Email/Password!');
            }

        }else{

            return redirect()->route("account.login")
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }
    





    public function profile(){

        $userId = Auth::user()->id; 

        $countries = Country::orderBy('name','ASC')->get();
        $user = User::where('id', $userId)->first();
        $address = CustomerAddress::where('user_id',$userId)->first();


        return view('front.account.profile',[
            'user' => $user,
            'countries' => $countries,
            'address' => $address,
        ]);
    }








    public function updateProfile(Request $request){
       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'phone' => 'required',
        ]);
        

        if($validator->passes()){

            $user = User::find(Auth::user()->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            $request->session()->flash('success', 'Profile updated successfully');
            return response()->json([
                'status' => true, 
                'message' => 'Profile updated successfully'
            ]);


        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }

    }








    public function updateAddress(Request $request){
       
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:3',
            'last_name' => 'required',
            'email' => 'required|email',
            'country_id' => 'required',
            'address' => 'required|min:20',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required'
        ]);
        

        if($validator->passes()){
            
            $user = Auth::user();

            CustomerAddress::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'country_id' => $request->country_id,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip
                ]
            );

            $request->session()->flash('success', 'Address updated successfully');
            return response()->json([
                'status' => true, 
                'message' => 'Address updated successfully'
            ]);


        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }

    }





    
    public function logout(){
        Auth::logout();
        return redirect()->route("account.login")
            ->with('success', 'You are logged out successfully!');
    }







    public function orders(){
        $data = [];
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        $data['orders'] = $orders;
        return view('front.account.order', $data);
    }





    
    public function orderDetail($id){
        $data = [];
        $user = Auth::user();

        $order = Order::where('user_id', $user->id)->where('id', $id)->first();
        $data['order'] = $order;

        $orderItems = OrderItem::where('order_id', $id)->get();
        $data['orderItems'] = $orderItems;

        $orderItemsCount = OrderItem::where('order_id', $id)->count();
        $data['orderItemsCount'] = $orderItemsCount;

        return view('front.account.order-detail', $data);
    }

    



    
    public function wishlist(){
        $wishlists = Wishlist::where('user_id', Auth::user()->id)->with('product')->get();
        $data=[];
        $data['wishlists'] = $wishlists;
        return view("front.account.wishlist", $data);
    }







    public function removeProductFromWishlist(Request $request){

        $wishlist = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();

        if($wishlist == null){

            $request->session()->flash('error', 'Product already removed');
            return response()->json([
                'status' => true,
            ]);

        }else{

            Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->delete();

            $request->session()->flash('success', 'Product removed successfully');
            return response()->json([
                'status' => true,
            ]);

        }
        
    }






    public function showChangePasswordForm(){
        return view('front.account.change-password');
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


    public function forgotPassword(){
        return view('front.account.forgot-password');
    }


    public function processForgotPassword(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email', 
        ]);

        if($validator->fails()){
            return redirect()->route('front.forgotPassword')->withInput()->withErrors($validator);
        }

        //Generate token
        $token = Str::random(60);

        //Remove previous stored data from password_reset_tokens table.
        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        //Store data to password_reset_tokens table
        \DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        //Send Mail Here
        $user = User::where('email',$request->email)->first();

        $formData = [
            'token' => $token,
            'user' => $user,
            'mailSubject' => 'You have requested to reset password.'
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($formData));

        return redirect()->route('front.forgotPassword')->with('success', 'Please check your inbox to reset your password.');

    }

    
    public function resetPassword($token){

        $tokenExist = \DB::table('password_reset_tokens')->where('token', $token)->first();
        if($tokenExist == null){
            return redirect()->route('front.forgotPassword')->with('error', 'Invalid request.');
        }
        return view('front.account.reset-password',[
            'token' => $token,
        ]);

    }


    public function processResetPassword(Request $request){

        $token = $request->token;

        $tokenObj = \DB::table('password_reset_tokens')->where('token', $token)->first();

        if($tokenObj == null){
            return redirect()->route('front.forgotPassword')->with('error', 'Invalid request.');
        }

        $user = User::where('email', $tokenObj->email)->first();

        $validator = Validator::make($request->all(),[
            'new_password' => 'required|min:5', 
            'confirm_password' => 'required|same:new_password', 
        ]);

        if($validator->fails()){
            return redirect()->route('front.resetPassword',$token)->withErrors($validator);
        }

        User::where('id',$user->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        // After update password remove token from password_reset_tokens table.
        \DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        return redirect()->route('account.login')->with('success', 'You have successfully updated your password.');

    }




}


















