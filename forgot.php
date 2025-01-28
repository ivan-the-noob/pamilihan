<?php include_once('header.php'); ?>

<?php
if (isset($_SESSION['customer'])) {
    header("Location: ./");
    exit;
}
?>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 ftco-animate">
                <form id="forgotPasswordForm" method="POST" class="login-form">
                    <h3 class="mb-4 billing-heading">Forgot Password</h3>
                    <div class="message">
                        <?php
                        if (isset($_SESSION['myMessage'])) {
                            ?>
                            <div class="alert alert-success"><small><?= $_SESSION['myMessage']; ?></small></div>
                            <?php
                            unset($_SESSION['myMessage']);
                        } else {
                            ?>
                            <div class="text text-danger"><small><span class="error"></span></small></div>
                            <?php
                        }
                        ?>
                    </div>
                    <hr />
                    <div class="row align-items-end">
                        <!-- Step 1: Email input -->
                        <div class="col-md-6" id="emailInputDiv">
                            <div class="form-group">
                                <label for="cust_email">Email</label>
                                <input type="email" required name="cust_email" id="cust_email" class="form-control" placeholder="Enter email...">
                            </div>
                        </div>

                        <!-- Step 2: Verify code input -->
                        <div class="col-md-6" id="verifyCodeDiv" style="display:none;">
                            <div class="form-group">
                                <label for="reset_code">Enter the 6-digit reset code</label>
                                <input type="text" name="reset_code" id="reset_code" class="form-control" maxlength="6" placeholder="Enter reset code">
                            </div>
                        </div>

                        <!-- Step 3: New password input -->
                        <div class="col-md-6" id="newPasswordDiv" style="display:none;">
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter new password">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mt-4">
                                <div class="radio">
                                    <label class="mr-3"><a href="login.php" style="cursor: pointer; color: blue;">Log in Here!</a></label>
                                </div>
                            </div>
                            <p>
                                <!-- Step 1 Button -->
                                <input type="submit" name="form1" class="btn btn-primary py-3 px-4" style="border-radius: 5px;" value="GET CODE" id="getCodeBtn">

                                <!-- Step 2 Button -->
                                <input type="submit" name="form2" class="btn btn-primary py-3 px-4" style="border-radius: 5px; display: none;" id="verifyCodeBtn" value="VERIFY CODE">

                                <!-- Step 3 Button -->
                                <input type="submit" name="form3" class="btn btn-primary py-3 px-4" style="border-radius: 5px; display: none;" id="changePasswordBtn" value="CHANGE PASSWORD">
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include_once('footer.php'); ?>

<script>
$(document).ready(function () {
    $('#forgotPasswordForm').on('submit', function (e) {
        e.preventDefault();

        var email = $('#cust_email').val();
        var resetCode = $('#reset_code').val();
        var newPassword = $('#new_password').val();

        var formData = {};
        var url = 'forgot-password-action.php'; 

        if ($('#emailInputDiv').is(":visible")) {
            formData = {
                form1: true,
                cust_email: email
            };
            url = 'forgot-password-action.php';
        }

        if ($('#verifyCodeDiv').is(":visible") && resetCode) {
            formData = {
                form2: true,
                reset_code: resetCode
            };
            url = 'verify-code-action.php'; 
        }

        if ($('#newPasswordDiv').is(":visible") && newPassword) {
            formData = {
                form3: true,
                new_password: newPassword
            };
            url = 'change-password-action.php'; 
        }

        console.log("Form Data being sent: ", formData); 

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log("Raw Response: ", response); 

                try {
                    response = JSON.parse(response);
                    if (response.status === 'success') {
                        if (response.codeSent) {
                            $('#emailInputDiv').hide();
                            $('#getCodeBtn').hide(); 
                            $('#verifyCodeDiv').show();
                            $('#verifyCodeBtn').show(); 
                            alert("A 6-digit reset code has been sent to your email.");
                        } else if (response.codeVerified) {
                            $('#verifyCodeDiv').hide();
                            $('#verifyCodeBtn').hide();
                            $('#newPasswordDiv').show();
                            $('#changePasswordBtn').show(); 
                            alert("Code validated successfully. Please enter your new password.");
                        } else if (response.passwordChanged) {
                            alert("Your password has been changed successfully.");
                            window.location.href = response.redirect;
                        }
                    } else {
                        alert(response.message);
                    }
                } catch (e) {
                    alert("Error: Unable to process response.");
                    console.error("Parsing error:", e, "Response:", response);
                }
            },
            error: function () {
                alert('There was an error with the request.');
            }
        });
    });
});

</script>
