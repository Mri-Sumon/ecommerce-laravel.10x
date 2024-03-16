@extends('admin.layouts.app')
@section('content')

    <section class="content-header">					
		<div class="container-fluid my-2">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Edit <Section></Section></h1>
				</div>
				<div class="col-sm-6 text-right">
					<a href="{{route('sections.create')}}" class="btn btn-primary">Back</a>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="container-fluid">

            <form action="" method="post" name="sectionForm" id="sectionForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <div class="input-group">
                                        <input type="text" value="{{$section->name}}" name="name" id="name" class="form-control" placeholder="Section Name">
                                        <p></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input readonly value="{{$section->slug}}" type="text" name="slug" id="slug" class="form-control" placeholder="Slug">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ ($section->status == 1) ? 'selected' : ''}} value="1">Active</option>
                                        <option {{ ($section->status == 0) ? 'selected' : ''}} value="0">Block</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{route('sections.create')}}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>

		</div>
	</section>
@endsection





@section('customJs')
    <script>

        $("#sectionForm").submit(function(event){

            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route("sections.update",$section->id) }}',
                type:'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){

                    $("button[type=submit]").prop('disabled', false);

                    if(response["status"]==true){

                        window.location.href="{{route('sections.create')}}"

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

                    }else{

                        if(response['notFound'] == true){
                            window.location.href="{{route('sections.create')}}"
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