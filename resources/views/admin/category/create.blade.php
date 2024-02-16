@extends('admin.layouts.app')
@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">					
		<div class="container-fluid my-2">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Create Category</h1>
				</div>
				<div class="col-sm-6 text-right">
					<a href="{{route('categories.index')}}" class="btn btn-primary">Back</a>
				</div>
			</div>
		</div>
		<!-- /.container-fluid -->
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Default box -->
		<div class="container-fluid">
            <form action="" method="post" id="categoryForm" name="categoryForm">
                <div class="card">
                    <div class="card-body">								
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
                                    <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">
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
                                </div>
                            </div>									
                        </div>
                    </div>							
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{route('categories.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
		</div>
		<!-- /.card -->
	</section>
	<!-- /.content -->
@endsection


<!-- আমরা ajax এর মাধ্যেমে ফর্মের সকল ডাটা ডাটাবেসে পাঠিয়ে দিবো। -->
<!-- @section('customJs') এর জন্য app.blade.php ফাইলে @yield('customJs') এ্যাড করা আছে। -->
<!-- নিচের ফরম্যাটটি হলো ajax এর একটি কমন ফরম্যাট, যেটা ডেভেলপাররা ব্যবহার করে ফর্মের ডাটা ডাটাবেসে পাঠানোর জন্য। -->
@section('customJs')
    <script>
        //$(): হলো jQuery এর একটি সিলেক্টর ফাংশন, যার কাজ হলো এক বা একাধিক HTML ইলিমেন্টকে সিলেক্ট করা।
        //#categoryForm: ফর্মের আইডিকে এখানে ক্যাচ করার হয়েছে, ফর্মকে সিলেক্ট করার জন্য।
        //submit(): এখানে হলো jQuery এর একটি মেথড, এর কাজ হলো ইভেন্ট হ্যান্ডেলার ফাংশনটিকে, ফর্মের সাবমিট বাটনের সাথে attach করে দেয়া, অর্থাৎ যখন ফর্মের সাবমিট বাটনে ক্লিক করা হবে, তখন এই ইভেন্ট হ্যান্ডেলার ফাংশনটি এক্সিকিউট হবে।
        $("#categoryForm").submit(function(event){
            //নরমালি আমরা যখন কোনো ডাটা সাবমিট করি বা কোনো একটি ইভেন্ট ঘটে, তখন সাবমিশন ফর্মের পেজটি রি-লোড হয়, এই ফাংশন পেজকে রি-লোড হতে বাধা দেয়।
            event.preventDefault();
            //$(this) এর কাজ হলো $("#categoryForm") এর মাধ্যমে যে Form ইলিমেন্ট আসছে, তাকে ধারন করা।
            var element = $(this);

            //$("button[type=submit]") -->সেই বাটনকে সিলেক্ট করবে, যার এট্রিবিউট হিসাবে submit আছে।
            //prop() --> এইচটিএমএল প্রপার্টির এট্রিবিউট সেট করার জন্য ব্যবহার করা হয়।
            //prop('disabled', true) --> বাটনকে disabled করার কারন হলো, যখন ফর্মের সাবমিট বাটন ক্লিক করা হবে অর্থাৎ ডাটা সাবমিট হবে তখন কিছু সময়ের জন্য সাবমিট বাটন disabled হয়ে যাবে, যেন ফর্ম সাবমিট হওয়ার পর অটোমেটিক্যালি একি ডাটা বার বার সাবমিট না হয়।
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                //url: আমরা ajax এর মাধ্যেমে যে রাউটে ডাটা পাঠাতে চাই, সেই রাউটের url এখানে বলে দিতে হবে।
                url: '{{route("categories.store")}}',
                //type: আমরা যে ডাটা পাঠাচ্ছি তা কী টাইপের, যেমন: get, post ইত্যাদি তা বলে দিতে হবে।
                type:'post',
                //ফর্মের ডাটা ডাটাবেসে পাঠানোর পূর্বে, ফর্মের ডাটাগুলোকে একটি এ্যারেতে serialize করে, তারপর ডাটাবেসে পাঠায়।
                data: element.serializeArray(),
                //সার্ভার থেকে যখন কোনো ডাটা আসবে বা যখন কোনো রেসপন্স আসবে তা কোন ফরম্যাটে আসবে তা তা নির্ধারন করে, আমরা সার্ভারের রেসপন্স ডাটা json ফরম্যাটে দেখতে চাই।
                dataType: 'json',
                //success: function(response): যদি ডাটাবেসে ডাটা সাকসেসফুলি যায়, তাহলে এই ফাংশনটি কল হবে।
                success: function(response){

                    $("button[type=submit]").prop('disabled', false);

                    //response['status']==true, সার্ভারে ডাটা যাওয়ার পর সার্ভার রেসপন্স যদি true আসে, তাহলে কন্ডিশন এক্সে হবে।
                    //কন্ট্রোলারে if($validator->passes()), অর্থাৎ ভ্যালিডেশন যদি পাস হয়, সার্ভার true রিটার্ন করবে।
                    if(response["status"]==true){

                        //window.location.href --> ডাটা সাবমিট হওয়ার পর, windows এর লোকেশন কোথায় হবে অর্থাৎ ডাটা সাবমিট হওয়ার পর কোন পেজে যাবে, তা এখানে বলে দেয়া হয়েছে।
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
                        //response['errors']; সার্ভারে ডাটা যাওয়ার পর সার্ভার রেসপন্স যদি এ্যারর আসে, তাহলে তা errors ভ্যারিয়েবলে এ্যাসাইন হবে।
                        var errors = response['errors'];
                        //যদি এ্যারর অবজেক্ট এর প্রপার্টি name হয়, তাহলে কন্ডিশনে প্রবেশ করবে।
                        if(errors['name']){
                            //name input tag এ সিএসএস এর একটি ক্লাস is-invalid এ্যাড করে দিবে।
                            $("#name").addClass('is-invalid')
                            //p tag কে name input tag এর siblings বানাবে, এবং এখানে এ্যারর ম্যাসেজটি দেখাবে।
                            .siblings('p')
                            //p tag এ সিএসএস এর একটি ক্লাস invalid-feedback এ্যাড করে দিবে।
                            .addClass('invalid-feedback')
                            //html পেজে name ট্যাগের যে এ্যারর গুলো আসবে, তাদের একটি এ্যারে ক্রিয়েট করবে।
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

        //name এ যে ভ্যালু দিবো, তাকে slug এর রাউটে পাঠিয়ে দিবে।
        //change() : যখন নেম ফিল্ড থেকে ফোকাস সরানো হবে, বা নেম ফিল্ডের কোনো ভ্যালু পরিবর্তন করা হবে, তখন function() টি এক্সিকিউট হবে।
        $('#name').change(function(){
            //$(this) এর কাজ হলো $("#name") এর মাধ্যমে যে name ইলিমেন্ট আসবে, তাকে ধারন করা।
            var element = $(this);

            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{route("getSlug")}}',
                type: 'get',
                //element.val() : নেম ট্যাগের ইনপুট ভ্যালুকে নিয়ে আসবে।
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












