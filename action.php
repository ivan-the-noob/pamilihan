<?php
include("system/inc/class.php");
include("system/inc/config.php");
include("system/inc/functions.php");
include("system/inc/CSRF_Protect.php");

require 'vendor/autoload.php'; // Ensure Ratchet is installed via Composer

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\App;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$c = new CustomizeClass();

date_default_timezone_set("Asia/Singapore");

// For Register
if(isset($_POST['submit'])){
    $success = "";
    $error = "";
    $output = "";

    // Details
    $email = "";
    $password = "";
    $c_password = "";
    $lname = "";
    $fname = "";
    $mname = "";
    $phone = "";

    $email = $_POST['cust_email'];
    $password = $_POST['cust_password'];
    $c_password = $_POST['cust_confirm_password'];
    $lname = $_POST['cust_lname'];
    $fname = $_POST['cust_fname'];
    $phone = $_POST['cust_phone'];
    // AFTER ADDING A CUSTOMER DETAILS, SEND A VERIFICATION TO HIS/HER EMAIL.
    try {
        if($password != $c_password){
            $error = "Your confirm password didn't match to your password.";
        }else{
            if(!empty($_POST['cust_mname'])){
                $fullname = sprintf("%s %s %s", $fname, $mname, $lname);
            }else{
                $fullname = sprintf("%s %s", $fname, $lname);
            }
    
            // CHECK EMAIL IF EXISTING OR NOT
            $check = "SELECT * FROM tbl_user WHERE email=:email";
            $p1 = [
                ':email'    =>  $email,
            ];
            $count = $c->countData($pdo, $check, $p1);
            if($count > 0){
                $error = "Email already exists, please use another email.";
            }else{
                // INSERT NEW USER
                $sql = "INSERT INTO tbl_user (full_name, email, phone, password, role, status) VALUES (:f, :e, :ph, :pa, :ro, :st)";
                $p = [
                    ':f'    =>  $fullname,
                    ':e'    =>  $email,
                    ':ph'   =>  $phone,
                    ':pa'   =>  md5($password),
                    ':ro'   =>  'customer',
                    ':st'   =>  'Pending',
                ];
                $res = $c->insertData($pdo, $sql, $p);
                $lastId = $pdo->lastInsertId();
                if($res){
                    // $success = "Register Successfully! $lastId";
                    // AFTER REGISTER AN ACCOUNT, CREATE CUSTOMER BILLING & SHIPPING INFORMATION 174
                    $token = md5(time());
                    $cust_datetime = date('Y-m-d h:i:s');
                    $cust_timestamp = time();
                    $sql2 = "INSERT INTO tbl_customer (user_id, cust_name, cust_cname, cust_email, cust_phone, cust_token, cust_timestamp, cust_status) VALUES (:u_id, :cName, :cCname, :cEmail, :cPhone, :cToken, :cStamp, '0')";
                    $p2 = [
                        ":u_id"     =>  $lastId,
                        ":cName"    =>  $fullname,
                        ":cCname"   =>  "none",
                        ":cEmail"   =>  $email,
                        ":cPhone"   =>  $phone,
                        ":cToken"   =>  $token,
                        ":cStamp"   =>  $cust_timestamp
                    ];
                    $res2 = $c->insertData($pdo, $sql2, $p2);
                    // ADD BLANK BILLING ADDRESS FOR THE NEW BUYER/CUSTOMER
                    $sql3 = "INSERT INTO tbl_billing_address (user_id, country) VALUES (:u_id, 'Philippines')";
                    $p3 = [
                        ':u_id'     =>  $lastId,
                    ];
                    $res3 = $c->insertData($pdo, $sql3, $p3);
                    // ADD BLANK BILLING ADDRESS FOR THE NEW BUYER/CUSTOMER
                    $sql3 = "INSERT INTO tbl_shipping_address (user_id, country) VALUES (:u_id, 'Philippines')";
                    $p3 = [
                        ':u_id'     =>  $lastId,
                    ];
                    $res3 = $c->insertData($pdo, $sql3, $p3);
                }
            }
        }
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'senanorlitoi@gmail.com';
        $mail->Password   = 'wuaq ujdg bxjx wvsm';
        $mail->setFrom('senanorlitoi@gmail.com', 'Pamilihan');
        $mail->addAddress($email, "Verification");

        $mail->isHTML(true);
        $mail->Subject = 'Verification Link';
        $verify_link =  'http://localhost/pamilihannet/'.'verify.php?email=' . $email . '&token=' . $token;
        $message = '
        ' . "Verification Link" . '<br>
        <a href="' . $verify_link . '">' . $verify_link . '</a>';
        $mail->Body = $message;
        $mail->send();
        $success = 'We\'ve sent a verification to your email. Please check your inbox.';
    } catch (Exception $e) {
        $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    
    $output = array(
        'success'   =>  $success,
        'error'     =>  $error,
    );

    echo json_encode($output);
}

// For Decrease and Increase Quantity in Cart
if(isset($_POST['dec_inc_quantity'])){
    $total = 0;
    $retail = 0;
    $p_id = $_POST['pId'];
    $sesId = $_POST['sesId'];
    if(!empty($_POST['quantity'])){
        $quantity = $_POST['quantity'];
    }else{
        $quantity = 1;
    }
    $sql = "SELECT * FROM tbl_inventory WHERE p_id=:pId AND stock_status='Available'";
    $p = [
        ':pId'  =>  $p_id,
    ];
    $res = $c->fetchData($pdo, $sql, $p);
    if($res != false){
        $sql = "SELECT * FROM tbl_product WHERE p_id=:pId";
        $p = [
            ':pId'  =>  $p_id,
        ];
        $res = $c->fetchData($pdo, $sql, $p);
        foreach($res as $row){
            $retail = $_SESSION['cart_p_current_price'][$sesId];
            $total = $retail * $quantity;

            if(isset($_SESSION['cart_p_id'])){
                $_SESSION['cart_p_qty'][$sesId] = $quantity;
            }

            echo number_format($total, 2);
        }
    }
}

// For Update Total Amount Once You Decrease/Increase the Quantity.
if(isset($_POST['forTotalAmount'])){
    $totalAmount = 0;
    $total = 0;
    $i = 1;
    if(isset($_SESSION['cart_p_id'])){
        foreach($_SESSION['cart_p_id'] as $key => $value){
            $totalAmount += $_SESSION['cart_p_qty'][$i] * $_SESSION['cart_p_current_price'][$i];
            $i++;
        }
    }
    echo number_format($totalAmount, 2);
}
if (isset($_POST['email'])) {
    $output = [];
    $email = $_POST['email'];
    $verification_code = $_POST['verification_code'];

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'senanorlitoi@gmail.com'; // Your email
        $mail->Password = 'wuaq ujdg bxjx wvsm'; // Your App Password
        $mail->setFrom('senanorlitoi@gmail.com', 'Pamilihan');
        $mail->addAddress($email, "Verification");
        $mail->isHTML(true);
        $mail->Subject = 'Your Verification Code';
        $mail->Body = "Your verification code is <b>$verification_code</b>";

        if ($mail->send()) {
            $_SESSION['verification_code'] = $verification_code; // Store verification code in session
            $output['success'] = "Verification code has been sent to your email.";
        } else {
            $output['error'] = "Failed to send the verification code.";
        }
    } catch (Exception $e) {
        $output['error'] = "Mail error: {$mail->ErrorInfo}";
    }

    echo json_encode($output);
}


