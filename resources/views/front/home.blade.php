@extends('front.layouts.app')
@section('content')

    <section class="section-1">
        <div class="row mx-auto">

            <!-- Set two div are same height -->
            <style>
                .equal-height {
                    display: flex;
                
                .equal-height > .col-md-3, .equal-height > .col-md-9 {
                    flex: 1;
                }
            </style>

            <!-- Css for dropdown category  -->
            <style>
                .nav-item h5 span.dropdown-symbol {
                    float: right; 
                    margin-top: 5px; 
                }
            </style>

            <div class="col-md-3 mt-3 a">
                <div class="card" style="height: 100%;">
                    <div class="card-body">
                        <ul class="nav flex-column">
                            @if (getCategories()->isNotEmpty())
                                @foreach (getCategories() as $key => $category)
                                    <li class="nav-item">
                                        @if ($category->sub_category->isNotEmpty())
                                            <h5 class="nav-link accordion-header" id="heading{{$key}}" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}" aria-expanded="false" aria-controls="collapse{{$key}}">
                                                <i class="{{ $category->icon }}"></i> {{ $category->name }} <span class="dropdown-symbol">&#x25BC;</span>
                                            </h5>
                                            <ul class="sub-menu collapse {{($categorySelected == $category->id) ? 'show' : ''}}" id="collapse{{$key}}" aria-labelledby="heading{{$key}}">
                                                @foreach ($category->sub_category as $subCategory)
                                                    <li><a href="{{route('front.shop',[$category->slug, $subCategory->slug]) }}" class="nav-link {{($subCategorySelected == $subCategory->id) ? 'text-primary' : ''}}">{{ $subCategory->name }}</a></li>
                                                @endforeach                               
                                            </ul>
                                        @else
                                            <a href="{{route('front.shop', $category->slug) }}" class="nav-link {{($categorySelected == $category->id) ? 'text-primary' : ''}}">{{ $category->name }}</a>
                                        @endif
                                    </li>
                                @endforeach                
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-9 mt-3">
                <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="false">
                    
                    <div class="carousel-inner">

                        <div class="carousel-item active">
                            <picture>
                                <source media="(max-width: 799px)" srcset="{{asset('front-assets/images/carousel-1-m.jpg')}}" />
                                <source media="(min-width: 800px)" srcset="{{asset('front-assets/images/carousel-1.jpg')}}" />
                                <img src="{{asset('front-assets/images/carousel-1.jpg')}}" alt="" />
                            </picture>
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3">
                                    <h1 class="display-4 text-white mb-3">Kids Fashion</h1>
                                    <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                                    <a href="{{route('front.shop')}}" class="btn btn-outline-light py-2 px-4 mt-3" href="#">Shop Now</a>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <picture>
                                <source media="(max-width: 799px)" srcset="{{asset('front-assets/images/carousel-2-m.jpg')}}" />
                                <source media="(min-width: 800px)" srcset="{{asset('front-assets/images/carousel-2.jpg')}}" />
                                <img src="{{asset('front-assets/images/carousel-2.jpg')}}" alt="" />
                            </picture>
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3">
                                    <h1 class="display-4 text-white mb-3">Womens Fashion</h1>
                                    <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                                    <a href="{{route('front.shop')}}" class="btn btn-outline-light py-2 px-4 mt-3" href="#">Shop Now</a>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <picture>
                                <source media="(max-width: 799px)" srcset="{{asset('front-assets/images/carousel-3-m.jpg')}}" />
                                <source media="(min-width: 800px)" srcset="{{asset('front-assets/images/carousel-3.jpg')}}" />
                                <img src="{{asset('front-assets/images/carousel-2.jpg')}}" alt="" />
                            </picture>
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3">
                                    <h1 class="display-4 text-white mb-3">Shop Online at Flat 70% off on Branded Clothes</h1>
                                    <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                                    <a href="{{route('front.shop')}}" class="btn btn-outline-light py-2 px-4 mt-3" href="#">Shop Now</a>
                                </div>
                            </div>
                        </div>

                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>

                </div>
            </div>
        </div>

    </section>
    



    <section class="section-2">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="box shadow-lg">
                            <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                            <h2 class="font-weight-semi-bold m-0">Quality Product</h5>
                        </div>                    
                    </div>
                    <div class="col-lg-3 ">
                        <div class="box shadow-lg">
                            <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                            <h2 class="font-weight-semi-bold m-0">Free Shipping</h2>
                        </div>                    
                    </div>
                    <div class="col-lg-3">
                        <div class="box shadow-lg">
                            <div class="fa icon fa-exchange-alt text-primary m-0 mr-3"></div>
                            <h2 class="font-weight-semi-bold m-0">14-Day Return</h2>
                        </div>                    
                    </div>
                    <div class="col-lg-3 ">
                        <div class="box shadow-lg">
                            <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                            <h2 class="font-weight-semi-bold m-0">24/7 Support</h5>
                        </div>                    
                    </div>
                </div>
            </div>
    </section>

    <section class="section-3">
            <div class="container">

                <div class="section-title">
                    <h2>Categories</h2>
                </div>

                <div class="row pb-3">
                    @if (getCategories()->isNotEmpty())
                        @foreach (getCategories() as $category)
                            <div class="col-lg-3">
                                <div class="cat-card">
                                    <div class="left">
                                        @if($category->image != "")
                                            <img src="{{asset('uploads/category/thumb/'.$category->image)}}" alt="" class="img-fluid">
                                        @endif
                                        <!-- <img src="{{asset('front-assets/images/cat-1.jpg')}}" alt="" class="img-fluid"> -->
                                    </div>
                                    <div class="right">
                                        <div class="cat-data">
                                            <h2>{{$category->name}}</h2>
                                            <p>100 Products</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
    </section>
    
    <section class="section-4 pt-5">
            <div class="container">

                <div class="section-title">
                    <h2>Featured Products</h2>
                </div>

                <div class="row pb-3">
                    @if ($featuredProduct->isNotEmpty())
                        @foreach ($featuredProduct as $product)
                            @php
                                /*product_images: In product model we create product_images() method for create relationship*/
                                $productImages = $product->product_images->first();
                            @endphp
                            <div class="col-md-3">
                                <div class="card product-card">
                                    <div class="product-image position-relative">

                                        <a href="{{ route('front.product', $product->slug)}}" class="product-img">
                                            <td>
                                                @if (!empty($productImages))
                                                    <img class="card-img-top" src="{{ asset('uploads/product/small/' . $productImages->image) }}" class="img-thumbnail">
                                                @else
                                                    <img class="card-img-top" src="{{ asset('admin-assets/img/default-150x150.png') }}" class="img-thumbnail">
                                                @endif
                                            </td>
                                        </a>

                                        <a onclick="addToWishlist({{$product->id}})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>                                                      

                                        <div class="product-action">
                                            @if ($product->track_qty == 'Yes')
                                                @if ($product->qty > 0)
                                                    <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$product->id}});">
                                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                                    </a> 
                                                @else
                                                    <a class="btn btn-dark" href="javascript:void(0);">
                                                        Out Of Stock
                                                    </a> 
                                                @endif
                                            @else
                                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$product->id}});">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a> 
                                            @endif                       
                                        </div>

                                    </div>                        
                                    <div class="card-body text-center mt-3">

                                        <a class="h6 link" href="product.php">{{$product->title}}</a>

                                        <div class="price mt-2">

                                            <span class="h5"><strong>{{$product->price}}</strong></span>

                                            @if ($product->	compare_price > 0)
                                                <span class="h6 text-underline"><del>{{$product->compare_price}}</del></span>
                                            @endif

                                        </div>
                                    </div>                        
                                </div>                                               
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
    </section>

    <section class="section-4 pt-5">
            <div class="container">

                <div class="section-title">
                    <h2>Latest Products</h2>
                </div>

                <div class="row pb-3">
                    @if ($latestProduct->isNotEmpty())
                        @foreach ($latestProduct as $product)
                            @php
                                /*product_images: In product model we create product_images() method for create relationship*/
                                $productImages = $product->product_images->first();
                            @endphp
                            <div class="col-md-3">
                                <div class="card product-card">
                                    <div class="product-image position-relative">

                                        <a href="{{ route('front.product', $product->slug)}}" class="product-img">
                                            <td>
                                                @if (!empty($productImages))
                                                    <img class="card-img-top" src="{{ asset('uploads/product/small/' . $productImages->image) }}" class="img-thumbnail">
                                                @else
                                                    <img class="card-img-top" src="{{ asset('admin-assets/img/default-150x150.png') }}" class="img-thumbnail">
                                                @endif
                                            </td>
                                        </a>

                                        <a onclick="addToWishlist({{$product->id}})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>                                                      

                                        <div class="product-action">
                                            @if ($product->track_qty == 'Yes')
                                                @if ($product->qty > 0)
                                                    <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$product->id}});">
                                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                                    </a> 
                                                @else
                                                    <a class="btn btn-dark" href="javascript:void(0);">
                                                        Out Of Stock
                                                    </a> 
                                                @endif
                                            @else
                                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$product->id}});">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a> 
                                            @endif                       
                                        </div>

                                    </div>                        
                                    <div class="card-body text-center mt-3">

                                        <a class="h6 link" href="product.php">{{$product->title}}</a>

                                        <div class="price mt-2">

                                            <span class="h5"><strong>{{$product->price}}</strong></span>

                                            @if ($product->	compare_price > 0)
                                                <span class="h6 text-underline"><del>{{$product->compare_price}}</del></span>
                                            @endif

                                        </div>
                                    </div>                        
                                </div>                                               
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
    </section>

    <section class="section-4 pt-5">
            <div class="container">

                <div class="section-title">
                    <h2>Top Selling Products</h2>
                </div>

                <div class="row pb-3">
                    @if ($topSellingProduct->isNotEmpty())
                        @foreach ($topSellingProduct as $product)
                            @php
                                /*product_images: In product model we create product_images() method for create relationship*/
                                $productImages = $product->product_images->first();
                            @endphp
                            <div class="col-md-3">
                                <div class="card product-card">
                                    <div class="product-image position-relative">

                                        <a href="{{ route('front.product', $product->slug)}}" class="product-img">
                                            <td>
                                                @if (!empty($productImages))
                                                    <img class="card-img-top" src="{{ asset('uploads/product/small/' . $productImages->image) }}" class="img-thumbnail">
                                                @else
                                                    <img class="card-img-top" src="{{ asset('admin-assets/img/default-150x150.png') }}" class="img-thumbnail">
                                                @endif
                                            </td>
                                        </a>

                                        <a onclick="addToWishlist({{$product->id}})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>                            

                                        <div class="product-action">
                                            @if ($product->track_qty == 'Yes')
                                                @if ($product->qty > 0)
                                                    <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$product->id}});">
                                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                                    </a> 
                                                @else
                                                    <a class="btn btn-dark" href="javascript:void(0);">
                                                        Out Of Stock
                                                    </a> 
                                                @endif
                                            @else
                                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$product->id}});">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a> 
                                            @endif                       
                                        </div>

                                    </div>                        
                                    <div class="card-body text-center mt-3">

                                        <a class="h6 link" href="product.php">{{$product->title}}</a>

                                        <div class="price mt-2">

                                            <span class="h5"><strong>{{$product->price}}</strong></span>

                                            @if ($product->	compare_price > 0)
                                                <span class="h6 text-underline"><del>{{$product->compare_price}}</del></span>
                                            @endif

                                        </div>
                                    </div>                        
                                </div>                                               
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
    </section>
    
@endsection













