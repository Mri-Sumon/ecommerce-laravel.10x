@extends('admin.layouts.app')
@section('content')
    	<!-- Content Header (Page header) -->
	<section class="content-header">					
		<div class="container-fluid my-2">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Edit Product</h1>
				</div>
				<div class="col-sm-6 text-right">
					<a href="{{route('products.index')}}" class="btn btn-primary">Back</a>
				</div>
			</div>
		</div>
		<!-- /.container-fluid -->
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Default box -->
        <form action="" name="productForm" id="productForm" method="post">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">								
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" value="{{$product->title}}" class="form-control" placeholder="Title">
                                            <p class="error"></p>	
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug">Slug</label>
                                            <input readonly type="text" name="slug" id="slug" value="{{$product->slug}}" class="form-control" placeholder="Slug">	
                                            <p class="error"></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description">
                                                {{$product->description}}
                                            </textarea>
                                        </div>
                                    </div> 

                                </div>
                            </div>	                                                                      
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>								
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">    
                                        <br>Drop files here or click to upload.<br><br>                                            
                                    </div>
                                </div>
                            </div>	                                                                      
                        </div>

                        <div class="row" id="product-gallery">
                            @if(!empty($productImages))
                                @foreach ($productImages as $image)
                                    <div class="col-md-3" id="image-row-{{$image->id}}">
                                        <div class="card">
                                            <input type="hidden" name="image_array[]" value="{{$image->id}}">
                                            <img src="{{asset('/uploads/product/small/'.$image->image)}}" class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <a href="javascript:void(0);" onclick="deleteImage({{$image->id}})" class="btn btn-danger">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>								
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" name="price" id="price" value="{{$product->price}}" class="form-control" placeholder="Price">	
                                            <p class="error"></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" name="compare_price" id="compare_price" value="{{$product->compare_price}}" class="form-control" placeholder="Compare Price">
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                            </p>	
                                        </div>
                                    </div>                                            
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>								
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" name="sku" id="sku" value="{{$product->sku}}" class="form-control" placeholder="sku">	
                                            <p class="error"></p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" value="{{$product->barcode}}" class="form-control" placeholder="Barcode">	
                                        </div>
                                    </div>   

                                    <div class="col-md-12">

                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <!-- when track_qty is unchecked that time this input field worked. -->
                                                <input type="hidden" name="track_qty" value="No">
                                                <input  type="checkbox" id="track_qty" name="track_qty" value="Yes" {{($product->track_qty == 'Yes') ? 'checked' : ''}} class="custom-control-input" >
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                <p class="error"></p>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty" value="{{$product->qty}}" class="form-control" placeholder="Qty">	
                                        </div>

                                    </div>  

                                </div>
                            </div>	                                                                      
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option {{ ($product->status == 1) ? 'selected' : ''}} value="1">Active</option>
                                        <option {{ ($product->status == 0) ? 'selected' : ''}} value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="card">
                            <div class="card-body">

                                <h2 class="h4  mb-3">Product category</h2>
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a category</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option {{ ($product->category_id == $category->id) ? 'selected' : ''}} value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="error"></p>
                                </div>

                                <div class="mb-3">
                                    <label for="sub_category">Sub category</label>
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        <option value="">Select a Sub Category</option>
                                        @if ($subCategories->isNotEmpty())
                                            @foreach ($subCategories as $subCategory)
                                                <option {{ ($product->sub_category_id == $subCategory->id) ? 'selected' : ''}} value="{{$subCategory->id}}">{{$subCategory->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                            </div>
                        </div> 
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="brand" id="brand" class="form-control">
                                        <option value="">Select a brand</option>
                                        @if ($brands->isNotEmpty())
                                            @foreach ($brands as $brand)
                                                <option {{ ($product->brand_id == $brand->id) ? 'selected' : ''}} value="{{$brand->id}}">{{$brand->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Featured product</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option {{ ($product->is_featured == 'No') ? 'selected' : ''}} value="No">No</option>
                                        <option {{ ($product->is_featured == 'Yes') ? 'selected' : ''}} value="Yes">Yes</option>                                                
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                                                        
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Top selling product</h2>
                                <div class="mb-3">
                                    <select name="is_top_selling" id="is_top_selling" class="form-control">
                                        <option {{ ($product->is_top_selling == 'No') ? 'selected' : ''}} value="No">No</option>
                                        <option {{ ($product->is_top_selling == 'Yes') ? 'selected' : ''}} value="Yes">Yes</option>                                                
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                                                        
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Sort</h2>
                                <div class="mb-3">
                                    <input type="number" name="sort" id="sort" value="{{$product->sort}}" class="form-control" placeholder="Sort">	
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{route('products.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
		<!-- /.card -->
	</section>
	<!-- /.content -->
@endsection



@section('customJs')
    <script>

        $("#productForm").submit(function(event){
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{route("products.update",$product->id)}}',
                type:'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled', false);

                    if(response["status"]==true){

                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type='text'], select, input[type='number']").removeClass('is-invalid');
                        window.location.href="{{route('products.index')}}"

                    }else{

                        var errors = response['errors'];
                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type='text'], select, input[type='number']").removeClass('is-invalid');
                        $.each(errors, function(key, value){
                            $(`#${key}`)
                            .addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(value);
                        });
                    }
                }, error:function(jqXHR,exception){
                    console.log("Something went wrong");
                }
            })
        });


        // Create slug 
        $('#title').change(function(){
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{route("getSlug")}}',
                type: 'get',
                data: {title: element.val()},
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled', false);
                    if(response["status"] == true){
                        $("#slug").val(response["slug"]);
                    }
                }
            });
        });


        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            url: "{{ route('product-images.update') }}",
            maxFiles: 10,
            paramName: 'image',
            params: { 'product_id': '{{$product->id}}' },
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            success: function(file, response) {
                var html = `<div class="col-md-3" id="image-row-${response.image_id}">
                    <div class="card">
                        <input type="hidden" name="image_array[]" value="${response.image_id}">
                        <img src="${response.image_path}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <a href="javascript:void(0);" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>`;
                $("#product-gallery").append(html);
            },
            complete: function(file) {
                this.removeFile(file);
            }
        });

        function deleteImage(imageId) {
            // Delete image from database when the delete button is pressed
            if (confirm("Are you sure you want to delete the image?")) {
                $.ajax({
                    url: '{{ route("product-images.destroy", ["imageId" => ":imageId"]) }}'.replace(':imageId', imageId),
                    type: 'DELETE',
                    success: function(response) {
                        if (response.status == true) {
                            alert(response.message);
                            // Remove the image from the DOM
                            $('#image-row-' + imageId).remove();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            }
        }


        $("#category").change(function(){
            var category_id = $(this).val();
            $.ajax({
                url: '{{ route("products-subCategories.index") }}',
                type: 'GET', 
                data: { category_id: category_id }, 
                dataType: 'json',
                success: function(response) {
                    $("#sub_category").find("option").not(":first").remove();
                    $.each(response["subCategories"], function(key, item){
                        $("#sub_category").append(`<option value="${item.id}">${item.name}</option>`);
                    });
                },
                error: function(xhr, status, error) {
                    console.log("Someting went wrong");
                }
            });
        });

        
    </script>
@endsection







