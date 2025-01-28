<?php

include("system/inc/class.php");
include("system/inc/config.php");
include("system/inc/functions.php");

$c = new CustomizeClass();

$output = array('status' => 'error', 'message' => '');

if (isset($_POST['form3']) && isset($_POST['new_password'])) {
    $new_password = $_POST['new_password'];

    if (isset($_SESSION['reset_email']) && isset($_SESSION['reset_code'])) {
        $email = $_SESSION['reset_email'];

        try {
            $hashed_password = md5($new_password);

            $sql = "UPDATE tbl_user SET password = :password, reset_code = NULL WHERE email = :email";
            $params = [
                ':password' => $hashed_password,
                ':email' => $email
            ];

            $c->updateData($pdo, $sql, $params);

            unset($_SESSION['reset_email']);
            unset($_SESSION['reset_code']);

            $output['status'] = 'success';
            $output['message'] = 'Your password has been changed successfully.';
            $output['passwordChanged'] = true;
            $output['redirect'] = 'login.php';
        } catch (Exception $e) {
            $output['message'] = "Error occurred: " . $e->getMessage();
        }
    } else {
        $output['message'] = 'Invalid session. Please restart the password reset process.';
    }
} else {
    $output['message'] = 'New password is required.';
}

echo json_encode($output);
exit();
?>
