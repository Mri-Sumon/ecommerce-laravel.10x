<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\BrandController;
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

        //CATEGORY ROUTES
        Route::get('/categories',[CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create',[CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories/store',[CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{categoryId}/edit',[CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{categoryId}',[CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{categoryId}',[CategoryController::class, 'destroy'])->name('categories.delete');

        //SUBCATEGORY ROUTES
        Route::get('/sub-categories',[SubCategoryController::class, 'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create',[SubCategoryController::class, 'create'])->name('sub-categories.create');
        Route::post('/sub-categories/store',[SubCategoryController::class, 'store'])->name('sub-categories.store');
        Route::get('/sub-categories/{subCategoryId}/edit',[SubCategoryController::class, 'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategoryId}',[SubCategoryController::class, 'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{subCategoryId}',[SubCategoryController::class, 'destroy'])->name('sub-categories.delete');

        // BRANDS ROUTES
        Route::get('/brands',[BrandController::class, 'index'])->name('brands.index'); 
        Route::get('/brands/create',[BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands/store',[BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands/{brandId}/edit',[BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brandId}',[BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brandId}',[BrandController::class, 'destroy'])->name('brands.delete');

        //TEMP IMAGE CREATE
        Route::post('/upload-temp-image',[TempImagesController::class, 'create'])->name('temp-images.create');

        //CREATE SLUG
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




        
        
        










    });
});
    


















