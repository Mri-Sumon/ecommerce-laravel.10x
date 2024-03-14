@extends('front.layouts.app')
@section('content')




    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.shop')}}">Shop</a></li>
                    <li class="breadcrumb-item">{{$product->title}}</li>
                </ol>
            </div>
        </div>
    </section>






    <section class="section-7 pt-3 mb-3">
        <div class="container">
            <div class="row ">

                @include('front.account.common.message')

                <!-- Sliders  -->
                <div class="col-md-5">

                    <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">

                        <div class="carousel-inner bg-light">

                            @if ($product->product_images)
                                @foreach ($product->product_images as $key => $productImage)
                                    <!-- First index image will be set as active -->
                                    <div class="carousel-item {{($key == 0) ? 'active' : ''}}">
                                        <img class="w-100 h-100" src="{{asset('/uploads/product/large/'.$productImage->image)}}" alt="Image">
                                    </div>
                                @endforeach
                            @endif

                        </div>

                        <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                            <i class="fa fa-2x fa-angle-left text-dark"></i>
                        </a>

                        <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                            <i class="fa fa-2x fa-angle-right text-dark"></i>
                        </a>

                    </div>
                </div>


                <div class="col-md-7">
                    <div class="bg-light right">

                        <h1>{{$product->title}}</h1>

                        <div class="d-flex mb-3">
                            <div class="text-primary mr-2">
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star-half-alt"></small>
                                <small class="far fa-star"></small>
                            </div>
                            <small class="pt-1">(99 Reviews)</small>
                        </div>

                        @if ($product->compare_price>0)
                            <h2 class="price text-secondary"><del>{{$product->compare_price}}</del></h2>
                        @endif

                        <h2 class="price ">{{$product->price}}</h2>

                        <p>{!! $product->short_description !!}</p>

                        @if ($product->track_qty == 'Yes')
                            @if ($product->qty > 0)
                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$product->id}});">
                                    <i class="fa fa-shopping-cart"></i>&nbsp; Add To Cart
                                </a> 
                            @else
                                <a class="btn btn-dark" href="javascript:void(0);">
                                    Out Of Stock
                                </a> 
                            @endif
                        @else
                            <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$product->id}});">
                                <i class="fa fa-shopping-cart"></i>&nbsp; Add To Cart
                            </a> 
                        @endif                       

                    </div>
                </div> 


                <div class="col-md-12 mt-5">
                    <div class="bg-light">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" aria-selected="false">Shipping & Returns</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                {!! $product->description !!}
                            </div>
                            <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                                {!! $product->shipping_returns !!}
                            </div>

                            <!-- Customer review start -->
                            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                <div class="col-md-8">

                                    <form action="" name="productRatingForm" id="productRatingForm" method="post">
                                        <div class="row">

                                            <h3 class="h4 pb-3">Write a Review</h3>

                                            <div class="form-group col-md-6 mb-3">
                                                <label for="username">Name</label>
                                                <input type="text" class="form-control" name="username" id="username" placeholder="Name">
                                                <p></p>
                                            </div>

                                            <div class="form-group col-md-6 mb-3">
                                                <label for="email">Email</label>
                                                <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                                                <p></p>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="rating">Rating</label>
                                                <br>
                                                <div class="rating" style="width: 10rem">
                                                    <input id="rating-5" type="radio" name="rating" value="5"/><label for="rating-5"><i class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-4" type="radio" name="rating" value="4"  /><label for="rating-4"><i class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-3" type="radio" name="rating" value="3"/><label for="rating-3"><i class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-2" type="radio" name="rating" value="2"/><label for="rating-2"><i class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-1" type="radio" name="rating" value="1"/><label for="rating-1"><i class="fas fa-3x fa-star"></i></label>
                                                </div>
                                                <p class="product-rating-error text-danger"></p>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="comment">How was your overall experience?</label>
                                                <textarea name="comment"  id="comment" class="form-control" cols="30" rows="10" placeholder="How was your overall experience?"></textarea>
                                                <p></p>
                                            </div>

                                            <div>
                                                <button class="btn btn-dark">Submit</button>
                                            </div>
                                            
                                        </div>
                                    </form>

                                </div>
                                <div class="col-md-12 mt-5">
                                    <div class="overall-rating mb-3">
                                        <div class="d-flex">
                                            <h1 class="h3 pe-3">4.0</h1>
                                            <div class="star-rating mt-2" title="70%">
                                                <div class="back-stars">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    
                                                    <div class="front-stars" style="width: 70%">
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>  
                                            <div class="pt-2 ps-2">(03 Reviews)</div>
                                        </div>
                                        
                                    </div>
                                    <div class="rating-group mb-4">
                                    <span> <strong>Mohit Singh </strong></span>
                                        <div class="star-rating mt-2" title="70%">
                                            <div class="back-stars">
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                
                                                <div class="front-stars" style="width: 70%">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>   
                                        <div class="my-3">
                                            <p>I went with the blue model for my new apartment and an very pleased with the purchase. I'm definitely someone not used to paying this much for furniture, and I am also anxious about buying online, but I am very happy with the quality of this couch. For me, it is the perfect mix of cushy firmness, and it arrived defect free. It really is well made and hopefully will be my main couch for a long time. I paid for the extra delivery & box removal, and had an excellent experience as well. I do tend move my own furniture, but with an online purchase this expensive, that helped relieved my anxiety about having a item this big open up in my space without issues. If you need a functional sectional couch and like the feel of leather, this really is a great choice.
                                        </p>
                                        </div>
                                    </div>

                                    <div class="rating-group mb-4">
                                        <span class="author"><strong>Mohit Singh </strong></span>
                                        <div class="star-rating mt-2" >
                                            <div class="back-stars">
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                
                                                <div class="front-stars" style="width: 100%">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>   
                                        <div class="my-3">
                                            <p>I went with the blue model for my new apartment and an very pleased with the purchase. I'm definitely someone not used to paying this much for furniture, and I am also anxious about buying online, but I am very happy with the quality of this couch. For me, it is the perfect mix of cushy firmness, and it arrived defect free. It really is well made and hopefully will be my main couch for a long time. I paid for the extra delivery & box removal, and had an excellent experience as well. I do tend move my own furniture, but with an online purchase this expensive, that helped relieved my anxiety about having a item this big open up in my space without issues. If you need a functional sectional couch and like the feel of leather, this really is a great choice.
                                        </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Customer review end -->

                        </div>
                    </div>
                </div>

            </div>           
        </div>
    </section>






    @if (!empty($relatedProducts))
        <section class="pt-5 section-8">
            <div class="container">
    
                <div class="section-title">
                    <h2>Related Products</h2>
                </div> 
    
                <div class="col-md-12">
                    <div id="related-products" class="carousel">
                        @foreach ($relatedProducts as $relatedProduct)
                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    @php
                                        /*product_images: In product model we create product_images() method for create relationship*/
                                        $productImages = $relatedProduct->product_images->first();
                                    @endphp
                                    <a href="{{ route('front.product', $product->slug)}}" class="product-img">
                                        @if (!empty($productImages))
                                            <img class="card-img-top" src="{{ asset('uploads/product/small/' . $productImages->image) }}" class="img-thumbnail">
                                        @else
                                            <img class="card-img-top" src="{{ asset('admin-assets/img/default-150x150.png') }}" class="img-thumbnail">
                                        @endif
                                    </a>


                                    <a onclick="addToWishlist({{$relatedProduct->id}})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>                            


                                    <div class="product-action">
                                        @if ($relatedProduct->track_qty == 'Yes')
                                            @if ($relatedProduct->qty > 0)
                                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$relatedProduct->id}});">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a> 
                                            @else
                                                <a class="btn btn-dark" href="javascript:void(0);">
                                                    Out Of Stock
                                                </a> 
                                            @endif
                                        @else
                                            <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{$relatedProduct->id}});">
                                                <i class="fa fa-shopping-cart"></i> Add To Cart
                                            </a> 
                                        @endif                       
                                    </div>


                                </div>
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link" href="">{{$relatedProduct->title}}</a>
                                    <div class="price mt-2">
                                        <span class="h5"><strong>{{$relatedProduct->price}}</strong></span>
                                        @if ($relatedProduct->compare_price > 0)
                                            <span class="h6 text-underline"><del>{{$relatedProduct->compare_price}}</del></span>
                                        @endif
                                    </div>
                                </div>
                            </div> 
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

