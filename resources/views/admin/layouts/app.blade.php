<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- website link icon  -->
		<link rel="icon" type="image/x-icon" href="{{ asset('admin-assets/img/AdminLTELogo.png') }}">
		<!-- website link title  -->
		<title>Laravel Shop :: Administrative Panel</title>
		<!-- Google Font: Source Sans Pro -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="{{asset('admin-assets/plugins/fontawesome-free/css/all.min.css')}}">
		<!-- summernote use only for product page  -->
		<link rel="stylesheet" href="{{asset('admin-assets/plugins/summernote/summernote.min.css')}}">
		<!-- Theme style -->
		<link rel="stylesheet" href="{{asset('admin-assets/css/adminlte.min.css')}}">
		<!-- dropzone  -->
		<link rel="stylesheet" href="{{asset('admin-assets/plugins/dropzone/min/dropzone.min.css')}}">
		<!-- CSRF token  -->
		<meta name="csrf-token" content="{{csrf_token()}}" />
		<!-- this is for related products  -->
		<link rel="stylesheet" href="{{asset('admin-assets/plugins/select2/css/select2.min.css')}}">
		<!-- this is for coupon code  -->
		<link rel="stylesheet" href="{{asset('admin-assets/css/datetimepicker.css')}}">
		<!-- custom css  -->
		<link rel="stylesheet" href="{{asset('admin-assets/css/custom.css')}}">

	</head>

	<body class="hold-transition sidebar-mini">
		<div class="wrapper">
			<nav class="main-header navbar navbar-expand navbar-white navbar-light">
				<ul class="navbar-nav">
					<li class="nav-item">
					  	<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
					</li>					
				</ul>
				<div class="navbar-nav pl-2">
					<!-- <ol class="breadcrumb p-0 m-0 bg-white">
						<li class="breadcrumb-item active">Dashboard</li>
					</ol> -->
				</div>
				
				<ul class="navbar-nav ml-auto">

					<li class="nav-item">
						<a class="nav-link" data-widget="fullscreen" href="#" role="button">
							<i class="fas fa-expand-arrows-alt"></i>
						</a>
					</li>

					<li class="nav-item dropdown">

						<a class="nav-link p-0 pr-3" data-toggle="dropdown" href="#">
							@if (settingData()->adminPicture_id != NULL)
								<img src="{{asset('uploads/setting/admin_picture_1.jpg')}}" class='img-circle elevation-2' width="40" height="40" alt="src="{{asset('uploads/setting/1.jpg')}}"">
							@else
								<img src="{{asset('uploads/setting/alter.jpg')}}" class='img-circle elevation-2' width="40" height="40" alt="src="{{asset('uploads/setting/1.jpg')}}"">
							@endif
						</a>

						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-3">

							<h4 class="h4 mb-0"><strong>{{Auth::guard('admin')->user()->name}}</strong></h4>

							<div class="mb-3">{{Auth::guard('admin')->user()->email}}</div>

							<div class="dropdown-divider"></div>

							<a href="{{route('settings.settings')}}" class="dropdown-item">
								<i class="fas fa-user-cog mr-2"></i> Settings								
							</a>

							<div class="dropdown-divider"></div>

							<a href="{{route('admin.showChangePasswordForm')}}" class="dropdown-item">
								<i class="fas fa-lock mr-2"></i> Change Password
							</a>

							<div class="dropdown-divider"></div>

							<a href="{{route('admin.logout')}}" class="dropdown-item text-danger">
								<i class="fas fa-sign-out-alt mr-2"></i> Logout							
							</a>

						</div>
					</li>
				</ul>
			</nav>

			<!-- Sidebar -->
            @include('admin.layouts.sidebar')

			<!-- Page body -->
			<div class="content-wrapper">
                @yield('content')
			</div>

			<!-- Footer -->
			<footer class="main-footer">
				<strong>Copyright &copy; 2014-2022 AmazingShop All rights reserved.
			</footer>
		</div>
		

		<!-- jQuery -->
		<script src="{{asset('admin-assets/plugins/jquery/jquery.min.js')}}"></script>

		<!-- Bootstrap 4 -->
		<script src="{{asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

		<!-- dropzone  -->
		<script src="{{asset('admin-assets/plugins/dropzone/min/dropzone.min.js')}}"></script>

		<!-- summernote use only for product page  -->
		<script src="{{asset('admin-assets/plugins/summernote/summernote.min.js')}}"></script>

		<!-- this is use only for related product  -->
		<script src="{{asset('admin-assets/plugins/select2/js/select2.full.min.js')}}"></script>

		<!-- this is for coupon  -->
		<script src="{{asset('admin-assets/js/datetimepicker.js')}}"></script>

		<!-- AdminLTE App -->
		<script src="{{asset('admin-assets/js/adminlte.min.js')}}"></script>

		<!-- AdminLTE for demo purposes -->
		<script src="{{asset('admin-assets/js/demo.js')}}"></script>

		<!-- passing csrf token using ajax  -->
		<script type="text/javascript">
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			// Register summernote 
			$(document).ready(function(){
				$(".summernote").summernote({
					height: 250,
				});
			});
		</script>

		<!-- javascript  -->
        @yield('customJs')

	</body>
</html>














