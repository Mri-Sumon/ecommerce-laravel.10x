<?php
namespace App\Http\Controllers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug="NULL", $subCategorySlug="NULL"){

        //when press any category or subcategory, that will show as active or stay open the dropdown.
        $categorySelected = '';
        $subCategorySelected = '';
        
        $categories=Category::orderBy('sort', 'ASC')
        //for relation create, sub_category function declare in category model.
        ->with(['sub_category' => function ($query){
            $query->orderBy('sort', 'ASC'); 
        }])->where('status', 1)->get();

        $brands=Brand::orderBy('sort', 'ASC')->where('status', 1)->get();
        //Get all data
        $products = Product::orderBy('sort','DESC');

        // Apply Brand filters here
        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            if($category){
                $products = $products->where('category_id', $category->id);
                $categorySelected = $category->id;
            }
        }


        // Apply Range filters here
        if($request->get('price_max') != '' && $request->get('price_min') != ''){
            if($request->get('price_max') == 10000){
                $products = $products->whereBetween('price', [intval($request->get('price_min')), 1000000]);
            }else{
                $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
            }
        }


        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            if($subCategory){
                $products = $products->where('sub_category_id', $subCategory->id);
                $subCategorySelected = $subCategory->id;
            }
        }

        //when press any brand , that will show as tik mark.
        $brandArray = [];
        if(!empty($request->get('brand'))){
            $brandArray = explode(',',$request->get('brand'));
            //Get products through brand_id.
            $products = $products->whereIn('brand_id',$brandArray);
        }

        $products = $products->where('status', 1)->get();

        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;
        $data['brandArray'] = $brandArray;

        $data['priceMin'] = intval($request->get('price_min'));
        $data['priceMax'] = (intval($request->get('price_max')) == 0) ? 10000 : intval($request->get('price_max'));        

        return view("front.shop",$data);

    }
}