@endsection



@section('customJs')
    <script type="text/javascript">

        //customer review
        $("#productRatingForm").submit(function(event){

            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{route("front.saveRating",$product->id)}}',
                type:'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){

                    $("button[type=submit]").prop('disabled', false);

                    if(response["status"]==true){

                        window.location.href="{{route('front.product',$product->slug)}}"

                        $("#username").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");
                    
                        $("#email").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");
                    
                        $("#comment").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");
                    
                        $(".product-rating-error")
                        .removeClass('is-invalid')
                        .removeClass('invalid-feedback')
                        .html("");

                    }else{
                        var errors = response['errors'];
                        if(errors['username']){
                            $("#username").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors['username']);
                        }else{
                            $("#username").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");
                        }

                        if(errors['email']){
                            $("#email").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors['email']);
                        }else{email
                            $("#slug").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");
                        }

                        if(errors['comment']){
                            $("#comment").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors['comment']);
                        }else{
                            $("#comment").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");
                        }

                        if(errors['rating']){
                            $(".product-rating-error")
                            .html(errors['rating']);
                        }else{
                            $(".product-rating-error")
                            .html("");
                        }

                    }

                }, error:function(jqXHR,exception){
                    console.log("Something went wrong");
                }
            })
        });

    </script>
@endsection

















