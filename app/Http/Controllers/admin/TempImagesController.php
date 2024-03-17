<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TempImagesController extends Controller
{

    public function create(Request $request){

        if ($request->has('image')) {
            $imageField = 'image';
        } elseif ($request->has('logo')) {
            $imageField = 'logo';
        } elseif ($request->has('adminPicture')) {
            $imageField = 'adminPicture';
        } elseif ($request->has('icon')) {
            $imageField = 'icon';
        } elseif ($request->has('imageSection')) {
            $imageField = 'imageSection';
        } elseif ($request->has('imageWithTextSection')) {
            $imageField = 'imageWithTextSection';
        } elseif ($request->has('footerLogo')) {
            $imageField = 'footerLogo';
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No image uploaded!',
            ]);
        }
    
        // Retrieve the image from the request
        $image = $request->file($imageField);
    
        if ($image) {
   
            // Process the uploaded image
            $ext = $image->getClientOriginalExtension();
            $newName = time().'.'.$ext;
    
            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();
    
            $image->move(public_path().'/temp', $newName);
    
            //Generate thumbnail image
            $sPath = public_path().'/temp/'.$newName;
            $dPath = public_path().'/temp/thumb/'.$newName;
            if ($sPath) {
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sPath);
                //Image resize ratio wise
                $img->resize(300, 275, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($dPath);
            }
    
            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'image_path' => asset('/temp/thumb/'.$newName),
                'message' => 'Image uploaded successfully!',
            ]);
        }
    }
   
    

}



















