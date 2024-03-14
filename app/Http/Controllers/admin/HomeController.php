<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function index(){

        $totalOrders = Order::where('status','!=','canceled' )->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role',1)->count();

        //Overall revenue:
        $totalRevenue = Order::where('status','!=','canceled' )->sum('grant_total');



        //This month revenue:
        //Get current month start date.
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        //Get current date.
        $currentDate = Carbon::now()->format('Y-m-d');

        $revenueThisMonth = Order::where('status','!=','canceled')
                            ->whereDate('created_at','>=',$startOfMonth)
                            ->whereDate('created_at','<=',$currentDate)
                            ->sum('grant_total');



        //Last month revenue:
        //Get last month start date.
        $lastMothStartDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        //Get last month end date.
        $lastMothEndDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        //Get last month name.
        $lastMothName = Carbon::now()->subMonth()->startOfMonth()->format('M');

        $revenueLastMonth = Order::where('status','!=','canceled')
                            ->whereDate('created_at','>=',$lastMothStartDate)
                            ->whereDate('created_at','<=',$lastMothEndDate)
                            ->sum('grant_total');



        //Last 30 Days revenue:
        //Get first date of last 30 Days date.
        $lastThirtyStartDate = Carbon::now()->subDays(30)->format('Y-m-d');

        $revenueLastThirtyDays = Order::where('status','!=','canceled')
                            ->whereDate('created_at','>=',$lastThirtyStartDate)
                            ->whereDate('created_at','<=',$currentDate)
                            ->sum('grant_total');

        
        
        //Delete temp image here:
        //Delete all temp images before current date.
        $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d H:i:s');
        $tempImages = TempImage::where('created_at','<=',$dayBeforeToday)->get();

        foreach($tempImages as $image){

            $tempPath = public_path('/temp/'.$image->name);
            $thumPath = public_path('/temp/thumb/'.$image->name);

            //Delete temp folder image
            if(File::exists($tempPath)){
                File::delete($tempPath);
            }

            //Delete thumb folder image
            if(File::exists($thumPath)){
                File::delete($thumPath);
            }

            //Image delete from database
            TempImage::where('id', $image->id)->delete();

        }


        return view('admin.dashboard',[
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
            'revenueThisMonth' => $revenueThisMonth,
            'revenueLastMonth' => $revenueLastMonth,
            'lastMothName' => $lastMothName,
            'revenueLastThirtyDays' => $revenueLastThirtyDays,
        ]);

        
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }



}
