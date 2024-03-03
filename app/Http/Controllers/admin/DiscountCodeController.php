<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{
    public function index(Request $request){

        $discountCoupons = DiscountCoupon::latest();

        if(!empty($request->get('keyword'))){
            $discountCoupons = $discountCoupons->where('name','like','%'.$request->get('keyword').'%');
            $discountCoupons = $discountCoupons->OrWhere('code','like','%'.$request->get('keyword').'%');
        }

        $discountCoupons = $discountCoupons->paginate(10);
        return view('admin.coupons.list', compact('discountCoupons'));
    }


    public function create(){

        return view('admin.coupons.create');
    }

  
    public function store(Request $request){
    
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required'
        ]);
    
        if ($validator->passes()) {
    
            // Start date must be greater than current date
            if (!empty($request->starts_at)) {
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
    
                if ($startAt->lte($now)) {
                    return response()->json([
                        'status'=> false,
                        'errors' => ['starts_at' => 'Start date must be greater than current date & time']
                    ]);
                }
            }
    
            // Expire date must be greater than start date
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
    
                if ($expiresAt->lte($startAt)) {
                    return response()->json([
                        'status'=> false,
                        'errors' => ['expires_at' => 'Expire date must be greater than start date & time']
                    ]);
                }
            }
    
            $discountCode = new DiscountCoupon();
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = !empty($request->starts_at) ? Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at) : null;
            $discountCode->expires_at = !empty($request->expires_at) ? Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at) : null;
            $discountCode->save();
    
            $message = 'Discount coupon added successfully';
            session()->flash('success', $message);
    
            return response()->json([
                'status'=> true,
                'message' => $message
            ]);
    
        } else {
            return response()->json([
                'status'=> false,
                'errors' => $validator->errors()
            ]);
        }
    }
    


    public function edit(Request $request, $id){

        $coupon = DiscountCoupon::find($id);

        if ($coupon == null) {
            session()->flash('error', 'Record not found');
            return redirect()->route('coupons.index');
        }

        $data['coupon'] = $coupon;
        return view('admin.coupons.edit', $data);
    }


    public function update(Request $request, $id){

        $discountCode = DiscountCoupon::find($id);

        if ($discountCode == null) {
            session()->flash('error', 'Record not found');
            return response()->json([
                'status'=> true
            ]);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required'
        ]);

        if ($validator->passes()) {

            // Expire date must be gratter than start date
            if (!empty($request->starts_at) && !empty($request->expires_at)) {

                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);

                if ($expiresAt->gt($startAt) == false) {
                    return response()->json([
                        'status'=> false,
                        'errors' => ['expires_at' => 'Expire date must be greater than start date & time']
                    ]);
                }
            }

            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();

            $message = 'Discount coupon updated successfully';
            session()->flash('success', $message);

            return response()->json([
                'status'=> true,
                'message' => $message
            ]);

        }else{

            return response()->json([
                'status'=> false,
                'errors' => $validator->errors()
            ]);
        }
    }


    
    public function destroy(Request $request, $id){

        $discountCode = DiscountCoupon::find($id);

        if ($discountCode == null) {
            session()->flash('error', 'Record not found');
            return response()->json([
                'status'=> true
            ]);
        }

        $discountCode->delete();

        session()->flash('success', 'Discount coupon Deleted successfully !');
        return response()->json([
            'status'=> true
        ]); 
    }







}


















