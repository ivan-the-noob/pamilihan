<?php
include("inc/config.php");
include("inc/class.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';  // or 'path_to_phpmailer/PHPMailerAutoload.php'

$mail = new PHPMailer(true);
$c = new CustomizeClass();

if(isset($_POST['register'])){
    $success = "";
    $error = "";
    $output = "";

    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $license_number = $_POST['license_number'];
    $vehicle_type =  $_POST['vehicle_type'];
    $vehicle_model = $_POST['vehicle_model'];
    $vehicle_plate_no = $_POST['vehicle_plate_no'];

    if($password == $confirm_password){
        $sql = "SELECT * FROM tbl_user WHERE email=:email AND role='Rider' OR email=:email AND role='Seller' OR email=:email AND role='Admin'";
        $p = [
            ":email"    =>  $email
        ];
        $res = $c->countData($pdo, $sql, $p);
        if($res > 0){
            $error = "Email already exist, please change your email!";
        }else{
            $insert = "INSERT INTO tbl_user (full_name, email, phone, password, role, status) VALUES (:fname, :email, :phone, :passwords, :roles, :statuss)";
            $p1 = [
                ":fname"        =>  $full_name,
                ":email"        =>  $email,
                ":phone"        =>  $phone,
                ":passwords"    =>  md5($password),
                ":roles"        =>  "Rider",
                ":statuss"      =>  "Pending"
            ];
            $res1 = $c->insertData($pdo,$insert,$p1);
            $getLastId = $pdo->lastInsertId();
            $r_token = time();

            $insert2 = "INSERT INTO tbl_rider (user_id, license_number, vehicle_type, vehicle_model, vehicle_plate_no, r_token, r_status) VALUES (:uIds, :license, :types, :model, :plate, :token, :statuss)";
            $p2 = [
                ":uIds"    =>  $getLastId,
                ":license"    =>  $license_number,
                ":types"    =>  $vehicle_type,
                ":model" =>  $vehicle_model,
                ":plate"     =>  $vehicle_plate_no,
                ":token"   =>  $r_token,
                ":statuss"   =>  "Pending"
            ];
            $res2 = $c->insertData($pdo,$insert2,$p2);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPAuth = true;
                $mail->Username = 'lorem.ipsum.sample.email@gmail.com';
                $mail->Password   = 'novtycchbrhfyddx';
                $mail->setFrom('lorem.ipsum.sample.email@gmail.com', 'Pamilihan');
                $mail->addAddress($email, "Verification");

                $mail->isHTML(true);
                $mail->Subject = 'Verification Link';
                $verify_link = "http://localhost/pamilihannet/system/" . 'verify.php?email=' . $email . '&token=' . $r_token;
                $message = '
                ' . "Verification Link" . '<br>
                <a href="' . $verify_link . '">' . $verify_link . '</a>';
                $mail->Body = $message;
                $mail->send();
                $success = "We've sent a verification to your email. Please check your inbox.";
            } catch (Exception $e) {
                $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }else{
        $error = "Your password and confirm password didn't match!";
    }

    $output = array(
        'success'   =>  $success,
        'error'     =>  $error,
    );

    echo json_encode($output);
}
?>