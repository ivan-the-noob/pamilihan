<?php
include("system/inc/class.php");
include("system/inc/config.php");
include("system/inc/functions.php");
include("system/inc/CSRF_Protect.php");

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$c = new CustomizeClass();

date_default_timezone_set("Asia/Singapore");

$output = array('status' => 'error', 'message' => '');

if (isset($_POST['form1'])) {
    $email = $_POST['cust_email'];

    try {
        $check = "SELECT * FROM tbl_user WHERE email=:email";
        $p1 = [':email' => $email];
        $count = $c->countData($pdo, $check, $p1);

        if ($count > 0) {

            $resetCode = mt_rand(100000, 999999);
        
            $sql = "UPDATE tbl_user SET reset_code = :reset_code WHERE email = :email";
            $p = [':reset_code' => $resetCode, ':email' => $email];
            $c->updateData($pdo, $sql, $p);
        
            $_SESSION['reset_code'] = $resetCode;
            $_SESSION['reset_email'] = $email;
        
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->Username = 'senanorlitoi@gmail.com';  
            $mail->Password = 'wuaq ujdg bxjx wvsm';  
            $mail->setFrom('senanorlitoi@gmail.com', 'Pamilihan');
            $mail->addAddress($email, "Verification");
        
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Code';
            $mail->Body = 'Your password reset code is: <strong>' . $resetCode . '</strong>';
        
            if ($mail->send()) {
                $output['status'] = 'success';
                $output['message'] = 'A 6-digit reset code has been sent to your email.';
                $output['codeSent'] = true;
            } else {
                $output['status'] = 'error'; 
                $output['message'] = 'Unable to send reset code. Please try again later.';
            }
        } else {
            $output['message'] = 'Email address not found!';
        }
        
    } catch (Exception $e) {
        $output['message'] = "Error occurred: " . $e->getMessage();
    }
}

echo json_encode($output);
exit();
?>
