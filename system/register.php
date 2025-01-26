<?php
if(isset($_SESSION['user'])){
	header("Location: index.php");
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Register as a Rider</title>

	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/ionicons.min.css">
	<link rel="stylesheet" href="css/datepicker3.css">
	<link rel="stylesheet" href="css/all.css">
	<link rel="stylesheet" href="css/select2.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.css">
	<link rel="stylesheet" href="css/AdminLTE.min.css">
	<link rel="stylesheet" href="css/_all-skins.min.css">

	<link rel="stylesheet" href="style.css">
</head>

<body class="hold-transition login-page sidebar-mini">

<!-- <div class="login-box">
	<div class="login-logo">
		<b>Register as a Rider</b>
	</div>
  	<div class="login-box-body">
    	<p class="login-box-msg">Log in to start your session</p>
		<div class="error"></div>
		<form action="" method="post">
			<div class="form-group has-feedback">
				<input class="form-control" placeholder="Email address" name="email" type="email" autocomplete="off" autofocus>
			</div>
			<div class="form-group has-feedback">
				<input class="form-control" placeholder="Password" name="password" type="password" autocomplete="off" value="">
			</div>
			<div class="row">
				<div class="col-xs-8"><a href="register.php">Register as a Rider?</a></div>
				<div class="col-xs-4">
					<input type="submit" class="btn btn-success btn-block btn-flat login-button" name="form1" value="Log In">
				</div>
			</div>
		</form>
	</div>
</div> -->

<div class="container" style="margin-top: 5% !important;">
	<div class="login-box-body" style="width: 80% !important; margin: 0 auto;">
		<h5>Account Details</h5>
		<hr/>
		<form id="registerRider" method="POST">
		<div class="error"></div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group has-feedback">
					<label for="">Full Name</label>
					<input class="form-control" placeholder="Full name..." name="full_name" type="full_name" autocomplete="off" autofocus required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group has-feedback">
					<label for="">Email</label>
					<input class="form-control" placeholder="Email..." name="email" type="email" autocomplete="off" autofocus required>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="">Phone No.</label>
					<input class="form-control" placeholder="Phone no..." name="phone" type="text" maxlength="11" minlength="11" autocomplete="off" autofocus required>
					<small><b>Format: </b>0987654321</small>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group has-feedback">
					<label for="">Password</label>
					<input class="form-control" placeholder="Password..." name="password" type="password" autocomplete="off" autofocus required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group has-feedback">
					<label for="">Confirm Password</label>
					<input class="form-control" placeholder="Confirm password..." name="confirm_password" type="password" autocomplete="off" autofocus required>
				</div>
			</div>
			<div class="col-md-12"><hr/></div>
			<div class="col-md-3">
				<div class="form-group has-feedback">
					<label for="">License Number</label>
					<input class="form-control" placeholder="License number..." name="license_number" type="text" autocomplete="off" autofocus required>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group has-feedback">
					<label for="">Vehicle Type</label>
					<input class="form-control" placeholder="Vehicle type..." name="vehicle_type" type="text" autocomplete="off" autofocus required>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group has-feedback">
					<label for="">Vehicle Model</label>
					<input class="form-control" placeholder="Vehicle model..." name="vehicle_model" type="text" autocomplete="off" autofocus required>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group has-feedback">
					<label for="">Plate No.</label>
					<input class="form-control" placeholder="Plate no..." name="vehicle_plate_no" type="text" autocomplete="off" autofocus required>
				</div>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-8"><a href="login.php">Have an account?</a></div>
			<div class="col-xs-4">
				<input type="submit" class="btn btn-success btn-block btn-flat login-button" name="form1" value="Register">
			</div>
		</div>
		</form>
	</div>
</div>


<script src="js/jquery-2.2.3.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/select2.full.min.js"></script>
<script src="js/jquery.inputmask.js"></script>
<script src="js/jquery.inputmask.date.extensions.js"></script>
<script src="js/jquery.inputmask.extensions.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/icheck.min.js"></script>
<script src="js/fastclick.js"></script>
<script src="js/jquery.sparkline.min.js"></script>
<script src="js/jquery.slimscroll.min.js"></script>
<script src="js/app.min.js"></script>
<script src="js/demo.js"></script>
<script>
	$(document).ready(function(){
		$(document).on('submit', '#registerRider', function(e){
			e.preventDefault();
			var formData = $(this).serialize()+'&register=true';
			$.ajax({
				url:"register-action.php",
				method:"POST",
				data:formData,
				dataType:"JSON",
				success:function(response){
					if(response.error != ""){
						$('.error').html(response.error);
					}else{
						alert(response.success);
						window.location.href='login.php';
					}
				}
			});
		});
	});
</script>

</body>
</html>