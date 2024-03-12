<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\DiscountCodeController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 




Route::get('/',[FrontController::class, 'index'])->name('front.home');
//The ? indicates that it's optional, if category or subcategory slug exist they will access otherwise not.
Route::get('/shop/{categorySlug?}/{subCategorySlug?}',[ShopController::class, 'index'])->name('front.shop');
Route::get('/product/{slug}',[ShopController::class, 'product'])->name('front.product');
Route::get('/cart',[CartController::class, 'cart'])->name('front.cart');
Route::post('/add-to-cart',[CartController::class, 'addToCart'])->name('front.addToCart');
Route::post('/update-cart',[CartController::class, 'updateCart'])->name('front.updateCart');
Route::post('/delete-item', [CartController::class, 'deleteItem'])->name('front.deleteItem.cart');
Route::get('/checkout',[CartController::class, 'checkout'])->name('front.checkout');
Route::post('/process-checkout',[CartController::class, 'processCheckout'])->name('front.processCheckout');
Route::get('/thanks/{orderId}',[CartController::class, 'thankyou'])->name('front.thankyou');
Route::post('/get-order-summery',[CartController::class, 'getOrderSummery'])->name('front.getOrderSummery');
Route::post('/apply-discount', [CartController::class, 'applyDiscount'])->name('front.applyDiscount');
Route::post('/remove-discount', [CartController::class, 'removeCoupon'])->name('front.removeCoupon');
Route::post('/add-to-wishlist', [FrontController::class, 'addToWishlist'])->name('front.addToWishlist');
Route::get('/page/{slug}',[FrontController::class, 'page'])->name('front.page');



Route::group(['prefix' => 'account'], function(){

    Route::group(['middleware' => 'guest'], function(){
        Route::get('/login', [AuthController::class, 'login'])->name('account.login');
        Route::post('/login', [AuthController::class, 'authenticate'])->name('account.authenticate');
        Route::get('/register', [AuthController::class, 'register'])->name('account.register');
        Route::post('/process-register', [AuthController::class, 'processRegister'])->name('account.processRegister');
    });

    Route::group(['middleware' => 'auth'], function(){
        Route::get('/profile', [AuthController::class, 'profile'])->name('account.profile');
        Route::post('/updateProfile', [AuthController::class, 'updateProfile'])->name('account.updateProfile');
        Route::post('/updateAddress', [AuthController::class, 'updateAddress'])->name('account.updateAddress');
        Route::get('/my-orders', [AuthController::class, 'orders'])->name('account.orders');
        Route::get('/my-wishlist', [AuthController::class, 'wishlist'])->name('account.wishlist');
        Route::post('/remove-product-from-wishlist', [AuthController::class, 'removeProductFromWishlist'])->name('account.removeProductFromWishlist');
        Route::get('/order-detail/{orderId}', [AuthController::class, 'orderDetail'])->name('account.orderDetail');
        Route::get('/logout', [AuthController::class, 'logout'])->name('account.logout');
    });
});




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


        //BRANDS ROUTES
        Route::get('/brands',[BrandController::class, 'index'])->name('brands.index'); 
        Route::get('/brands/create',[BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands/store',[BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands/{brandId}/edit',[BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brandId}',[BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brandId}',[BrandController::class, 'destroy'])->name('brands.delete');


        //PRODUCT ROUTES
        Route::get('/products',[ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create',[ProductController::class, 'create'])->name('products.create');
        Route::post('/products/store',[ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{productId}/edit',[ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{productId}',[ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{productId}',[ProductController::class, 'destroy'])->name('products.delete');
        Route::get('/get-products', [ProductController::class, 'getProducts'])->name('products.getProducts');


        //PRODUCT SUBCATEGORY ROUTES
        Route::get('/products-subCategories',[ProductSubCategoryController::class, 'index'])->name('products-subCategories.index'); 


        //TEMP IMAGE CREATE ROUTE
        Route::post('/upload-temp-image',[TempImagesController::class, 'create'])->name('temp-images.create');


        //PRODUCT IMAGE UPDATED ROUTE
        Route::post('/product-images/update',[ProductImageController::class, 'update'])->name('product-images.update');
        Route::delete('/product-images/{imageId}',[ProductImageController::class, 'destroy'])->name('product-images.destroy');


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


        //SHIPPING ROUTES
        Route::get('/shipping/create', [ShippingController::class, 'create'])->name('shipping.create');
        Route::post('/shipping', [ShippingController::class, 'store'])->name('shipping.store');
        Route::get('/shipping/{id}', [ShippingController::class, 'edit'])->name('shipping.edit');
        Route::put('/shipping/{id}', [ShippingController::class, 'update'])->name('shipping.update');
        Route::delete('/shipping/{id}', [ShippingController::class, 'destroy'])->name('shipping.delete');


        //COUPON CODE ROUTES
        Route::get('/coupons', [DiscountCodeController::class, 'index'])->name('coupons.index');
        Route::get('/coupons/create', [DiscountCodeController::class, 'create'])->name('coupons.create');
        Route::post('/coupons', [DiscountCodeController::class, 'store'])->name('coupons.store');
        Route::get('/coupons/{coupon}/edit', [DiscountCodeController::class, 'edit'])->name('coupons.edit');
        Route::put('/coupons/{coupon}', [DiscountCodeController::class, 'update'])->name('coupons.update');
        Route::delete('/coupons/{coupon}', [DiscountCodeController::class, 'destroy'])->name('coupons.delete');


        //ORDER ROUTES 
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'detail'])->name('orders.detail');
        Route::post('/order/change-status/{id}', [OrderController::class, 'changeOrderStatus'])->name('orders.changeOrderStatus');
        Route::post('/order/send-email/{id}', [OrderController::class, 'sendInvoiceEmail'])->name('orders.sendInvoiceEmail');


        //USER ROUTES
        Route::get('/users',[UserController::class, 'index'])->name('users.index');
        Route::get('/users/create',[UserController::class, 'create'])->name('users.create');
        Route::post('/users/store',[UserController::class, 'store'])->name('users.store');
        Route::get('/users/{userId}/edit',[UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{userId}',[UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{userId}',[UserController::class, 'destroy'])->name('users.delete');


        //PAGE ROUTES
        Route::get('/pages',[PageController::class, 'index'])->name('pages.index'); 
        Route::get('/pages/create',[PageController::class, 'create'])->name('pages.create');
        Route::post('/pages/store',[PageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{pageId}/edit',[PageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{pageId}',[PageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{pageId}',[PageController::class, 'destroy'])->name('pages.delete');








    });









});
    


















