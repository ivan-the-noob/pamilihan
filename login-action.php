<?php
include("system/inc/config.php");
include("system/inc/functions.php");
include("system/inc/CSRF_Protect.php");
?>
<?php
if (isset($_SESSION['customer'])) {
    // If user is already logged in, redirect to dashboard
    header("Location: shop.php");
    exit;
}
if (isset($_SESSION['rider'])) {
    // If user is already logged in, redirect to dashboard
    header("Location: index.php");
    exit;
}
if(isset($_POST['form1'])) {
        
    if(empty($_POST['cust_email']) || empty($_POST['cust_password'])) {
        echo 'Please fill in all fields.';
    } else {
        
        $cust_email = strip_tags($_POST['cust_email']);
        $cust_password = strip_tags($_POST['cust_password']);
       
        $statement = $pdo->prepare("SELECT * FROM tbl_user WHERE email=? AND role='customer'");
        $statement->execute(array($cust_email));
        $total = $statement->rowCount();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
       
        foreach($result as $row) {
            $cust_status = $row['status'];
            $row_password = $row['password'];
            $row_role = $row['role'];
        }

        if($total==0) {
            $response = [
                'status' => 'error',
                'message' => 'Incorrect email or password.',
                'redirect' => 'login.php',  // Redirect location
            ];
            header('Content-Type: application/json');  // Set content type to JSON
            echo json_encode($response);
        } else {
            //using MD5 form
           
            if( $row_password != md5($cust_password) ) {
                $response = [
                    'status' => 'error',
                    'message' => 'Incorrect email or password.',
                    'redirect' => 'login.php',  // Redirect location
                ];
                header('Content-Type: application/json');  // Set content type to JSON
                echo json_encode($response);
            } else {
                if($cust_status == "Inactive") {
                    $response = [
                        'status' => 'error',
                        'message' => 'Account Is Inactive',
                        'redirect' => 'login.php',  // Redirect location
                    ];
                    header('Content-Type: application/json');  // Set content type to JSON
                    echo json_encode($response);
                }else if($cust_status == "Pending"){
                    $response = [
                        'status' => 'error',
                        'message' => 'We\'ve sent an email to you, please verify your account.',
                        'redirect' => 'login.php',  // Redirect location
                    ];
                    header('Content-Type: application/json');  // Set content type to JSON
                    echo json_encode($response);
                }else {
                    if ($row_role == 'customer') {
                        $statement = $pdo->prepare("SELECT * FROM tbl_user WHERE email=?");
                        $statement->execute([$cust_email]);
                        $user = $statement->fetch(PDO::FETCH_ASSOC);
                        $_SESSION['customer'] = $user;
                        $_SESSION['customer']['status'] = $cust_status;
                        $page = "";
                        if(isset($_GET['page'])){
                            $page = $_GET['page'];
                        }else{
                            $page = "shop.php";
                        }
                        $response = [
                            'status' => 'success',
                            'message' => 'Login Successful!',
                            'redirect' => $page,  // Redirect location
                        ];
                        header('Content-Type: application/json');  // Set content type to JSON
                        echo json_encode($response);
                    } elseif ($row_role == 'rider') {
                        // Fetch user details to use for session
                        $statement = $pdo->prepare("SELECT * FROM tbl_rider WHERE email=?");
                        $statement->execute([$cust_email]);
                        $user = $statement->fetch(PDO::FETCH_ASSOC);
                        $_SESSION['rider'] = $user;
                        $response = [
                            'status' => 'success',
                            'message' => 'Login Successful!',
                            'redirect' => 'riderdashboard.php',  // Redirect location
                        ];
                        header('Content-Type: application/json');  // Set content type to JSON
                        echo json_encode($response);
                    }else{
                        $response = [
                            'status' => 'error',
                            'message' => 'Incorrect email or password.',
                            'redirect' => 'login.php',  // Redirect location
                        ];
                        header('Content-Type: application/json');  // Set content type to JSON
                        echo json_encode($response);
                    }
                }
            }
            
        }
    }
}
?>