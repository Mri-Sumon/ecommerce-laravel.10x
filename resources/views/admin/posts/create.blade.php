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
                        <div class="card card-body">
                            <h2 class="h4 mb-3">Image Section</h2>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="selectImageSection">Select Section</label>
                                        <select name="selectImageSection" id="selectImageSection" class="form-control">
                                            <option value="1">Select section</option>
                                            @if ($sections->isNotEmpty())
                                                @foreach ($sections as $section)
                                                    <option value="{{$section->slug}}">{{ $section->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="imageFirstTitle">First Title</label>
                                        <input type="text" name="imageFirstTitle" id="imageFirstTitle" class="form-control" placeholder="Image first title">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <input type="hidden" id="imageSection_id" name="imageSection_id" value="">
                                    <label for="imageSection">Image Section</label>
                                    <div id="imageSection" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br>Drop files here or click to upload.<br><br>
                                        </div>
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
                                        <div class="mb-3">
                                            <label for="selectImgWithTextSection">Select Section</label>
                                            <select name="selectImgWithTextSection" id="selectImgWithTextSection" class="form-control">
                                                <option value="1">Select section</option>
                                                @if ($sections->isNotEmpty())
                                                    @foreach ($sections as $section)
                                                        <option value="{{$section->slug}}">{{ $section->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div>
                                            <input type="hidden" id="imageWithTextSection_id" name="imageWithTextSection_id" value="">
                                            <label for="imageWithTextSection">Image</label>
                                            <div id="imageWithTextSection" class="dropzone dz-clickable">
                                                <div class="dz-message needsclick">
                                                    <br>Drop files here or click to upload.<br><br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                                                        
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="form-control summernote" placeholder="Description"></textarea>
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
                                        <label for="selectVideoSection">Select Section</label>
                                        <select name="selectVideoSection" id="selectVideoSection" class="form-control">
                                            <option value="1">Select section</option>
                                            @if ($sections->isNotEmpty())
                                                @foreach ($sections as $section)
                                                    <option value="{{$section->slug}}">{{ $section->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
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


        //Dropzone for image section
        const imageSectionDropzone = new Dropzone("#imageSection", {
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{ route('temp-images.create') }}",
            maxFiles: 1,
            paramName: 'imageSection',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg, image/png, image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            success: function(file, response){
                $("#imageSection_id").val(response.image_id);
            },
        });


        //Dropzone for image with text section
        const imageWithTextSectionDropzone = new Dropzone("#imageWithTextSection", {
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{ route('temp-images.create') }}",
            maxFiles: 1,
            paramName: 'imageWithTextSection',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg, image/png, image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            success: function(file, response){
                $("#imageWithTextSection_id").val(response.image_id);
            },
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












