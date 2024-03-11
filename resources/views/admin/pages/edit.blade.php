@extends('admin.layouts.app')
@section('content')

    <section class="content-header">					
		<div class="container-fluid my-2">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Edit Page</h1>
				</div>
				<div class="col-sm-6 text-right">
					<a href="{{route('pages.index')}}" class="btn btn-primary">Back</a>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="container-fluid">

            <form action="" id="pageForm" name="pageForm" method="post">
                <div class="card">
                    <div class="card-body">								
                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" value="{{$page->name}}" name="name" id="name" class="form-control" placeholder="Name">
                                    <p></p>	
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input readonly type="text" value="{{$page->slug}}" name="slug" id="slug" class="form-control" placeholder="Slug">
                                    <p></p>	
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="content">Content</label>
                                    <textarea name="content" id="content" class="summernote" cols="30" rows="10">{{$page->content}}</textarea>
                                </div>								
                            </div> 	
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ ($page->status == 1) ? 'selected' : ''}} value="1">Active</option>
                                        <option {{ ($page->status == 0) ? 'selected' : ''}} value="0">Block</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort">Sort</label>
                                    <input type="number" value="{{$page->sort}}" name="sort" id="sort" class="form-control" placeholder="sort">
                                    <p></p>	
                                </div>
                            </div>

                        </div>
                    </div>							
                </div>

                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{route('pages.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>

		</div>
	</section>
@endsection



@section('customJs')
    <script>

        $("#pageForm").submit(function(event){

            event.preventDefault();

            var element = $(this);

            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route("pages.update",$page->id) }}',
                type:'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){

                    $("button[type=submit]").prop('disabled', false);

                    if(response["status"]==true){

                        $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");
                    
                        $("#slug").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");

                        $("#status").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");

                        window.location.href="{{route('pages.index')}}"

                    }else{

                        if(response['notFound'] == true){
                            window.location.href="{{route('pages.index')}}"
                            return false;
                        }

                        var errors = response['errors'];

                        if(errors['name']){
                            $("#name").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors['name']);
                        }else{
                            $("#name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");
                        }

                        if(errors['slug']){
                            $("#slug").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors['slug']);
                        }else{
                            $("#slug").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");
                        }

                        if(errors['status']){
                            $("#status").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(errors['status']);
                        }else{
                            $("#status").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html("");
                        }
                    }

                }, error:function(jqXHR,exception){
                    console.log("Something went wrong");
                }
            })
        });



        $('#name').change(function(){
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


    </script>
@endsection







