@extends('front.layouts.app')
@section('content')

    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                    <li class="breadcrumb-item">Settings</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">

                <!-- Add sidebar  -->
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                        </div>

                        <form action="" name="profileForm" id="profileForm">
                            <div class="card-body p-4">

                                <div class="row">

                                    <div class="mb-3">               
                                        <label for="name">Name</label>
                                        <input type="text" value="{{$user->name}}" name="name" id="name" placeholder="Enter Your Name" class="form-control">
                                    </div>

                                    <div class="mb-3">            
                                        <label for="email">Email</label>
                                        <input type="text" value="{{$user->email}}" name="email" id="email" placeholder="Enter Your Email" class="form-control">
                                    </div>

                                    <div class="mb-3">                                    
                                        <label for="phone">Phone</label>
                                        <input type="number" value="{{$user->phone}}" name="phone" id="phone" placeholder="Enter Your Phone" class="form-control">
                                    </div>

                                    <!-- <div class="mb-3">                                    
                                        <label for="phone">Address</label>
                                        <textarea name="address" id="address" class="form-control" cols="30" rows="5" placeholder="Enter Your Address"></textarea>
                                    </div> -->

                                    <div class="d-flex">
                                        <button class="btn btn-dark">Update</button>
                                    </div>

                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection



@section('customJs')
    <script>

        $("#profileForm").submit(function(event){

            event.preventDefault();
            var element = $(this);

            $("button[type=submit]").prop('disabled', true);

            $.ajax({

                url: '{{ route("account.updateProfile") }}',
                type:'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){

                    $("button[type=submit]").prop('disabled', false);

                    if(response["status"]==true){

                        window.location.href="{{route('categories.index')}}"

                        $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");

                        $("#slug").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");

                    }else{

                        if(response['notFound'] == true){
                            window.location.href="{{route('categories.index')}}"
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
                    }

                }, error:function(jqXHR,exception){
                    console.log("Something went wrong");
                }
            })
        });

    </script>
@endsection












