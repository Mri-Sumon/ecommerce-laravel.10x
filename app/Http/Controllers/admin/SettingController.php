<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Setting;
use App\Models\TempImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SettingController extends Controller
{
    

    public function showChangePasswordForm(){
        return view('admin.change-password');
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




    public function settings(){
        $sections = Section::where('status', 1)->get();
        return view('admin.settings', compact('sections'));
    }
    


    public function update(Request $request, $id){

        Setting::where('id', $id)->update([
            'title' => $request->title,
            'icon_id' => $request->icon_id,
            'companyName' => $request->companyName,
            'logo_id' => $request->logo_id,
            'adminPicture_id' => $request->adminPicture_id,
            'importantUpdates' => $request->importantUpdates,
            'selectImageSection' => $request->selectImageSection,
            'imageFirstTitle' => $request->imageFirstTitle,
            'imageSection_id' => $request->imageSection_id,
            'imageSecondTitle' => $request->imageSecondTitle,
            'selectImgWithTextSection' => $request->selectImgWithTextSection,
            'description' => $request->description,
            'selectVideoSection' => $request->selectVideoSection,
            'videoLink' => $request->videoLink,
            'videoFirstTitle' => $request->videoFirstTitle,
            'videoSecondTitle' => $request->videoSecondTitle,
            'facebook' => $request->facebook,
            'whatsapp' => $request->whatsapp,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
            'linkedin' => $request->linkedin,
            'pinterest' => $request->pinterest,
            'map' => $request->map,
            'officeHours' => $request->officeHours,
            'address' => $request->address,
            'footerLogo_id' => $request->footerLogo_id,
            'email' => $request->email,
            'contact' => $request->contact,
        ]);

        
        $setting = Setting::find($id);

        // Save image for icon
        if (!empty($request->icon_id)) {
            $tempImage = TempImage::find($request->icon_id);
            if ($tempImage) {
                // Copy the image from temporary location to the final location
                $extArray = explode('.', $tempImage->name);
                $newImageName = $setting->id . '.jpg'; // Convert extension to .jpg
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/setting/' . $newImageName;
                File::copy($sPath, $dPath);

                // Create a thumbnail for the image
                $thumbPath = public_path() . '/uploads/setting/thumb/' . $newImageName;
                if ($sPath) {
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read($sPath);
                    // Resize the image while maintaining aspect ratio
                    $img->resize(450, 600, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->save($thumbPath);
                }

                // Update 'icon_id' with the ID of the newly uploaded image
                $setting->icon_id = $tempImage->id;
                $setting->save();
            } else {
                // Handle case when temp image is not found
                return response()->json([
                    'status' => false,
                    'message' => 'Temp image not found'
                ], 404);
            }
        }

        // Save image for logo
        if (!empty($request->logo_id)) {
            $tempImage = TempImage::find($request->logo_id);
            if ($tempImage) {
                // Copy the image from temporary location to the final location for logo
                $extArray = explode('.', $tempImage->name);
                $newLogoName = 'logo_' . $setting->id . '.jpg'; // Convert extension to .jpg
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPathLogo = public_path() . '/uploads/setting/' . $newLogoName;
                File::copy($sPath, $dPathLogo);

                // Create a thumbnail for the logo image
                $thumbPathLogo = public_path() . '/uploads/setting/thumb/' . $newLogoName;
                if ($sPath) {
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read($sPath);
                    // Resize the logo image while maintaining aspect ratio
                    $img->resize(450, 600, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->save($thumbPathLogo);
                }

                // Update 'logo_id' with the ID of the newly uploaded logo image
                $setting->logo_id = $tempImage->id;
                $setting->save();
            } else {
                // Handle case when temp image is not found
                return response()->json([
                    'status' => false,
                    'message' => 'Temp image not found'
                ], 404);
            }
        }

        // Save image for admin picture
        if (!empty($request->adminPicture_id)) {
            $tempImage = TempImage::find($request->adminPicture_id);
            if ($tempImage) {
                // Copy the image from temporary location to the final location for admin picture
                $extArray = explode('.', $tempImage->name);
                $newAdminPictureName = 'admin_picture_' . $setting->id . '.jpg'; // Convert extension to .jpg
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPathAdminPicture = public_path() . '/uploads/setting/' . $newAdminPictureName;
                
                File::copy($sPath, $dPathAdminPicture);
                
                // Create a thumbnail for the admin picture image
                $thumbPathAdminPicture = public_path() . '/uploads/setting/thumb/' . $newAdminPictureName;
                if ($sPath) {
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read($sPath);
                    // Resize the admin picture image while maintaining aspect ratio
                    $img->resize(450, 600, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->save($thumbPathAdminPicture);
                }
                
                // Update 'adminPicture_id' with the ID of the newly uploaded admin picture image
                $setting->adminPicture_id = $tempImage->id;
                $setting->save();
            } else {
                // Handle case when temp image is not found
                return response()->json([
                    'status' => false,
                    'message' => 'Temp image not found'
                ], 404);
            }
        }


        $request->session()->flash('success', 'Setting updated successfully');
        return response()->json([
            'status' => true, 
            'message' => 'Setting updated successfully'
        ]);

    }



    
}











