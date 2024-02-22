<?php
namespace App\Http\Controllers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(){

        $categories=Category::orderBy('sort', 'ASC')
        //for relation create, sub_category function declare in category model.
        ->with(['sub_category' => function ($query){
            $query->orderBy('sort', 'ASC'); 
        }])->where('status', 1)->get();
        $brands=Brand::orderBy('sort', 'ASC')->where('status', 1)->get();
        $products=Product::orderBy('sort', 'ASC')->where('status', 1)->get();


        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;


        return view("front.shop",$data);

    }
}
