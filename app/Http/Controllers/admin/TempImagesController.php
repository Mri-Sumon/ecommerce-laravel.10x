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
        $image = $request->image;
        if(!empty($image)){
            $ext = $image->getClientOriginalExtension();
            $newName = time().'.'.$ext;

            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path().'/temp',$newName);

            //Generate thumbnail image
            $sPath = public_path().'/temp/'.$newName;
            $dPath = public_path().'/temp/thumb/'.$newName;
            if($sPath){
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sPath);
                // image resize ratio wise
                $img->resize(300, 275, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($dPath);
            }


            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'image_path' => asset('/temp/thumb/'.$newName),
                'message' => 'image_uploaded_successfully!',
            ]);
        }
    }


}
