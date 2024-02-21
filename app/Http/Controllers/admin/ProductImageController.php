<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductImageController extends Controller
{
    public function update(Request $request){

        // Validate if an image was uploaded
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $ext = $image->getClientOriginalExtension();
    
            $sPath = $image->getPathName();
    
            $productImage = new ProductImage();
            $productImage->product_id =  $request->product_id;
            $productImage->image = 'NULL';
            $productImage->save();
    
            $imageName = $request->product_id.'-'.$productImage->id.time().'.'.$ext;
            $productImage->image = $imageName;
            $productImage->save();
    
            // Generate and save large thumbnail
            $dPath = public_path().'/uploads/product/large/'.$imageName;
            if($sPath){
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sPath);
                $img->resize(1400, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($dPath);
            }
    
            // Generate and save small thumbnail
            $dPath = public_path().'/uploads/product/small/'.$imageName;
            if($sPath){
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sPath);
                $img->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($dPath);
            }
    
            return response()->json([ 
                'status' => true,
                'image_id' => $productImage->id,
                'image_path' => asset('/uploads/product/small/'.$productImage->image),
                'message' => 'Image saved successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No image uploaded',
            ]);
        }
    }
    
    public function destroy(Request $request){
    
        $productImage = ProductImage::find($request->imageId);
    
        if(empty($productImage)){
            return response()->json([
                'status' => true,
                'message' => 'Image not found',
            ]);
        }
    
        //Delete image from laravel folder
        File::delete(public_path('/uploads/product/small/'.$productImage->image));
        File::delete(public_path('/uploads/product/large/'.$productImage->image));
    
        //delete image from database
        $productImage->delete();
    
        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully',
        ]);
    }
    
    
}






