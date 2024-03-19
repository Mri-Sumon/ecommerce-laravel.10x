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
                                            <input type="text" value="{{$settings->title}}" name="title" id="title" class="form-control" placeholder="Link Title">
                                        </div>
                                        <div>
                                            <input type="hidden" id="icon_id" name="icon_id" value="">
                                            <label for="icon">Icon</label>
                                            <div id="icon" class="dropzone dz-clickable">
                                                <div class="dz-message needsclick">
                                                    <br>Drop files here or click to upload.<br><br>
                                                </div>
                                            </div>
                                            <div class="mt-3 mb-5">
                                                <img 
                                                    @if (settingData()->icon_id != NULL)
                                                        src="{{ asset('uploads/setting/1.jpg') }}"
                                                    @else
                                                        src="{{ asset('uploads/setting/boxed-bg.jpg') }}"
                                                    @endif alt="" width="80" height="80"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div>
                                            <div class="mb-3">
                                                <label for="companyName">Company Name</label>
                                                <input type="text" value="{{$settings->companyName}}" name="companyName" id="companyName" class="form-control" placeholder="Company Name">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <input type="hidden" id="logo_id" name="logo_id" value="">
                                            <label for="logo">Logo</label>
                                            <div id="logo" class="dropzone dz-clickable">
                                                <div class="dz-message needsclick">
                                                    <br>Drop files here or click to upload.<br><br>
                                                </div>
                                            </div>
                                            <div class="mt-3 mb-5">
                                                <img 
                                                    @if (settingData()->icon_id != NULL)
                                                        src="{{ asset('uploads/setting/logo_1.jpg') }}"
                                                    @else
                                                        src="{{ asset('uploads/setting/boxed-bg.jpg') }}"
                                                    @endif alt="" width="100" 
                                                >
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="col-md-6">
                                        <input type="hidden" id="adminPicture_id" name="adminPicture_id" value="">
                                        <label for="adminPicture">Admin Picture</label>
                                        <div id="adminPicture" class="dropzone dz-clickable">
                                            <div class="dz-message needsclick">
                                                <br>Drop files here or click to upload.<br><br>
                                            </div>
                                        </div>
                                        <div class="mt-3 mb-5">
                                            <img 
                                                @if (settingData()->icon_id != NULL)
                                                    src="{{ asset('uploads/setting/1.jpg') }}"
                                                @else
                                                    src="{{ asset('uploads/setting/admin_picture_1.jpg') }}"
                                                @endif alt="" width="80" height="80"
                                            >
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
                                            <input type="text" value="{{$settings->importantUpdates}}" name="importantUpdates" id="importantUpdates" class="form-control" placeholder="Important updates">
                                        </div>
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
                                                <input type="text" value="{{$settings->facebook}}" name="facebook" id="facebook" class="form-control" placeholder="Facebook Link">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="mb-3">
                                                <label for="whatsapp">Whatsapp</label>
                                                <input type="text" value="{{$settings->whatsapp}}" name="whatsapp" id="whatsapp" class="form-control" placeholder="Whatsapp Number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="mb-3">
                                                <label for="twitter">Twitter</label>
                                                <input type="text" value="{{$settings->twitter}}" name="twitter" id="twitter" class="form-control" placeholder="Twitter Link">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="mb-3">
                                                <label for="instagram">Instagram</label>
                                                <input type="text" value="{{$settings->instagram}}" name="instagram" id="instagram" class="form-control" placeholder="Instagram Link">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="mb-3">
                                                <label for="linkedin">Linkedin</label>
                                                <input type="text" value="{{$settings->linkedin}}" name="linkedin" id="linkedin" class="form-control" placeholder="Linkedin Link">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="mb-3">
                                                <label for="pinterest">Pinterest</label>
                                                <input type="text" value="{{$settings->pinterest}}" name="pinterest" id="pinterest" class="form-control" placeholder="Pinterest Link">
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
                                        <input type="text" value="{{$settings->map}}" name="map" id="map" class="form-control" placeholder="Company Location">
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
                                        <input type="text" value="{{$settings->officeHours}}" name="officeHours" id="officeHours" class="form-control" placeholder="Day & Time">
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
                                            <textarea name="address" id="address" cols="30" rows="10" class="form-control summernote" placeholder="Company Address">
                                                {{$settings->address}}
                                            </textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="email">Email</label>
                                            <input type="email" value="{{$settings->email}}" name="email" id="email" class="form-control" placeholder="Email">
                                            <p></p>	
                                        </div>

                                        <div class="mb-3">
                                            <label for="contact">Contact Number</label>
                                            <input type="number" value="{{$settings->contact}}" name="contact" id="contact" class="form-control" placeholder="Contact">
                                            <p></p>	
                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="mb-3">
                                            <input type="hidden" id="footerLogo_id" name="footerLogo_id" value="">
                                            <label for="footerLogo">Logo</label>
                                            <div id="footerLogo" class="dropzone dz-clickable">
                                                <div class="dz-message needsclick">
                                                    <br>Drop files here or click to upload.<br><br>
                                                </div>
                                            </div>
                                            <div class="mt-3 mb-5">
                                                <img 
                                                    @if (settingData()->icon_id != NULL)
                                                        src="{{ asset('uploads/setting/footer_logo_1.jpg') }}"
                                                    @else
                                                        src="{{ asset('uploads/setting/boxed-bg.jpg') }}"
                                                    @endif alt="" width="100" 
                                                >
                                            </div>
                                        </div>
                                    </div>
                                                                      
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="copyright">Copyright Text</label>
                                            <input type="text" value="{{$settings->copyright}}" name="copyright" id="copyright" class="form-control" placeholder="Copyright Text">
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

        //Stop auto discover image 
        Dropzone.autoDiscover = false;

        //Dropzone for icon
        const iconDropzone = new Dropzone("#icon", {
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{route('temp-images.create')}}",
            maxFiles: 1,
            paramName: 'icon',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            success: function(file, response){
                $("#icon_id").val(response.image_id);
            }
        });


        //Dropzone for logo
        const logoDropzone = new Dropzone("#logo", {
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{route('temp-images.create')}}",
            maxFiles: 1,
            paramName: 'logo',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            success: function(file, response){
                $("#logo_id").val(response.image_id);
            }
        });

        //Dropzone for admin picture
        const adminPictureDropzone = new Dropzone("#adminPicture", {
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{route('temp-images.create')}}",
            maxFiles: 1,
            paramName: 'adminPicture',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            success: function(file, response){
                $("#adminPicture_id").val(response.image_id);
            }
        });


        //Dropzone for footer Logo
        const footerLogoDropzone = new Dropzone("#footerLogo", {
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{ route('temp-images.create') }}",
            maxFiles: 1,
            paramName: 'footerLogo',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg, image/png, image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            success: function(file, response){
                $("#footerLogo_id").val(response.image_id);
            },
        });


        //send form data to route
        $("#settingForm").submit(function(event){

            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route("settings.update", 1) }}',
                type:'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){

                    $("button[type=submit]").prop('disabled', false);

                    if(response["status"]==true){

                        window.location.href="{{route('settings.settings')}}"
                        
                    }
                }, error:function(jqXHR,exception){
                    console.log("Something went wrong");
                }
            })
        });
        
    </script>

@endsection












