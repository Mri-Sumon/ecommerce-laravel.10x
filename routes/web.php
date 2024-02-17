<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\TempImagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

Route::get('/', function () {return view('welcome');});

Route::group(['prefix'=>'admin'], function(){
    Route::group(['middleware' => 'admin.guest'], function(){
        Route::get('/login',[AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate',[AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function(){
        Route::get('/dashboard',[HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class, 'logout'])->name('admin.logout');
        //Category Routes
        Route::get('/categories',[CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create',[CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories/store',[CategoryController::class, 'store'])->name('categories.store');
        Route::get('/getSlug', function(Request $request){
            $slug = '';
            if(!empty($request->title)){
                $slug = Str::slug($request->title);
            }

            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');
        Route::post('/upload-temp-image',[TempImagesController::class, 'create'])->name('temp-images.create');
        Route::get('/categories/{categoryId}/edit',[CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{categoryId}',[CategoryController::class, 'update'])->name('categories.update');

        Route::get('/categories/destroy',[CategoryController::class, 'destroy'])->name('categories.destroy');
    });
});
    


