// For Update Profile
if(isset($_POST['update'])){
    // For Update Account Details
    if($_POST['update'] == "updateAccount"){
        $success = "";
        $error = "";
        $output = "";
        $btL = "";
        $user_id = "";
        $email = "";
        $fname = "";
        $phone = "";
        $photo = "";
        if(isset($_SESSION['customer'])){
            $email = $_SESSION['customer']['email'];
            $fname = $_POST['f_name'];
            $phone = $_POST['phone_no'];
            if(!empty($_POST['profile_pic'])){
                $photo = $_POST['profile_pic'];
            }
            $user_id = $_SESSION['customer']['id'];
            $sql = "UPDATE tbl_user SET email=:email, full_name=:fname, phone=:phone, photo=:photo WHERE id=:u_id";
            $p = [
                ":email"        =>  $email,
                ":fname"        =>  $fname,
                ":phone"        =>  $phone,
                ":photo"        =>  $photo,
                ":u_id"         =>  $user_id
            ];
            $res = $c->updateData($pdo, $sql, $p);

            $success = "Your account details has been updated!";
        }else{
            $error = "Your session has ended, if you want to update your details, please try to login again!";
            $btL = "login.php";
        }



        $output = array(
            'success'       =>  $success,
            'error'         =>  $error,
            'backToLogin'   =>  $btL
        );
    
        echo json_encode($output);
    }

    // For Update Billing Address
    if($_POST['update'] == "updateBilling"){
        $success = "";
        $error = "";
        $output = "";
        $btL = "";
        $user_id = "";
        $address = "";
        $fname = "";
        $phone = "";
        $city = "";
        $sameAsShipping = "";
        if(isset($_SESSION['customer'])){
            $address = $_POST['B_address'];
            $fname = $_POST['B_fname'];
            $phone = $_POST['B_phone'];
            $city = $_POST['B_city'];
            $user_id = $_SESSION['customer']['id'];
            if(isset($_POST['sameAsShipping'])){
                $sql1 = "UPDATE tbl_billing_address SET full_name=:fname, phone=:phone, address=:address, city=:city WHERE user_id=:u_id";
                $p1 = [
                    ":fname"        =>  $fname,
                    ":phone"        =>  $phone,
                    ":address"      =>  $address,
                    ":city"         =>  $city,
                    ":u_id"         =>  $user_id
                ];
                $res1 = $c->updateData($pdo, $sql1, $p1);

                $sql2 = "UPDATE tbl_shipping_address SET full_name=:fname, phone=:phone, address=:address, city=:city WHERE user_id=:u_id";
                $p2 = [
                    ":fname"        =>  $fname,
                    ":phone"        =>  $phone,
                    ":address"      =>  $address,
                    ":city"         =>  $city,
                    ":u_id"         =>  $user_id
                ];
                $res2 = $c->updateData($pdo, $sql2, $p2);

                $success = "Your billing address and shipping address has been updated!".$sameAsShipping;
            }else{
                $sql = "UPDATE tbl_billing_address SET full_name=:fname, phone=:phone, address=:address, city=:city WHERE user_id=:u_id";
                $p = [
                    ":fname"        =>  $fname,
                    ":phone"        =>  $phone,
                    ":address"      =>  $address,
                    ":city"         =>  $city,
                    ":u_id"         =>  $user_id
                ];
                $res = $c->updateData($pdo, $sql, $p);
                $success = "Your billing address has been updated!";
            }
        }else{
            $error = "Failed to login, if you want to update your information, please try to login again!";
            $btL = "login.php";
        }



        $output = array(
            'success'       =>  $success,
            'error'         =>  $error,
            'backToLogin'   =>  $btL
        );
    
        echo json_encode($output);
    }

    // For Update Shipping Address
    if($_POST['update'] == "updateShipping"){
        $success = "";
        $error = "";
        $output = "";
        $btL = "";
        $user_id = "";
        $address = "";
        $fname = "";
        $phone = "";
        $city = "";
        $lat = "";
        $lng = "";
        if(isset($_SESSION['customer'])){
            $address = $_POST['S_address'];
            $fname = $_POST['S_fname'];
            $phone = $_POST['S_phone'];
            $city = $_POST['S_city'];
            $user_id = $_SESSION['customer']['id'];
            $lat = $_POST['destLat'];
            $lng = $_POST['destLng'];
            $sql = "UPDATE tbl_shipping_address SET full_name=:fname, phone=:phone, address=:address, city=:city, custLat=:lat, custLng=:lng WHERE user_id=:u_id";
            $p = [
                ":fname"        =>  $fname,
                ":phone"        =>  $phone,
                ":address"      =>  $address,
                ":city"         =>  $city,
                ":lat"          =>  $lat,
                ":lng"          =>  $lng,
                ":u_id"         =>  $user_id
            ];
            $res = $c->updateData($pdo, $sql, $p);
            if($res){
                $success = "Your shipping address has been updated!";
            }
        }else{
            $error = "Failed to login, if you want to update your information, please try to login again!";
            $btL = "login.php";
        }



        $output = array(
            'success'       =>  $success,
            'error'         =>  $error,
            'backToLogin'   =>  $btL
        );
    
        echo json_encode($output);
    }
}

