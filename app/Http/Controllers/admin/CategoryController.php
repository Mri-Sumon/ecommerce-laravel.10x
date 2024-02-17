<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function index(Request $request){
        $categories = Category::latest();
        if(!empty($request->get('keyword'))){
            $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
        }
        $categories = $categories->paginate(10);
        return view('admin.category.list', compact('categories'));
    }

    public function create(){
        return view('admin.category.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required', 
            'slug' => 'required|unique:categories', 
        ]);

        if($validator->passes()){
            
            $createBy = Auth::user()->id;

            $category = new Category();
            // $category->image = $request->image;
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->created_by = $createBy;
            $category->save();

            // save image here 
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                //explode() --> স্ট্রিংকে এ্যারেতে পরিনত করে। 1708119231.PNG এটি যদি ইমেজ নেম হয়, তাহলে এ্যারে হবে [0]=1708119231, [1]=PNG
                $extArray = explode('.',$tempImage->name);
                //last() --> একটি এ্যারের লাষ্ট ভ্যালু নিয়ে আসবে অর্থাৎ [1]=PNG
                $ext = last($extArray);
                //last inserted ইমেজ নেম এবং last category আইডি কনক্যাট হবে অর্থাৎ নতুন নেম হবে 2.PNG
                $newImageName = $category->id.'.'.$ext;
                //public/temp/ থেকে ইমেজের নামটাকে তুলে আনবে।
                $sPath = public_path().'/temp/'.$tempImage->name;
                //image এর destination হবে public/temp/uploads/category/ এর ভীতরে নতুন নাম নিয়ে।
                $dPath = public_path().'/uploads/category/'.$newImageName;
                //ফাইলকে বা ইমেজকে source path থেকে কপি করে নিয়ে destination path এ রেখে দিবে।
                File::copy($sPath, $dPath);
                //ইমেজ নেম কে ডাটাবেসে স্টোর করবে।
                $category->image = $newImageName;
                $category->save();

                //Generate Image Thumbnail using image intervention package.
                //Thumbnail ইমেজের জন্য নতুন একটি ডেষ্টিনেশন সেট করবো।
                $dPath=public_path().'/uploads/category/thumb/'.$newImageName;
                if($sPath){
                    //intervention package এর ভার্শন-3 তে Image নিয়ে কাজ করার জন্য ImageManager এবং Image Driver এর অবজেক্ট ক্রিয়েট করতে হয়।
                    $manager = new ImageManager(new Driver());
                    //read() এর কাজ হলো, যেখানে ইমেজ আছে সেখান থেকে ইমেজকে রিড করবে।
                    $img = $manager->read($sPath);
                    //রিড করা ইমেজকে রি-সাইজ করবে।
                    $img = $img->resize(450, 600);
                    //নতুন ডেষ্টিনেশনে Thumbnail ইমেজটাকে সেভ করে দিবো।
                    $img->save($dPath);
                }
            }

            $request->session()->flash('success', 'Category added successfully!');
            return response()->json([
                'status' => true, 
                'message' => 'Category added successfully'
            ]);

        }else{
            return response()->json([
                'status' => false, 
                'errors' => $validator->errors(),
            ]);
        }

    }

    public function edit(){

    }

    public function update(){

    }

    public function destroy(){

    }
}
