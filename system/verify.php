<?php
include("inc/config.php");
include("inc/class.php");

$c = new CustomizeClass();

if(isset($_GET['email']) && isset($_GET['token'])){
    $email = $_GET['email'];
    $token = $_GET['token'];

    $sql = "SELECT * FROM tbl_user WHERE email=:email AND role='Rider'";
    $p = [
        ":email"    =>  $email
    ];
    $r = $c->fetchData($pdo, $sql, $p);
    foreach($r as $row){
        $sql1 = "SELECT * FROM tbl_rider WHERE user_id=:uIds AND r_token=:token";
        $p1 = [
            ":uIds"  =>  $row['id'],
            ":token"    =>  $token
        ];
        $re = $c->countData($pdo, $sql1, $p1);
        if($re > 0){
            $upd = "UPDATE tbl_rider SET r_status='Verified' WHERE user_id=:u_id";
            $u = [
                ":u_id" =>  $row['id'] 
            ];
            $res = $c->updateData($pdo, $upd, $u);

            $update = "UPDATE tbl_user SET status='Active' WHERE id=:u_id";
            $res1 = $c->updateData($pdo, $update, $u);

            $_SESSION['myMessage'] = "You verified your email successfully!";
            header("Location: login.php");
            exit;
        }
    }
}
?>