// For Change Password
if(isset($_POST['changePassword'])){
    $u_id = "";
    $success = "";
    $error = "";
    $for = "";
    $output = "";
    $prev_pass = "";
    $current_pass = "";
    $new_pass = "";
    $confirm_new_pass = "";

    $current_pass = $_POST['current_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_new_pass = $_POST['confirm_new_pass'];
    
    $u_id = $_POST['u_id'];

    $sql = "SELECT * FROM tbl_user WHERE id=:u_id AND role='customer'";
    $p = [
        ":u_id" =>  $u_id
    ];
    $res = $c->fetchData($pdo, $sql, $p);
    if($res){
        foreach($res as $row){
            $prev_pass = $row['password'];
        }
        if(md5($current_pass) == $prev_pass){
            if($new_pass == $confirm_new_pass){
                if($current_pass == md5($new_pass)){
                    $for = ".message";
                    $error = "Your new password is the same as your old password!";
                }else{
                    $sql1 = "UPDATE tbl_user SET password=:new_pass WHERE id=:u_id AND role='customer' AND status='Active'";
                    $p1 = [
                        ':u_id'         =>  $u_id,
                        ':new_pass'     =>  md5($confirm_new_pass)
                    ];
                    $res1 = $c->updateData($pdo, $sql1, $p1);

                    $for = ".correct";
                    $success = "You changed your password successfully!";
                }
            }else{
                $for = ".message";
                $error = "Your new password and confirm new password do NOT match!";
            }
        }else{
            $for = ".messageCurrentPass";
            $error = "Wrong current password!";
        }
    }

    $output = array(
        'success'       =>  $success,
        'error'         =>  $error,
        'for'           =>  $for
    );

    echo json_encode($output);
}

// For Checkout Process
if(isset($_POST['checkout'])){
    $success = "";
    $error = "";

    $count = 0;
    $count = count($_SESSION['cart_p_id']);
    $myId = $_POST['myId'];
    $total = 0;
    $totalAmount = 0;
    $orderID = "O".generateRandomText(10);
    $transactId = "T".generateRandomNumber(10);
    for($i=1;$i<=$count;$i++){
        $totalAmount += $_SESSION['cart_p_qty'][$i] * $_SESSION['cart_p_current_price'][$i];
    }

  

    // Check If Billing and Shipping Address is existing.
    $billing_address = false;
    $shipping_address = false;
    $sql = "SELECT * FROM tbl_billing_address WHERE user_id=:myId";
    $pr = [
        ':myId'     =>  $myId
    ];
    $billing = $c->fetchData($pdo, $sql, $pr);

    $sql1 = "SELECT * FROM tbl_shipping_address WHERE user_id=:myId";
    $pr1 = [
        ':myId'     =>  $myId
    ];
    $shipping = $c->fetchData($pdo, $sql1, $pr1);

    foreach($billing as $row){
        if($row['full_name'] != "" && $row['phone'] != "" && $row['address'] != "" && $row['city'] != ""){
            $billing_address = true;
        }
    }

    foreach($shipping as $row){
        if($row['full_name'] != "" && $row['phone'] != "" && $row['address'] != "" && $row['city'] != ""){
            $shipping_address = true;
        }
    }

    if($billing_address == true && $shipping_address == true){
        $arr_cart_p_seller_id = array();
        $arr_cart_p_type = array();
        $arr_cart_p_size = array();
        $arr_cart_p_id = array();
        $arr_cart_p_qty = array();
        $arr_cart_p_current_price = array();

        $i=0;
        foreach($_SESSION['cart_p_seller_id'] as $key => $value) 
        {
            $i++;
            $arr_cart_p_seller_id[$i] = $value;
        }
        $i=0;
        foreach($_SESSION['cart_p_type'] as $key => $value) 
        {
            $i++;
            $arr_cart_p_type[$i] = $value;
        }
        $i=0;
        foreach($_SESSION['cart_p_size'] as $key => $value) 
        {
            $i++;
            $arr_cart_p_size[$i] = $value;
        }
        $i=0;
        foreach($_SESSION['cart_p_id'] as $key => $value) 
        {
            $i++;
            $arr_cart_p_id[$i] = $value;
        }
        $i=0;
        foreach($_SESSION['cart_p_qty'] as $key => $value) 
        {
            $i++;
            $arr_cart_p_qty[$i] = $value;
        }

        $i=0;
        foreach($_SESSION['cart_p_current_price'] as $key => $value) 
        {
            $i++;
            $arr_cart_p_current_price[$i] = $value;
        }
        for($s=1;$s<=$count;$s++){
            // Add Each Purchase Item.
            // Begin
            $insert = "INSERT INTO tbl_purchase_item (type_item, order_id, seller_id, product_id, size_id, product_price, product_qty, status) VALUES (:item, :o_id, :s_id, :p_id, :si_id, :p_price, :p_qty, 'Pending')";
            $p = [
                ":item"         =>  $arr_cart_p_type[$s],
                ":o_id"         =>  $orderID,
                ":s_id"         =>  $arr_cart_p_seller_id[$s],
                ":p_id"         =>  $arr_cart_p_id[$s],
                ":si_id"        =>  $arr_cart_p_size[$s],
                ":p_price"      =>  $arr_cart_p_current_price[$s],
                ":p_qty"        =>  $arr_cart_p_qty[$s]
            ];
            $res = $c->insertData($pdo, $insert, $p);
            // End
        }
        // Add Order
        // Begin
        $insert1 = "INSERT INTO tbl_purchase_order (order_id, customer_id, remarks, date_and_time, status) VALUES (:o_id, :c_id, :remarks, :dat, :stat)";
        $p1 = [
            ":o_id"     =>  $orderID,
            ":c_id"     =>  $myId,
            ":remarks"  =>  "Pending",
            ":dat"      =>  time(),
            ":stat"     =>  "Pending"
        ];
        $res1 = $c->insertData($pdo, $insert1, $p1);
        // End

        // Add Payment For This Order
        // Begin
        $newTotal = $_POST['myTotal'];
       // Capture POST values for payment method and related data
       $paymentMethod = !empty($_POST['payment_method']) ? $_POST['payment_method'] : '';  
       $gcashName = !empty($_POST['gcash_name']) ? $_POST['gcash_name'] : '';  
       $gcashReference = !empty($_POST['gcash_reference']) ? $_POST['gcash_reference'] : '';  
       $fullName = !empty($_POST['full_name']) ? $_POST['full_name'] : '';  
       $phoneNo = !empty($_POST['phone_no']) ? $_POST['phone_no'] : '';  
       $country = !empty($_POST['country']) ? $_POST['country'] : '';  
       $address = !empty($_POST['address']) ? $_POST['address'] : '';  
       $city = !empty($_POST['city']) ? $_POST['city'] : '';  
       
   
       // Handle file upload for GCash Image
       $gcashImage = '';
       if ($paymentMethod === 'gcash' && isset($_FILES['gcash_image']) && $_FILES['gcash_image']['error'] === UPLOAD_ERR_OK) {
           $targetDir = "assets/img/gcash/";
           $fileExtension = pathinfo($_FILES['gcash_image']['name'], PATHINFO_EXTENSION);
           $gcashImage = uniqid("gcash_") . "." . $fileExtension; // Generate unique filename
           $targetFile = $targetDir . $gcashImage;
   
           // Move the uploaded file to target directory
           if (!move_uploaded_file($_FILES['gcash_image']['tmp_name'], $targetFile)) {
               $gcashImage = ''; // Reset if upload fails
           }
       }
   
       $insert3 = "INSERT INTO tbl_purchase_payment (
        order_id, total_amount, transaction_id, date_and_time, transaction_status, 
        payment_method, gcash_name, gcash_image, gcash_reference, full_name, phone_no, country, address, city
        ) 
        VALUES (:o_id, :tot, :t_id, :dat, :stat, :pay_method, :gcash_name, :gcash_image, :gcash_ref, :full_name, :phone_no, :country, :address, :city)";

        $p3 = [
        ":o_id"         => $orderID,
        ":tot"          => $newTotal,
        ":t_id"         => $transactId,
        ":dat"          => time(),
        ":stat"         => "Pending",  
        ":pay_method"   => $paymentMethod,
        ":gcash_name"   => $gcashName,
        ":gcash_image"  => $gcashImage,  
        ":gcash_ref"    => $gcashReference,
        ":full_name"    => $fullName, 
        ":phone_no"     => $phoneNo,  
        ":country"      => $country,  
        ":address"      => $address,  
        ":city"         => $city      
        ];

        $res3 = $c->insertData($pdo, $insert3, $p3);
        // End

        unset($_SESSION['cart_p_seller_id']);
        unset($_SESSION['cart_p_type']);
        unset($_SESSION['cart_p_size']);
        unset($_SESSION['cart_p_id']);
        unset($_SESSION['cart_p_qty']);
        unset($_SESSION['cart_p_current_price']);
        unset($_SESSION['cart_p_name']);
        unset($_SESSION['cart_p_featured_photo']);
        
        $success = "You have successfully ordered the product! If your order is in transit, we will notify you as soon as possible.\nThanks.";
    }else{
        if($billing_address == false){
            $error = "Your billing address is not complete information, please update your billing address!";
        }
        if($shipping_address == false){
            $error = "Your shipping address is not complete information, please update your shipping address!";
        }
        if($billing_address == false && $shipping_address == false){
            $error = "Your billing and shipping address is not complete information, please update your billing and shipping address!";
        }
    }

    $output = array(
        "success"       =>  $success,
        "error"         =>  $error
    );

    echo json_encode($output);
}

