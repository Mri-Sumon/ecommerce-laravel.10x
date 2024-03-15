@extends('admin.layouts.app')
@section('content')

	<section class="content-header">					
		<div class="container-fluid my-2">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Settings</h1>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
        <div class="container-fluid">

    		<!-- show successfull message  -->
		    @include('admin.message')

            <form action="" name="settingForm" id="settingForm" method="post">
                <div class="row">

                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Header Section</h2>		
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="title">Link Title</label>
                                            <input type="text" name="title" id="title" class="form-control" placeholder="Link Title">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="icon">Link Icon</label>
                                            <input type="file" name="icon" id="icon" class="form-control">
                                        </div>
                                    </div>	

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="logo">Company Logo</label>
                                            <input type="file" name="logo" id="logo" class="form-control">
                                        </div>
                                    </div>	

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name">Company Name</label>
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Company Name">
                                        </div>
                                    </div>	
                                    
                                </div>
                            </div>							
                        </div>
                    </div>

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
                                        <option value="">Electronics</option>
                                        <option value="">Clothes</option>
                                        <option value="">Furniture</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="category">Sub category</label>
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        <option value="">Mobile</option>
                                        <option value="">Home Theater</option>
                                        <option value="">Headphones</option>
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Apple</option>
                                        <option value="">Vivo</option>
                                        <option value="">HP</option>
                                        <option value="">Samsung</option>
                                        <option value="">DELL</option>
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
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Footer Section</h2>		
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                            <p></p>	
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="slug">Slug</label>
                                            <input readonly type="text" name="slug" id="slug" class="form-control" placeholder="Slug">
                                            <p></p>	
                                        </div>
                                    </div>	
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="1">Active</option>
                                                <option value="0">Block</option>
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sort">Sort</label>
                                            <input type="number" name="sort" id="sort" class="form-control" placeholder="sort">
                                            <p></p>	
                                        </div>
                                    </div>

                                </div>
                            </div>							
                        </div>
                    </div>

                </div>
                
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary">Create</button>
                    <a href="products.html" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>

            </form>
            
		</div>
	</section>
@endsection



@section('customJs')

	<script>
		function deleteBrand(brandId){
			
			var url='{{ route("brands.delete","ID") }}';
			var newUrl = url.replace("ID",brandId);

			if(confirm("Are you sure you want to delete")){
				$.ajax({
					url: newUrl,
					type: 'DELETE',
					data: {},
					dataType: 'json',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function(response) {
						window.location.href = "{{ route('brands.index') }}";
					}
				});
			}
		}
	</script>

@endsection












