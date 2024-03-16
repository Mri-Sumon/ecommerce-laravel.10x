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

                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Announcement</h2>		
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="importantUpdates">Important updates</label>
                                            <input type="text" name="importantUpdates" id="importantUpdates" class="form-control" placeholder="Important updates">
                                        </div>
                                    </div>
                                </div>
                            </div>							
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="card card-body">
                            <h2 class="h4 mb-3">Image Section</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="createSection">Create Section</label>
                                        <div class="d-flex">
                                            <input type="text" name="createSection" id="createSection" class="form-control" placeholder="Create section">
                                            <button type="submit" class="btn btn-primary ms-2">Create</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="selectSection">Select Section</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="1">Select section</option>
                                            <option value="1">Section-01</option>
                                            <option value="2">Section-02</option>
                                            <option value="3">Section-03</option>
                                            <option value="4">Section-04</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="card mb-3">
                                        <div class="card-body">						
                                            <div id="image" class="dropzone dz-clickable">
                                                <div class="dz-message needsclick">    
                                                    <br>Drop files here or click to upload.<br><br>                                            
                                                </div>
                                            </div>
                                        </div>	                                                                      
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="imageFirstTitle">First Title</label>
                                        <input type="text" name="imageFirstTitle" id="imageFirstTitle" class="form-control" placeholder="Image first title">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="imageSecondTitle">Second Title</label>
                                        <input type="text" name="imageSecondTitle" id="imageSecondTitle" class="form-control" placeholder="Image second title">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Image with Text Section</h2>							
                                <div class="row">

                                    <div class="col-md-6">
                                        <label for="image">Create Section</label>
                                        <div class="mb-3 d-flex">
                                            <input type="text" name="createSection" id="createSection" class="form-control" placeholder="Create Section">
                                            <button type="submit" class="btn btn-primary ms-2">Create</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="image">Select Section</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="1">Select section</option>
                                                <option value="1">Section-01</option>
                                                <option value="2">Section-02</option>
                                                <option value="3">Section-03</option>
                                                <option value="4">Section-04</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="image">Image</label>
                                            <input type="file" name="image" id="image" class="form-control">	
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
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="card card-body">
                            <h2 class="h4 mb-3">Video Section</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="createSection">Create Section</label>
                                        <div class="d-flex">
                                            <input type="text" name="createSection" id="createSection" class="form-control" placeholder="Create section">
                                            <button type="submit" class="btn btn-primary ms-2">Create</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="selectSection">Select Section</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="1">Select section</option>
                                            <option value="1">Section-01</option>
                                            <option value="2">Section-02</option>
                                            <option value="3">Section-03</option>
                                            <option value="4">Section-04</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="videoLink">Apply link</label>
                                        <input type="text" name="videoLink" id="videoLink" class="form-control" placeholder="Apply link here">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="videoFirstTitle">First Title</label>
                                        <input type="text" name="videoFirstTitle" id="videoFirstTitle" class="form-control" placeholder="Video first title">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="videoSecondTitle">Second Title</label>
                                        <input type="text" name="videoSecondTitle" id="videoSecondTitle" class="form-control" placeholder="Video second title">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="col-md-12 mb-3">
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Social media</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="mb-3">
                                                <label for="facebook">Facebook</label>
                                                <input type="text" name="facebook" id="facebook" class="form-control" placeholder="Facebook Link">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="mb-3">
                                                <label for="whatsapp">Whatsapp</label>
                                                <input type="text" name="whatsapp" id="whatsapp" class="form-control" placeholder="Whatsapp Number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="mb-3">
                                                <label for="twitter">Twitter</label>
                                                <input type="text" name="twitter" id="twitter" class="form-control" placeholder="Twitter Link">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="mb-3">
                                                <label for="instagram">Instagram</label>
                                                <input type="text" name="instagram" id="instagram" class="form-control" placeholder="Instagram Link">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="mb-3">
                                                <label for="linkedin">Linkedin</label>
                                                <input type="text" name="linkedin" id="linkedin" class="form-control" placeholder="Linkedin Link">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="mb-3">
                                                <label for="pinterest">Pinterest</label>
                                                <input type="text" name="pinterest" id="pinterest" class="form-control" placeholder="Pinterest Link">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                              
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Company Location</h2>
                                <div class="mb-3">
                                    <div class="mb-3">
                                        <label for="map">Google Map</label>
                                        <input type="text" name="map" id="map" class="form-control" placeholder="Company Location">
                                    </div>
                                </div>
                            </div>
                        </div>                              
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Office Hours</h2>
                                <div class="mb-3">
                                    <div class="mb-3">
                                        <label for="officeHours">Day & Time</label>
                                        <input type="text" name="officeHours" id="officeHours" class="form-control" placeholder="Day & Time">
                                    </div>
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
                                            <label for="address">Company Address</label>
                                            <input type="text" name="address" id="address" class="form-control" placeholder="Company address">
                                            <p></p>	
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact">Contact Number</label>
                                            <input type="number" name="contact" id="contact" class="form-control" placeholder="Contact">
                                            <p></p>	
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email">Email</label>
                                            <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                                            <p></p>	
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="copyright">Copyright Text</label>
                                            <input type="text" name="copyright" id="copyright" class="form-control" placeholder="Copyright Text">
                                            <p></p>	
                                        </div>
                                    </div>

                                </div>
                            </div>							
                        </div>
                    </div>

                </div>
                
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary">Update</button>
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