// For Cancellation Order
if(isset($_POST['cancelMyOrder'])){
    $orderId = "";
    $orderId = $_POST['order_id'];
    $remarks = "Cancelled by Buyer";
    $upd = "UPDATE tbl_purchase_order SET status='Cancelled', remarks=:remarks WHERE order_id=:ord";
    $p = [
        ":ord"      =>  $orderId,
        ":remarks"  =>  $remarks
    ];
    $res1 = $c->fetchData($pdo, $upd, $p);

    $upd = "UPDATE tbl_purchase_payment SET status='Cancelled' WHERE order_id=:ord";
    $p = [
        ":ord"      =>  $orderId
    ];
    $res1 = $c->fetchData($pdo, $upd, $p);

    $upd = "UPDATE tbl_purchase_item SET status='Cancelled' WHERE order_id=:ord";
    $p = [
        ":ord"      =>  $orderId
    ];
    $res1 = $c->fetchData($pdo, $upd, $p);
    echo "success";
}


// For Change Size In product-single
if(isset($_POST['onChangeSize'])){
    $sizeId = $_POST['sizeId'];
    $prodId = $_POST['prodId'];
    $total = 0;
    $sql = "SELECT s.*, p.p_retail, a.size_value FROM tbl_product p LEFT JOIN tbl_product_size s ON p.p_id=s.p_id LEFT JOIN tbl_size a ON s.size_id=a.size_id WHERE s.p_id=:prodId AND s.size_id=:sizeId";
    $p = [
        ":prodId"   =>  $prodId,
        ":sizeId"   =>  $sizeId
    ];
    $res = $c->fetchData($pdo, $sql, $p);
    if($res){
        foreach($res as $row){
            $total = $row['p_retail'] / $row['size_value'];
        }
    }
    echo number_format($total);
}

// GENERATE RANDOM TEXT
function generateRandomText($length) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomText = '';
    $charactersLength = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $randomText .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomText;
}

function generateRandomNumber($length) {
    $characters = '0123456789';
    $randomText = '';
    $charactersLength = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $randomText .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomText;
}
?>