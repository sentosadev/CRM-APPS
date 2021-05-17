<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>CRM - Sinsen</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>components/bootstrap/dist/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>components/font-awesome/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>components/Ionicons/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>dist/css/AdminLTE.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/iCheck/square/blue.css">
	<link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/sweetalert2/sweetalert2.min.css">
	<link rel="stylesheet" href="<?= base_url('assets/') ?>components/font-awesome/css/font-awesome.min.css">
	<style>
		.swal2-popup {
			font-size: 1.5rem !important;
		}
	</style>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page">
	<div class="login-box">
		<div class="login-logo">
			Welcome Back
		</div>
		<!-- /.login-logo -->
		<div class="login-box-body">
			<p class="login-box-msg">Please sign in to your account below.</p>

			<form id='form_'>
				<div class="form-group">
					<input type="text" name='email' class="form-control" placeholder="Email / Username" required>
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				</div>
				<div class="form-group">
					<input type="password" name='password' class="form-control" placeholder="Password" required>
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<div class="checkbox icheck">
							<label>
								<input type="checkbox" name='keep_login'> Keep me loged in
							</label>
						</div>
					</div>
					<!-- /.col -->
					<div class="col-xs-6">
						<button type="button" id="submitLogin" class="btn btn-primary btn-block btn-flat">Login to Dashboard</button>
					</div>
					<!-- /.col -->
				</div>
			</form>
		</div>
		<!-- /.login-box-body -->
	</div>
	<!-- /.login-box -->

	<!-- jQuery 3 -->
	<script src="<?= base_url('assets/') ?>components/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="<?= base_url('assets/') ?>components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- iCheck -->
	<script src="<?= base_url('assets/') ?>plugins/iCheck/icheck.min.js"></script>
	<script src="<?= base_url('assets/') ?>plugins/sweetalert2/sweetalert2.min.js"></script>
	<script src="<?= base_url('assets/') ?>plugins/jquery-validation/jquery.validate.js"></script>
	<script>
		$(function() {
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '20%' /* optional */
			});
		});

		$('#submitLogin').click(function() {
			// Swal.fire('Any fool can use a computer')
			$('#form_').validate({
				highlight: function(element, errorClass, validClass) {
					var elem = $(element);
					if (elem.hasClass("select2-hidden-accessible")) {
						$("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
					} else {
						$(element).parents('.form-input').addClass('has-error');
					}
				},
				unhighlight: function(element, errorClass, validClass) {
					var elem = $(element);
					if (elem.hasClass("select2-hidden-accessible")) {
						$("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
					} else {
						$(element).parents('.form-input').removeClass('has-error');
					}
				},
				errorPlacement: function(error, element) {
					var elem = $(element);
					if (elem.hasClass("select2-hidden-accessible")) {
						element = $("#select2-" + elem.attr("id") + "-container").parent();
						error.insertAfter(element);
					} else {
						error.insertAfter(element);
					}
				}
			})
			var values = new FormData($('#form_')[0]);
			if ($('#form_').valid()) // check if form is valid
			{
				$.ajax({
					beforeSend: function() {
						$('#submitLogin').html('<i class="fa fa-spinner fa-spin"></i> Process');
						$('#submitLogin').attr('disabled', true);
					},
					enctype: 'multipart/form-data',
					url: '<?= site_url('auth/login') ?>',
					type: "POST",
					data: values,
					processData: false,
					contentType: false,
					// cache: false,
					dataType: 'JSON',
					success: function(response) {
						if (response.status == 1) {
							window.location = response.url;
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Peringatan',
								text: response.pesan,
							})
							$('#submitLogin').attr('disabled', false);
						}
						$('#submitLogin').html('Login to Dashboard');
					},
					error: function() {
						Swal.fire({
							icon: 'error',
							title: 'Peringatan',
							text: 'Telah terjadi kesalahan !',
						})
						$('#submitLogin').html('Login to Dashboard');
						$('#submitLogin').attr('disabled', false);

					}
				});
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Peringatan',
					text: 'Silahkan lengkapi field yang wajib diisi',
				})
			}
		})
	</script>
</body>

</html>