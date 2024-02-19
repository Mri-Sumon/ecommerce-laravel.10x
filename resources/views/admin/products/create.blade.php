@extends('admin.layouts.app')
@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">					
		<div class="container-fluid my-2">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Create Product</h1>
				</div>
				<div class="col-sm-6 text-right">
					<a href="products.html" class="btn btn-primary">Back</a>
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
                                            <input type="text" name="title" id="title" class="form-control" placeholder="Title">	
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug">Slug</label>
                                            <input readonly type="text" name="slug" id="slug" class="form-control" placeholder="Slug">	
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description"></textarea>
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
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>								
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" name="price" id="price" class="form-control" placeholder="Price">	
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price">
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
                                            <input type="text" name="sku" id="sku" class="form-control" placeholder="sku">	
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode">	
                                        </div>
                                    </div>   
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" checked>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty">	
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
                                        <option value="1">Active</option>
                                        <option value="0">Block</option>
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
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="sub_category">Sub category</label>
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        <option value="">Select a Sub Category</option>
                                        <!-- Additional options can be dynamically added here using JavaScript -->
                                    </select>
                                </div>

                            </div>
                        </div> 
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                    <option value="">Select a brand</option>
                                        @if ($brands->isNotEmpty())
                                            @foreach ($brands as $brandd)
                                                <option value="{{$brandd->id}}">{{$brandd->name}}</option>
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
                                    <select name="status" id="status" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>                                                
                                    </select>
                                </div>
                            </div>
                        </div>
                                                        
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Top selling product</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>                                                
                                    </select>
                                </div>
                            </div>
                        </div>
                                                        
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Sort</h2>
                                <div class="mb-3">
                                    <input type="number" name="sort" id="sort" class="form-control" placeholder="Sort">	
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary">Create</button>
                    <a href="products.html" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
		<!-- /.card -->
	</section>
	<!-- /.content -->
@endsection


@section('customJs')
    <script>

        //send form data to route, Get validation message
        // $("#productForm").submit(function(event){

        //     event.preventDefault();

        //     var element = $(this);

        //     $("button[type=submit]").prop('disabled', true);

        //     $.ajax({
        //         url: '{{route("products.store")}}',
        //         type:'post',
        //         data: element.serializeArray(),
        //         dataType: 'json',
        //         success: function(response){

        //             $("button[type=submit]").prop('disabled', false);
        //             if(response["status"]==true){
        //                 window.location.href="{{route('categories.index')}}"

        //                 $("#name").removeClass('is-invalid')
        //                 .siblings('p')
        //                 .removeClass('invalid-feedback')
        //                 .html("");
                    
        //                 $("#slug").removeClass('is-invalid')
        //                 .siblings('p')
        //                 .removeClass('invalid-feedback')
        //                 .html("");
        //             }else{
        //                 var errors = response['errors'];
        //                 if(errors['name']){
        //                     $("#name").addClass('is-invalid')
        //                     .siblings('p')
        //                     .addClass('invalid-feedback')
        //                     .html(errors['name']);
        //                 }else{
        //                     $("#name").removeClass('is-invalid')
        //                     .siblings('p')
        //                     .removeClass('invalid-feedback')
        //                     .html("");
        //                 }

        //                 if(errors['slug']){
        //                     $("#slug").addClass('is-invalid')
        //                     .siblings('p')
        //                     .addClass('invalid-feedback')
        //                     .html(errors['slug']);
        //                 }else{
        //                     $("#slug").removeClass('is-invalid')
        //                     .siblings('p')
        //                     .removeClass('invalid-feedback')
        //                     .html("");
        //                 }
        //             }

        //         }, error:function(jqXHR,exception){
        //             console.log("Something went wrong");
        //         }
        //     })
        // });

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

        //image upload using javascript dropzone package
        // Dropzone.autoDiscover = false;
        // const dropzone = $("#image").dropzone({
        //     init: function() {
        //         this.on('addedfile', function(file) {
        //             if (this.files.length > 1) {
        //                 this.removeFile(this.files[0]);
        //             }
        //         });
        //     },
        //     url:  "{{route('temp-images.create')}}",
        //     maxFiles: 1,
        //     paramName: 'image',
        //     addRemoveLinks: true,
        //     acceptedFiles: "image/jpeg,image/png,image/gif",
            
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }, success: function(file, response){
        //         $("#image_id").val(response.image_id);
        //     }
        // });


        //category submit to ProductSubCategoryController, because when we select category, subcategory will automatically be selected.
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












