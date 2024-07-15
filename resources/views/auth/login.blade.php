<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
	<title>Login</title>
	<!-- [Meta] -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
	<meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
	<meta name="author" content="CodedThemes">
	<meta name="csrf-token" content="{{ csrf_token() }}">

  	<!-- [Favicon] icon -->
	<link rel="icon" href="{{ url('assets/images/favicon.svg') }}" type="image/x-icon"> <!-- [Google Font] Family -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
	<!-- [Tabler Icons] https://tablericons.com -->
	<link rel="stylesheet" href="{{ url('assets/fonts/tabler-icons.min.css') }}" >
	<!-- [Feather Icons] https://feathericons.com -->
	<link rel="stylesheet" href="{{ url('assets/fonts/feather.css') }}" >
	<!-- [Font Awesome Icons] https://fontawesome.com/icons -->
	<link rel="stylesheet" href="{{ url('assets/fonts/fontawesome.css') }}" >
	<!-- [Material Icons] https://fonts.google.com/icons -->
	<link rel="stylesheet" href="{{ url('assets/fonts/material.css') }}" >
	<!-- [Template CSS Files] -->
	<link rel="stylesheet" href="{{ url('assets/css/style.css') }}" id="main-style-link" >
	<link rel="stylesheet" href="{{ url('assets/css/style-preset.css') }}" >
	<link href="{{ url('assets/css/toast.style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('assets/css/toast.style.min.css') }}" rel="stylesheet" type="text/css">
	<meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body>
  	<!-- [ Pre-loader ] start -->
  	<div class="loader-bg">
    	<div class="loader-track">
      		<div class="loader-fill"></div>
    	</div>
  	</div>
  	<!-- [ Pre-loader ] End -->

	<div class="auth-main">
		<div class="auth-wrapper v3">
			<div class="auth-form">
				<div class="auth-header">
					<a href="#"><img src="../assets/images/logo-dark.svg" alt="img"></a>
				</div>
				<div class="card my-5">
					<div class="card-body">
						<form autocomplete="off" id="loginForm" method="POST">
							@csrf
							<div class="d-flex justify-content-between align-items-end mb-4">
								<h3 class="mb-0"><b>Login</b></h3>
								<a href="{{ route('register') }}" class="link-primary">Don't have an account?</a>
							</div>
							<div class="form-group mb-3">
								<label class="form-label">Username</label>
								<input type="text" id="username" name="username" class="form-control" placeholder="Username">
							</div>
							<div class="form-group mb-3">
								<label class="form-label">Password</label>
								<input type="password" id="password" name="password" class="form-control" placeholder="Password">
							</div>
							<div class="d-flex mt-1 justify-content-between">
								<div class="form-check">
									<input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="">
									<label class="form-check-label text-muted" for="customCheckc1">Keep me sign in</label>
								</div>
								<h5 class="text-secondary f-w-400">Forgot Password?</h5>
							</div>
							<div class="d-grid mt-4">
								<button type="submit" class="btn btn-primary">Login</button>
							</div>
						</form>
						<div class="saprator mt-3">
							<span>Login with</span>
						</div>
						<div class="row">
							<div class="col-4">
								<div class="d-grid">
									<button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
										<img src="{{ url('assets/images/authentication/google.svg') }}" alt="img"> <span class="d-none d-sm-inline-block"> Google</span>
									</button>
								</div>
							</div>
							<div class="col-4">
								<div class="d-grid">
									<button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
										<img src="{{ url('assets/images/authentication/twitter.svg') }}" alt="img"> <span class="d-none d-sm-inline-block"> Twitter</span>
									</button>
								</div>
							</div>
							<div class="col-4">
								<div class="d-grid">
									<button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
										<img src="{{ url('assets/images/authentication/facebook.svg') }}" alt="img"> <span class="d-none d-sm-inline-block"> Facebook</span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="auth-footer row">
					<!-- <div class=""> -->
					<div class="col my-1">
						<p class="m-0">Copyright © <a href="#">MoelTeam</a></p>
					</div>
					<div class="col-auto my-1">
						<ul class="list-inline footer-link mb-0">
							<li class="list-inline-item"><a href="#">Home</a></li>
							<li class="list-inline-item"><a href="#">Privacy Policy</a></li>
							<li class="list-inline-item"><a href="#">Contact us</a></li>
						</ul>
					</div>
					<!-- </div> -->
				</div>
			</div>
		</div>
	</div>
  	<!-- [ Main Content ] end -->

	<!-- Required Js -->
	<script src="{{ url('assets/js/plugins/popper.min.js') }}"></script>
	<script src="{{ url('assets/js/plugins/simplebar.min.js') }}"></script>
	<script src="{{ url('assets/js/plugins/bootstrap.min.js') }}"></script>
	<script src="{{ url('assets/js/fonts/custom-font.js') }}"></script>
	<script src="{{ url('assets/js/pcoded.js') }}"></script>
	<script src="{{ url('assets/js/plugins/feather.min.js') }}"></script>
	<script>layout_change('light');</script>
	<script>change_box_container('false');</script>
	<script>layout_rtl_change('false');</script>
	<script>preset_change("preset-1");</script>
	<script>font_change("Public-Sans");</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
	<script src="{{ url('assets/js/toast.script.js') }}"></script>
	<script>
		$(document).ready(function() {

			$('#loginForm').submit(function(event) {
				event.preventDefault();

				var username = $('#username').val();
				var password = $('#password').val();
				var token = $("meta[name='csrf-token']").attr("content");

				if (username.length === "") {
					$("#validation-errors").append('<div class="alert alert-error alert-block"> <a class="close" data-dismiss="alert" href="#">×</a><h4 class="alert-heading">'+"error"+'</h4></div>');
				} else {

					$.ajax({
						url: "{{ route('dologin') }}",
						type: "POST",
						cache: false,
						data: {
							"username": username,
							"password": password,
							"_token": token
						},
						success: function(response) {
							if (response.success == true) {
								$.Toast("Berhasil", 'Login berhasil', "success", {
									has_icon:true,
									has_close_btn:true,
									stack: true,
									fullscreen:false,
									timeout:8000,
									sticky:false,
									has_progress:true,
									rtl:false,
									position_class: "toast-top-right",
									width: 150,
								});
								window.location.href = response.redirect;
							}
						},
						error: function(err) {
							$.Toast("Failed", err.responseJSON.message, "warning", {
								has_icon:true,
								has_close_btn:true,
								stack: true,
								fullscreen:false,
								timeout:8000,
								sticky:false,
								has_progress:true,
								rtl:false,
								position_class: "toast-top-right",
								width: 150,
							});
							console.log(err.responseJSON.message)
						}
					});
				}
			})
		});
	</script>
</body>
<!-- [Body] end -->

</html>