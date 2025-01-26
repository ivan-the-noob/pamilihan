<?php include_once('header.php');?>
<?php
if(isset($_SESSION['customer'])){
	header("Location: ./");
	exit;
}
?>
    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center">
          	<div class="col-xl-7 ftco-animate">
			<form id="loginForm" method="POST" class="login-form">
				<h3 class="mb-4 billing-heading">Log-In</h3>
				<div class="message">
					<?php
					if(isset($_SESSION['myMessage'])){
						?>
						<div class="alert alert-success"><small><?= $_SESSION['myMessage']; ?></small></div>
						<?php
						unset($_SESSION['myMessage']);
					}else{
						?>
						<div class="text text-danger"><small><span class="error"></span></small></div>
						<?php
					}
					?>
				</div>
				<hr/>
	          	<div class="row align-items-end">
	          	  <div class="col-md-6">
	                <div class="form-group">
	                  <label for="cust_email">Email</label>
	                  <input type="email" required name="cust_email" style="" id="cust_email" class="form-control" placeholder="Enter email...">
	                </div>
	              </div>
	              <div class="col-md-6">
	                <div class="form-group">
	                	<label for="cust_password">Password</label>
	                  <input type="password" required name="cust_password" id="cust_password" class="form-control" style="" placeholder="Enter password...">
	                </div>
                </div>
				<div class="col-md">
					<input type="checkbox" name="showPassword" style="cursor: pointer;" id="showPassword">
					<label for="showPassword" style="cursor: pointer;m">Show Password</label>
				</div>
                <div class="col-md-12">
                	<div class="form-group mt-4">
					<div class="radio">
						<label class="mr-3"><a href="register.php" style="cursor: pointer; color: blue;">Sign Up Here!</a></label>
					</div>
				</div>
				<p><input type="submit" name="form1" class="btn btn-primary py-3 px-4" style="border-radius: 5px;" value="Log-In"></p>
	        </form>
	          	</div>
	          </div>
          </div> <!-- .col-md-8 -->
        </div>
      </div>
    </section> <!-- .section -->

<?php include_once('footer.php');?>
<script>
    $(document).ready(function() {
		
		$('#showPassword').on('change', function(){
			var showPasswordStats = $(this).is(':checked');
			if(showPasswordStats == true){
				$('#cust_password').attr('type', 'text');
			}else{
				$('#cust_password').attr('type', 'password');
			}
		});
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();  // Prevent the default form submission

            // Get form data
            var email = $('#cust_email').val();
            var password = $('#cust_password').val();

            // Perform AJAX request to submit the form data to login.php
            $.ajax({
                url: 'login-action.php<?php if(isset($_GET['page'])){ echo '?page='.$_GET['page'];}?>',  // Server-side PHP script to handle the login
                type: 'POST',
                data: {
                    form1: true,
                    cust_email: email,
                    cust_password: password
                },
                success: function(response) {
					console.log(response);
					if (response.status == 'success') {
						alert("Login Successfully");
						window.location.href = response.redirect;
					} else {
						$('#cust_email').css({ outline: '1px solid red'});
						$('#cust_password').css({ outline: '1px solid red'});
						$('.error').html(response.message);
					}
                },
                error: function() {
                    alert('There was an error with the request.');
                }
            });
        });

		$('#cust_email').on('keyup', function(e){
			e.preventDefault();
			$(this).css('outline', 'none');
		});

		$('#cust_password').on('keyup', function(e){
			e.preventDefault();
			$(this).css('outline', 'none');
		});
    });
</script>