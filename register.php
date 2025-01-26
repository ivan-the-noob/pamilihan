<?php include_once('header.php');?>

	<section class="mt-5">
      	<div class="container">
		  	<form id="loginForm" method="POST" class="register-form">
			<div class="col-md-12 ftco-animate pb-1 pl-4 pr-4">
				<div class="message">
				
				</div>
			</div>
			
			
        	<div class="row justify-content-center">
				<div class="col-xl-5 ftco-animate border p-4 mr-2 ml-2 mb-2">
					<h3 class="mb-4 billing-heading">Account Details</h3>
					<hr/>
					<div class="row align-items-center">
						<div class="col-md-12">
							<div class="form-group">
								<label for="cust_email">Email <span class="text-danger">*</span></label>
								<input type="email" required name="cust_email" style="" id="cust_email" class="form-control" placeholder="Enter email...">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="cust_password">Password <span class="text-danger">*</span></label>
								<input type="password" minlength="6" required name="cust_password" id="cust_password" class="form-control" style="" placeholder="Enter password...">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="cust_confirm_password">Confirm Password <span class="text-danger">*</span></label><br>
								<small><span class="text text-danger passwordError"></span></small>
								<input type="password" minlength="6" required name="cust_confirm_password" id="cust_confirm_password" class="form-control" style="" placeholder="Enter confirm password...">
							</div>
						</div>
						<div class="col-md text-center">
							<input type="checkbox" name="showPassword" style="cursor: pointer;" id="showPassword">
							<label for="showPassword" style="cursor: pointer;">Show Password</label>
						</div>
					</div>
				</div> <!-- .col-md-8 -->
		  		<div class="col-xl-5 ftco-animate border p-4 mr-2 ml-2 mb-2">
				  	<h3 class="mb-4 billing-heading">Personal Information</h3>
					<hr/>
					<div class="row align-items-end">
						<div class="col-md-12">
							<div class="form-group">
								<label for="firstname">Last Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="cust_lname" name="cust_lname" placeholder="Enter last name..." required>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="firstname">First Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="cust_fname" name="cust_fname" placeholder="Enter first name..." required>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="cust_mname">Middle Name <small>(optional)</small></label>
								<input type="text" class="form-control" id="cust_mname" name="cust_mname" placeholder="Enter middle name...">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="firstname">Phone No. <span class="text-danger">*</span></label>
								<input type="text" maxlength="11" class="form-control" id="cust_phone" name="cust_phone" placeholder="Enter phone number..." required>
								<small><b>Format: </b>09876543210</small>
							</div>
						</div>
						<!-- Checkbox and Terms Link -->
                        <div class="checkbox-container mb-3 text-center">
                            <input type="checkbox" id="termsCheckbox" class="mx-2">
                            <label for="termsCheckbox">
                                I have read and agree to the
                                <a href="terms.php" target="_self">Terms and Conditions</a>
                            </label>
                        </div>
						<div class="col-md-12">
							<div class="form-group">
							<button id="register" disabled type="submit" class="btn btn-primary btn-block py-3 px-4""
                            name="register">Register</button>
								<!--p><input type="submit" name="submit" class="btn btn-primary btn-block py-3 px-4" style="border-radius: 5px;" disabled value="Register"></p-->
							</div>
						</div>
						<div class="col-md-12">
					<div class="form-group text-center">
						<div class="radio">
							<label class="mr-3"><a href="login.php" style="cursor: pointer; color: blue;">Sign In Here!</a></label>
						</div>
					</div>
				</div>
					</div>
				</div>
        	</div>
			</form>
      	</div>
    </section> <!-- .section --><br>
	<hr/><br><br>

<?php include_once('footer.php');?>
<script>
		function addslashes(str) {
			return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
		}
		$(document).ready(function(){
		$(document).on('click', '.btn-close', function(e){
			e.preventDefault();
			$('.message').html('');
		});
		$('#loginForm').on('submit', function(e){
			e.preventDefault();
			var FormData = $(this).serialize()+'&submit=true';
			var pass = $('#cust_password').val();
			var confirmPass = $('#cust_confirm_password').val();
			if(pass != confirmPass){
				$('.message').html('<div class="alert alert-danger"><div class="row"><div class="col-md-11"><span class="error">Your confirm password didn\'t match to your password.</span></div><div class="col-md-1"><button type="button" class="btn btn-close"><span class="icon-close"></span></button></div></div></div>');
			}else{
				$.ajax({
					method: "POST",
					url: "action.php",
					data: FormData,
					dataType: "JSON",
					success:function(response){
						if(response.error != ""){
							$('.message').html('<div class="alert alert-danger"><div class="row"><div class="col-md-11"><span class="error">'+addslashes(response.error)+'</span></div><div class="col-md-1"><button type="button" class="btn btn-close"><span class="icon-close"></span></button></div></div></div>');
						}else{
							alert(response.success);
							window.location.href="./login.php";
						}
					}
				});
			}
		});

		$('#showPassword').on('change', function(){
			var showPasswordStats = $(this).is(':checked');
			if(showPasswordStats == true){
				$('#cust_password').attr('type', 'text');
				$('#cust_confirm_password').attr('type', 'text');
			}else{
				$('#cust_password').attr('type', 'password');
				$('#cust_confirm_password').attr('type', 'password');
			}
		});

		$('#cust_confirm_password').on('keyup', function(){
			var password = $('#cust_password').val();
			if(password != ""){
				if(password != $(this).val()){
					$('.passwordError').html('Password do NOT match!');
					$('#cust_confirm_password').css({ outline: '1px solid red' });
				}else{
					$('.passwordError').html('');
					$('#cust_confirm_password').css({ outline: 'none' });
				}
			}
		});

		$('#cust_password').on('keyup', function(){
			var confirmPassword = $('#cust_confirm_password').val();
			if(confirmPassword != ""){
				if($(this).val() != confirmPassword){
					$('.passwordError').html('Password do NOT match!');
					$('#cust_confirm_password').css({ outline: '1px solid red' });
				}else{
					$('#cust_confirm_password').css({ outline: 'none' });
					$('.passwordError').html('');
				}
			}
		});

		var quantitiy=0;
		   $('.quantity-right-plus').click(function(e){
		        
		        // Stop acting like a button
		        e.preventDefault();
		        // Get the field name
		        var quantity = parseInt($('#quantity').val());
		        
		        // If is not undefined
		            
		            $('#quantity').val(quantity + 1);

		          
		            // Increment
		        
		    });

		     $('.quantity-left-minus').click(function(e){
		        // Stop acting like a button
		        e.preventDefault();
		        // Get the field name
		        var quantity = parseInt($('#quantity').val());
		        
		        // If is not undefined
		      
		            // Increment
		            if(quantity>0){
		            $('#quantity').val(quantity - 1);
		            }
		    });
		    
		});
	</script>

<script>
        // Enable or disable the "Register" button based on the checkbox state
        const registerButton = document.getElementById('register');
        const termsCheckbox = document.getElementById('termsCheckbox');

        termsCheckbox.addEventListener('change', function () {
            registerButton.disabled = !termsCheckbox.checked;
        });
    </script>